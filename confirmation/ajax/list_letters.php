<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $status = trim($_GET['status'] ?? '');

    $where = [];
    $params = [];

    if ($status !== '') {
        $where[] = "cl.status = :status";
        $params[':status'] = $status;
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("
        SELECT cl.*,
               pbi.PBI_NAME, pbi.last_name, pbi.PBI_CODE, pbi.job_title, pbi.dept_id, pbi.EMPLOYMENT_TYPE, pbi.offer_id AS pbi_offer_id, pbi.PBI_EMAIL,
               d.name AS dept_name,
               des.name AS designation_name,
               o.salary, o.joining_date
        FROM confirmation_letters cl
        LEFT JOIN personnel_basic_info pbi ON pbi.PBI_ID = cl.employee_id
        LEFT JOIN departments d ON d.id = pbi.dept_id
        LEFT JOIN designations des ON des.id = pbi.DESG_ID
        LEFT JOIN offers o ON o.id = COALESCE(cl.offer_id, pbi.offer_id)
        $whereClause
        ORDER BY cl.created_at DESC
    ");
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $letters = [];
    foreach ($rows as $r) {
        $letters[] = [
            'id'                => (int)$r['id'],
            'employee_id'       => (int)$r['employee_id'],
            'employee_name'     => trim(($r['PBI_NAME'] ?? '') . ' ' . ($r['last_name'] ?? '')),
            'employee_code'     => $r['PBI_CODE'] ?? '',
            'job_title'         => $r['job_title'] ?? 'N/A',
            'dept_name'         => $r['dept_name'] ?? 'N/A',
            'designation_name'  => $r['designation_name'] ?? 'N/A',
            'employment_type'   => $r['employment_type'] ?? 'Probationer',
            'salary'            => (float)($r['salary'] ?? 0),
            'status'            => $r['status'],
            'generated_at'      => $r['generated_at'],
            'approved_at'       => $r['approved_at'],
            'sent_at'           => $r['sent_at'],
            'sent_to_email'     => $r['sent_to_email'] ?? '',
            'email'             => $r['PBI_EMAIL'] ?? '',
        ];
    }

    echo json_encode(['success' => true, 'data' => $letters]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
