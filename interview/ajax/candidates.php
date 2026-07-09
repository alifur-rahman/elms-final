<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    // Return candidates eligible for interview scheduling
    $stmt = $pdo->query("
        SELECT c.id, c.first_name, c.last_name, c.job_id, c.status,
               r.title AS job_title
        FROM candidates c
        LEFT JOIN requisitions r ON r.id = c.job_id
        WHERE c.status IN ('Shortlisted', 'Interview')
        ORDER BY c.first_name ASC
    ");

    $rows = $stmt->fetchAll();

    $candidates = [];
    foreach ($rows as $r) {
        $candidates[] = [
            'id'          => (int) $r['id'],
            'first_name'  => $r['first_name'],
            'last_name'   => $r['last_name'],
            'job_id'      => (int) $r['job_id'],
            'job_title'   => $r['job_title'] ?? 'N/A',
            'status'      => $r['status'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $candidates]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
