<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT c.id AS candidate_id, c.first_name, c.last_name, c.email, c.phone,
               c.job_id, c.status_id, cs.name AS status_name, c.applied_at,
               r.title AS job_title, d.name AS dept_name
        FROM elms_candidates c
        LEFT JOIN elms_candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
        LEFT JOIN elms_departments d ON d.id = r.dept_id
        WHERE c.status_id = 1
        ORDER BY c.created_at DESC
    ");

    $rows = $stmt->fetchAll();

    $data = [];
    foreach ($rows as $r) {
        $data[] = [
            'candidate_id'     => (int) $r['candidate_id'],
            'first_name'       => $r['first_name'],
            'last_name'        => $r['last_name'],
            'email'            => $r['email'],
            'phone'            => $r['phone'] ?? '',
            'job_id'           => (int) $r['job_id'],
            'job_title'        => $r['job_title'] ?? 'N/A',
            'dept_name'        => $r['dept_name'] ?? 'N/A',
            'status_id'        => (int) $r['status_id'],
            'status_name'      => $r['status_name'],
            'applied_at'       => $r['applied_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $data]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
