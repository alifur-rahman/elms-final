<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $candidateId = isset($_GET['candidate_id']) ? (int) $_GET['candidate_id'] : 0;

    $sql = "
        SELECT i.*, c.first_name, c.last_name, r.title AS job_title
        FROM elms_interview_log i
        LEFT JOIN elms_candidates c ON c.id = i.candidate_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
    ";
    $params = [];
    if ($candidateId > 0) {
        $sql .= " WHERE i.candidate_id = :cid";
        $params[':cid'] = $candidateId;
    }
    $sql .= " ORDER BY i.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $interviews = [];
    foreach ($rows as $r) {
        $interviews[] = [
            'id'                  => (int) $r['id'],
            'candidate_id'        => (int) $r['candidate_id'],
            'date'                => $r['date'],
            'type'                => $r['type'],
            'interviewer'         => $r['interviewer'],
            'round'               => (int) $r['round'],
            'round_name'          => $r['round_name'] ?? '',
            'rating'              => $r['rating'] ? (float) $r['rating'] : null,
            'score_technical'     => $r['score_technical'] ? (float) $r['score_technical'] : null,
            'score_communication' => $r['score_communication'] ? (float) $r['score_communication'] : null,
            'score_problem_solving' => $r['score_problem_solving'] ? (float) $r['score_problem_solving'] : null,
            'score_attitude'      => $r['score_attitude'] ? (float) $r['score_attitude'] : null,
            'reject_reason'       => $r['reject_reason'] ?? '',
            'is_final_round'      => (bool) $r['is_final_round'],
            'feedback'            => $r['feedback'] ?? '',
            'decision'            => $r['decision'] ?? '',
            'status'              => $r['status'],
            'first_name'          => $r['first_name'],
            'last_name'           => $r['last_name'],
            'job_title'           => $r['job_title'] ?? 'N/A',
        ];
    }

    // Fetch latest screening for this candidate
    $screening = null;
    if ($candidateId > 0) {
        $sStmt = $pdo->prepare("
            SELECT s.*, c.first_name, c.last_name, r.title AS job_title
            FROM elms_screenings s
            LEFT JOIN elms_candidates c ON c.id = s.candidate_id
            LEFT JOIN elms_requisitions r ON r.id = c.job_id
            WHERE s.candidate_id = :cid
            ORDER BY s.id DESC LIMIT 1
        ");
        $sStmt->execute([':cid' => $candidateId]);
        $sRow = $sStmt->fetch();
        if ($sRow) {
            $screening = [
                'id'                  => (int) $sRow['id'],
                'rating'              => $sRow['rating'] ? (float) $sRow['rating'] : null,
                'communication_score' => $sRow['communication_score'] ? (float) $sRow['communication_score'] : null,
                'experience_match'    => $sRow['experience_match'] ?? '',
                'skill_match'         => $sRow['skill_match'] ?? '',
                'education_match'     => $sRow['education_match'] ?? '',
                'result'              => $sRow['result'] ?? '',
                'remarks'             => $sRow['remarks'] ?? '',
                'screened_by'         => $sRow['screened_by'] ?? '',
                'date'                => $sRow['date'] ?? '',
            ];
        }
    }

    echo json_encode(['success' => true, 'data' => $interviews, 'screening' => $screening]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
