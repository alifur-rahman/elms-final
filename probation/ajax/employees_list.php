<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT pbi.PBI_ID AS id, pbi.PBI_NAME, pbi.last_name, pbi.PBI_CODE AS employee_code,
               pbi.job_title, pbi.dept_id, pbi.candidate_id,
               d.name AS dept_name,
               des.name AS designation_name,
               o.joining_date, o.probation_months,
               (SELECT COUNT(*) FROM probation_appraisals pa2 WHERE pa2.employee_id = pbi.PBI_ID) AS existing_count
        FROM personnel_basic_info pbi
        INNER JOIN offers o ON o.id = pbi.offer_id AND o.response_status = 'Accepted'
        LEFT JOIN departments d ON d.id = pbi.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        WHERE pbi.candidate_id IS NOT NULL
          AND pbi.EMPLOYMENT_TYPE = 'Probationer'
        ORDER BY pbi.PBI_NAME ASC
    ");
    $rows = $stmt->fetchAll();
    $employees = [];
    foreach ($rows as $r) {
        $employees[] = [
            'id'               => (int)$r['id'],
            'name'             => $r['PBI_NAME'] . ' ' . $r['last_name'],
            'first_name'       => $r['PBI_NAME'],
            'last_name'        => $r['last_name'],
            'employee_code'    => $r['employee_code'] ?? 'N/A',
            'job_title'        => $r['job_title'] ?? 'N/A',
            'dept_id'          => (int)$r['dept_id'],
            'dept_name'        => $r['dept_name'] ?? 'N/A',
            'designation_name' => $r['designation_name'] ?? 'N/A',
            'joining_date'     => $r['joining_date'],
            'probation_months' => (int)$r['probation_months'],
            'has_appraisal'    => (int)$r['existing_count'] > 0,
        ];
    }
    echo json_encode(['success' => true, 'data' => $employees]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
