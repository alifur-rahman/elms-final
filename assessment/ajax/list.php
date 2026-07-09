<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT
            c.id AS candidate_id, c.first_name, c.last_name, c.email, c.phone,
            c.job_id, c.status AS candidate_status, c.applied_at,
            r.title AS job_title,
            COUNT(i.id) AS interview_count,
            ROUND(AVG(CASE WHEN i.status = 'Completed' AND i.rating IS NOT NULL THEN i.rating END), 1) AS avg_rating,
            MAX(CASE WHEN i.decision = 'Pass' THEN 1 ELSE 0 END) AS has_pass_interview
        FROM candidates c
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN interviews i ON i.candidate_id = c.id
        GROUP BY c.id
        HAVING has_pass_interview = 1 OR c.status IN ('Interview', 'Offered', 'Joined')
        ORDER BY c.created_at DESC
    ");

    $rows = $stmt->fetchAll();

    $candidates = [];
    foreach ($rows as $r) {
        $candidates[] = [
            'candidate_id'      => (int) $r['candidate_id'],
            'first_name'        => $r['first_name'],
            'last_name'         => $r['last_name'],
            'email'             => $r['email'] ?? '',
            'phone'             => $r['phone'] ?? '',
            'job_id'            => (int) $r['job_id'],
            'job_title'         => $r['job_title'] ?? 'N/A',
            'candidate_status'  => $r['candidate_status'],
            'applied_at'        => $r['applied_at'],
            'interview_count'   => (int) $r['interview_count'],
            'avg_rating'        => $r['avg_rating'] ? (float) $r['avg_rating'] : null,
            'has_pass_interview' => (bool) $r['has_pass_interview'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $candidates]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
