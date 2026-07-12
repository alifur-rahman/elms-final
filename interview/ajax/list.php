<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';

function computeActionState($ivStatus, $decision, $isFinal) {
    if ($ivStatus === null) return 'schedule';
    if ($ivStatus === 'Waiting for Feedback') return 'feedback';
    if ($ivStatus === 'Completed') {
        if ($decision === 'Pass' && !$isFinal) return 'next_round';
        return 'done';
    }
    return 'done';
}

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT
            c.id AS cid, c.first_name, c.last_name, c.job_id, c.status_id,
            cs.name AS candidate_status_name, r.title AS job_title, d.name AS dept_name,
            i.id AS interview_id, i.date, i.type, i.interviewer, i.round, i.round_name,
            i.rating, i.score_technical, i.score_communication, i.score_problem_solving,
            i.score_attitude, i.reject_reason, i.is_final_round, i.feedback, i.decision,
            i.status AS interview_status
        FROM candidates c
        LEFT JOIN candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = r.dept_id
        LEFT JOIN interview_log i ON i.id = (
            SELECT i2.id FROM interview_log i2
            WHERE i2.candidate_id = c.id AND i2.status != 'Rescheduled'
            ORDER BY i2.id DESC LIMIT 1
        )
        WHERE (c.status_id IN (2, 3)
           OR (c.status_id IN (4, 7, 8)
               AND EXISTS (SELECT 1 FROM interview_log il WHERE il.candidate_id = c.id AND il.status != 'Rescheduled')))
        ORDER BY
            CASE c.status_id WHEN 2 THEN 0 WHEN 3 THEN 1 ELSE 2 END,
            c.first_name ASC
    ");

    $rows = $stmt->fetchAll();

    $list = [];
    foreach ($rows as $r) {
        $interviewStatus = $r['interview_status'] ?? null;
        $decision        = $r['decision'] ?? '';
        $isFinal         = !empty($r['is_final_round']);

        $list[] = [
            // Candidate info
            'candidate_id'          => (int) $r['cid'],
            'first_name'            => $r['first_name'],
            'last_name'             => $r['last_name'],
            'job_id'                => (int) $r['job_id'],
            'job_title'             => $r['job_title'] ?? 'N/A',
            'dept_name'             => $r['dept_name'] ?? 'N/A',
            'status_id'             => (int) $r['status_id'],
            'candidate_status_name' => $r['candidate_status_name'] ?? '',

            // Latest interview info
            'interview_id'          => $r['interview_id'] ? (int) $r['interview_id'] : null,
            'date'                  => $r['date'] ?? null,
            'type'                  => $r['type'] ?? '',
            'interviewer'           => $r['interviewer'] ?? '',
            'round'                 => $r['round'] ? (int) $r['round'] : null,
            'round_name'            => $r['round_name'] ?? '',
            'rating'                => $r['rating'] ? (float) $r['rating'] : null,
            'score_technical'       => $r['score_technical'] ? (float) $r['score_technical'] : null,
            'score_communication'   => $r['score_communication'] ? (float) $r['score_communication'] : null,
            'score_problem_solving' => $r['score_problem_solving'] ? (float) $r['score_problem_solving'] : null,
            'score_attitude'        => $r['score_attitude'] ? (float) $r['score_attitude'] : null,
            'reject_reason'         => $r['reject_reason'] ?? '',
            'is_final_round'        => $isFinal,
            'feedback'              => $r['feedback'] ?? '',
            'decision'              => $decision,
            'interview_status'      => $interviewStatus,

            // Computed action state
            'action_state' => computeActionState($interviewStatus, $decision, $isFinal),
        ];
    }

    echo json_encode(['success' => true, 'data' => $list]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
