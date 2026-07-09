<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT c.id, c.first_name, c.last_name, c.job_id, c.status_id,
               cs.name AS status_name, c.expected_salary, c.applied_at,
               r.title AS job_title, r.dept_id,
               o.id AS offer_id, o.status AS offer_status
        FROM candidates c
        LEFT JOIN candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN offers o ON o.candidate_id = c.id AND o.status IN ('Pending','Approved')
        WHERE (c.status_id = 4 AND o.id IS NULL)
           OR (c.status_id = 5 AND o.status = 'Pending')
        ORDER BY c.applied_at DESC
    ");

    $rows = $stmt->fetchAll();
    $candidates = [];
    foreach ($rows as $r) {
        $candidates[] = [
            'id'              => (int)$r['id'],
            'first_name'      => $r['first_name'],
            'last_name'       => $r['last_name'],
            'job_id'          => (int)$r['job_id'],
            'job_title'       => $r['job_title'] ?? 'N/A',
            'dept_id'         => (int)$r['dept_id'],
            'status_id'       => (int)$r['status_id'],
            'status_name'     => $r['status_name'],
            'expected_salary' => $r['expected_salary'] ? (float)$r['expected_salary'] : null,
            'applied_at'      => $r['applied_at'],
            'offer_id'        => $r['offer_id'] ? (int)$r['offer_id'] : null,
            'offer_status'    => $r['offer_status'] ?? null,
        ];
    }

    echo json_encode(['success' => true, 'data' => $candidates]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
