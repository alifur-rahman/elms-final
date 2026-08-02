<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT c.*, cs.name AS status_name, r.title AS job_title, d.name AS dept_name
        FROM elms_candidates c
        LEFT JOIN elms_candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
        LEFT JOIN elms_departments d ON d.id = r.dept_id
        ORDER BY c.created_at DESC
    ");

    $rows = $stmt->fetchAll();

    $candidates = [];
    foreach ($rows as $r) {
        $candidates[] = [
            'id'                => (int) $r['id'],
            'job_id'            => (int) $r['job_id'],
            'code'              => $r['code'] ?? '',
            'first_name'        => $r['first_name'],
            'last_name'         => $r['last_name'],
            'email'             => $r['email'],
            'phone'             => $r['phone'] ?? '',
            'nid'               => $r['nid'] ?? '',
            'blood_group'       => $r['blood_group'] ?? '',
            'dob'               => $r['dob'] ?? '',
            'gender'            => $r['gender'] ?? '',
            'present_address'   => $r['present_address'] ?? '',
            'permanent_address' => $r['permanent_address'] ?? '',
            'current_salary'    => (float) $r['current_salary'],
            'expected_salary'   => (float) $r['expected_salary'],
            'status_id'         => (int) $r['status_id'],
            'status_name'       => $r['status_name'],
            'applied_at'        => $r['applied_at'],
            'created_at'        => $r['created_at'],
            'job_title'         => $r['job_title'] ?? 'N/A',
            'dept_name'         => $r['dept_name'] ?? 'N/A',
        ];
    }

    echo json_encode(['success' => true, 'data' => $candidates]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
