<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->prepare("
        SELECT pa.id AS appraisal_id, pa.employee_id, pa.end_date, pa.overall_score, pa.recommendation, pa.status AS appraisal_status,
               pbi.PBI_NAME, pbi.last_name, pbi.PBI_CODE, pbi.job_title, pbi.dept_id, pbi.EMPLOYMENT_TYPE, pbi.PBI_EMAIL, pbi.PBI_MOBILE, pbi.PBI_DOJ,
               d.name AS dept_name,
               des.name AS designation_name,
               o.id AS offer_id, o.salary, o.joining_date, o.probation_months,
               cl.id AS confirmation_id, cl.status AS confirmation_status
        FROM elms_probation_appraisals pa
        LEFT JOIN elms_personnel_basic_info pbi ON pbi.PBI_ID = pa.employee_id
        LEFT JOIN elms_departments d ON d.id = pbi.dept_id
        LEFT JOIN elms_designations des ON des.id = pbi.DESG_ID
        LEFT JOIN elms_offers o ON o.id = pbi.offer_id
        LEFT JOIN elms_confirmation_letters cl ON cl.employee_id = pa.employee_id
        WHERE pa.status = 'Completed'
        ORDER BY pa.end_date DESC
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $employees = [];
    foreach ($rows as $r) {
        $employees[] = [
            'appraisal_id'       => (int)$r['appraisal_id'],
            'employee_id'        => (int)$r['employee_id'],
            'employee_name'      => trim(($r['PBI_NAME'] ?? '') . ' ' . ($r['last_name'] ?? '')),
            'employee_code'      => $r['PBI_CODE'] ?? '',
            'job_title'          => $r['job_title'] ?? 'N/A',
            'dept_name'          => $r['dept_name'] ?? 'N/A',
            'designation_name'   => $r['designation_name'] ?? 'N/A',
            'employment_type'    => $r['EMPLOYMENT_TYPE'] ?? 'Probationer',
            'email'              => $r['PBI_EMAIL'] ?? '',
            'phone'              => $r['PBI_MOBILE'] ?? '',
            'joining_date'       => $r['joining_date'] ?? $r['PBI_DOJ'] ?? null,
            'probation_months'   => (int)($r['probation_months'] ?? 6),
            'end_date'           => $r['end_date'],
            'salary'             => (float)($r['salary'] ?? 0),
            'overall_score'      => $r['overall_score'] ? (float)$r['overall_score'] : null,
            'recommendation'     => $r['recommendation'] ?? '',
            'offer_id'           => $r['offer_id'] ? (int)$r['offer_id'] : null,
            'confirmation_id'    => $r['confirmation_id'] ? (int)$r['confirmation_id'] : null,
            'confirmation_status'=> $r['confirmation_status'] ?? null,
        ];
    }

    echo json_encode(['success' => true, 'data' => $employees]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
