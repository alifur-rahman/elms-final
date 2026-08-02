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
               DATEDIFF(DATE_ADD(o.joining_date, INTERVAL o.probation_months MONTH), CURDATE()) AS days_left
        FROM elms_personnel_basic_info pbi
        INNER JOIN elms_offers o ON o.id = pbi.offer_id AND o.response_status = 'Accepted'
        LEFT JOIN elms_departments d ON d.id = pbi.dept_id
        LEFT JOIN elms_designations des ON des.id = o.designation_id
        WHERE pbi.candidate_id IS NOT NULL
          AND pbi.EMPLOYMENT_TYPE = 'Probationer'
          AND NOT EXISTS (SELECT 1 FROM elms_probation_appraisals pa WHERE pa.employee_id = pbi.PBI_ID)
        ORDER BY pbi.PBI_NAME ASC
    ");
    $rows = $stmt->fetchAll();
    $employees = [];
    foreach ($rows as $r) {
        $employees[] = [
            'id'               => (int)$r['id'],
            'name'             => $r['PBI_NAME'] . ' ' . $r['last_name'],
            'employee_code'    => $r['employee_code'] ?? 'N/A',
            'job_title'        => $r['job_title'] ?? 'N/A',
            'dept_name'        => $r['dept_name'] ?? 'N/A',
            'designation_name' => $r['designation_name'] ?? 'N/A',
            'joining_date'     => $r['joining_date'],
            'probation_months' => (int)$r['probation_months'],
            'days_left'        => $r['days_left'] !== null ? (int)$r['days_left'] : null,
        ];
    }
    echo json_encode(['success' => true, 'data' => $employees]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
