<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $letterId = (int)($_GET['letter_id'] ?? 0);

    if ($letterId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'letter_id required']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT cl.*,
               pbi.PBI_NAME, pbi.last_name, pbi.PBI_CODE, pbi.job_title,
               d.name AS dept_name,
               des.name AS designation_name,
               o.salary, o.joining_date, o.probation_months
        FROM elms_confirmation_letters cl
        LEFT JOIN elms_personnel_basic_info pbi ON pbi.PBI_ID = cl.employee_id
        LEFT JOIN elms_departments d ON d.id = pbi.dept_id
        LEFT JOIN elms_designations des ON des.id = pbi.DESG_ID
        LEFT JOIN elms_offers o ON o.id = COALESCE(cl.offer_id, pbi.offer_id)
        WHERE cl.id = :id
    ");
    $stmt->execute([':id' => $letterId]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Letter not found']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'id'               => (int)$row['id'],
            'employee_id'      => (int)$row['employee_id'],
            'employee_name'    => trim(($row['PBI_NAME'] ?? '') . ' ' . ($row['last_name'] ?? '')),
            'employee_code'    => $row['PBI_CODE'] ?? '',
            'job_title'        => $row['job_title'] ?? 'N/A',
            'dept_name'        => $row['dept_name'] ?? 'N/A',
            'designation_name' => $row['designation_name'] ?? 'N/A',
            'employment_type'  => $row['employment_type'] ?? 'Probationer',
            'salary'           => (float)($row['salary'] ?? 0),
            'letter_content'   => $row['letter_content'] ?? '',
            'status'           => $row['status'],
            'generated_at'     => $row['generated_at'],
            'sent_at'          => $row['sent_at'],
            'sent_to_email'    => $row['sent_to_email'] ?? '',
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
