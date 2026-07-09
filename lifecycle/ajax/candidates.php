<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT c.id, c.first_name, c.last_name, c.code, c.job_id, c.status_id,
               cs.name AS status_name, r.title AS job_title, c.applied_at
        FROM candidates c
        LEFT JOIN candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        ORDER BY c.created_at DESC
    ");

    $rows = $stmt->fetchAll();

    $candidates = [];
    foreach ($rows as $r) {
        $candidates[] = [
            'id'          => (int) $r['id'],
            'first_name'  => $r['first_name'],
            'last_name'   => $r['last_name'],
            'code'        => $r['code'] ?? '',
            'job_id'      => (int) $r['job_id'],
            'job_title'   => $r['job_title'] ?? 'N/A',
            'status_id'   => (int) $r['status_id'],
            'status_name' => $r['status_name'],
            'applied_at'  => $r['applied_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $candidates]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
