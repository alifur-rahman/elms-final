<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'id is required']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT pa.*,
               c.id AS candidate_id, c.first_name, c.last_name, c.email, c.phone, c.nid, c.blood_group, c.dob, c.gender,
               c.present_address, c.permanent_address, c.current_salary, c.expected_salary, c.code AS candidate_code, c.applied_at,
               c.status_id AS candidate_status_id, cs.name AS candidate_status_name,
               r.id AS job_id, r.title AS job_title, r.location AS job_location, r.type AS job_type, r.education AS job_education,
               r.experience AS job_experience, r.salary_min AS job_salary_min, r.salary_max AS job_salary_max,
               r.skills AS job_skills, r.description AS job_description,
               o.id AS offer_id, o.salary AS offer_salary, o.joining_date, o.notes AS offer_notes, o.response_status,
               o.sent_at AS offer_sent_at, o.accepted_at AS offer_accepted_at, o.response_notes AS offer_response_notes,
               o.status AS offer_status, o.probation_months, o.dept_id,
               d.name AS dept_name,
               des.name AS designation_name
        FROM probation_appraisals pa
        LEFT JOIN candidates c ON c.id = pa.employee_id
        LEFT JOIN candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN offers o ON o.candidate_id = c.id AND o.response_status = 'Accepted'
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        WHERE pa.id = :id LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $r = $stmt->fetch();

    if (!$r) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Appraisal not found.']);
        exit;
    }

    // Get scores
    $scoresStmt = $pdo->prepare("
        SELECT ps.id, ps.category_id, ps.score, ps.comments, pc.name AS category_name, pc.weight
        FROM probation_scores ps
        LEFT JOIN probation_categories pc ON pc.id = ps.category_id
        WHERE ps.appraisal_id = :aid
        ORDER BY pc.sort_order ASC
    ");
    $scoresStmt->execute([':aid' => $id]);
    $scores = [];
    foreach ($scoresStmt as $s) {
        $scores[] = [
            'id'            => (int)$s['id'],
            'category_id'   => (int)$s['category_id'],
            'category_name' => $s['category_name'],
            'weight'        => (float)$s['weight'],
            'score'         => (float)$s['score'],
            'comments'      => $s['comments'] ?? '',
        ];
    }

    // Get workflow
    $wfStmt = $pdo->prepare("SELECT * FROM probation_workflow WHERE appraisal_id = :aid ORDER BY FIELD(level, 'Self Assessment','Supervisor','Dept Head','HR Review','GM','Managing Director') ASC");
    $wfStmt->execute([':aid' => $id]);
    $workflow = [];
    foreach ($wfStmt as $w) {
        $workflow[] = [
            'id'          => (int)$w['id'],
            'level'       => $w['level'],
            'status'      => $w['status'],
            'approved_by' => $w['approved_by'] ?? '',
            'comments'    => $w['comments'] ?? '',
            'action_at'   => $w['action_at'],
        ];
    }

    // Get screening details
    $screenStmt = $pdo->prepare("SELECT * FROM screenings WHERE candidate_id = :cid ORDER BY created_at DESC");
    $screenStmt->execute([':cid' => $r['candidate_id']]);
    $screenings = [];
    foreach ($screenStmt as $s) {
        $screenings[] = [
            'id'                 => (int)$s['id'],
            'screened_by'        => $s['screened_by'] ?? '',
            'date'               => $s['date'] ?? '',
            'rating'             => $s['rating'] ? (float)$s['rating'] : null,
            'communication_score'=> $s['communication_score'] ? (float)$s['communication_score'] : null,
            'experience_match'   => $s['experience_match'] ?? '',
            'skill_match'        => $s['skill_match'] ?? '',
            'education_match'    => $s['education_match'] ?? '',
            'remarks'            => $s['remarks'] ?? '',
            'result'             => $s['result'] ?? '',
            'created_at'         => $s['created_at'],
        ];
    }

    // Get interview logs
    $intStmt = $pdo->prepare("SELECT * FROM interview_log WHERE candidate_id = :cid ORDER BY round ASC");
    $intStmt->execute([':cid' => $r['candidate_id']]);
    $interviews = [];
    foreach ($intStmt as $iv) {
        $interviews[] = [
            'id'                => (int)$iv['id'],
            'round'             => (int)$iv['round'],
            'round_name'        => $iv['round_name'] ?? '',
            'type'              => $iv['type'] ?? '',
            'interviewer'       => $iv['interviewer'] ?? '',
            'date'              => $iv['date'] ?? '',
            'status'            => $iv['status'] ?? '',
            'score_technical'   => $iv['score_technical'] ? (float)$iv['score_technical'] : null,
            'score_communication'=> $iv['score_communication'] ? (float)$iv['score_communication'] : null,
            'score_problem_solving'=> $iv['score_problem_solving'] ? (float)$iv['score_problem_solving'] : null,
            'score_attitude'    => $iv['score_attitude'] ? (float)$iv['score_attitude'] : null,
            'rating'            => $iv['rating'] ? (float)$iv['rating'] : null,
            'decision'          => $iv['decision'] ?? '',
            'reject_reason'     => $iv['reject_reason'] ?? '',
            'feedback'          => $iv['feedback'] ?? '',
            'is_final_round'    => (bool)$iv['is_final_round'],
            'created_at'        => $iv['created_at'],
        ];
    }

    // Get candidate lifecycle logs
    $logsStmt = $pdo->prepare("SELECT * FROM candidate_logs WHERE candidate_id = :cid ORDER BY created_at DESC");
    $logsStmt->execute([':cid' => $r['candidate_id']]);
    $screeningLogs = [];
    $interviewLogs = [];
    $offerLogs = [];
    $otherLogs = [];
    foreach ($logsStmt as $log) {
        $entry = [
            'id'         => (int)$log['id'],
            'action'     => $log['action'],
            'from_status'=> $log['from_status'] ?? '',
            'to_status'  => $log['to_status'] ?? '',
            'notes'      => $log['notes'] ?? '',
            'created_by' => $log['created_by'] ?? '',
            'created_at' => $log['created_at'],
        ];
        if (strpos($log['action'], 'screening_') === 0) {
            $screeningLogs[] = $entry;
        } elseif (strpos($log['action'], 'interview_') === 0) {
            $interviewLogs[] = $entry;
        } elseif (strpos($log['action'], 'offer_') === 0 || strpos($log['action'], 'salary_') === 0) {
            $offerLogs[] = $entry;
        } else {
            $otherLogs[] = $entry;
        }
    }

    // Determine current level (first pending in order)
    $current_level = '';
    $levelOrder = ['Self Assessment', 'Supervisor', 'Dept Head', 'HR Review', 'GM', 'Managing Director'];
    foreach ($levelOrder as $lvl) {
        $found = false;
        foreach ($workflow as $wf) {
            if ($wf['level'] === $lvl) {
                if ($wf['status'] === 'Pending' || $wf['status'] === '') {
                    $current_level = $lvl;
                    $found = true;
                }
                break;
            }
        }
        if ($found) break;
    }

    $daysLeft = null;
    if ($r['end_date']) {
        $end = new DateTime($r['end_date']);
        $now = new DateTime();
        $daysLeft = (int)$now->diff($end)->format('%r%a');
    }

    echo json_encode(['success' => true, 'data' => [
        'id'                  => (int)$r['id'],
        'employee_id'         => (int)$r['employee_id'],
        'employee_name'       => ($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''),
        'email'               => $r['email'] ?? '',
        'phone'               => $r['phone'] ?? '',
        'candidate_code'      => $r['candidate_code'] ?? '',
        'candidate_status'    => $r['candidate_status_name'] ?? '',
        'nid'                 => $r['nid'] ?? '',
        'blood_group'         => $r['blood_group'] ?? '',
        'dob'                 => $r['dob'] ?? '',
        'gender'              => $r['gender'] ?? '',
        'present_address'     => $r['present_address'] ?? '',
        'permanent_address'   => $r['permanent_address'] ?? '',
        'current_salary'      => $r['current_salary'] ? (float)$r['current_salary'] : null,
        'expected_salary'     => $r['expected_salary'] ? (float)$r['expected_salary'] : null,
        'applied_at'          => $r['applied_at'] ?? '',
        // Job / Requisition
        'job_title'           => $r['job_title'] ?? 'N/A',
        'job_location'        => $r['job_location'] ?? '',
        'job_type'            => $r['job_type'] ?? '',
        'job_education'       => $r['job_education'] ?? '',
        'job_experience'      => $r['job_experience'] ?? '',
        'job_salary_min'      => $r['job_salary_min'] ? (float)$r['job_salary_min'] : null,
        'job_salary_max'      => $r['job_salary_max'] ? (float)$r['job_salary_max'] : null,
        'job_skills'          => $r['job_skills'] ?? '',
        'job_description'     => $r['job_description'] ?? '',
        // Offer
        'offer_salary'        => $r['offer_salary'] ? (float)$r['offer_salary'] : null,
        'offer_notes'         => $r['offer_notes'] ?? '',
        'offer_sent_at'       => $r['offer_sent_at'] ?? '',
        'offer_accepted_at'   => $r['offer_accepted_at'] ?? '',
        'offer_response_notes'=> $r['offer_response_notes'] ?? '',
        // Department / Designation
        'dept_id'             => (int)$r['dept_id'],
        'dept_name'           => $r['dept_name'] ?? 'N/A',
        'designation_name'    => $r['designation_name'] ?? 'N/A',
        // Probation
        'joining_date'        => $r['joining_date'],
        'probation_months'    => (int)$r['probation_months'],
        'end_date'            => $r['end_date'],
        'review_date'         => $r['review_date'],
        'status'              => $r['status'],
        'overall_score'       => $r['overall_score'] ? (float)$r['overall_score'] : null,
        'recommendation'      => $r['recommendation'] ?? '',
        'remarks'             => $r['remarks'] ?? '',
        'supervisor_comments' => $r['supervisor_comments'] ?? '',
        'dept_head_comments'  => $r['dept_head_comments'] ?? '',
        'hr_review'           => $r['hr_review'] ?? '',
        'days_left'           => $daysLeft,
        'scores'              => $scores,
        'workflow'            => $workflow,
        'current_level'       => $current_level,
        'screenings'          => $screenings,
        'interviews'          => $interviews,
        'candidate_logs'      => [
            'screening' => $screeningLogs,
            'interview' => $interviewLogs,
            'offer'     => $offerLogs,
            'other'     => $otherLogs,
        ],
        'created_at'          => $r['created_at'],
        'updated_at'          => $r['updated_at'],
    ]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
