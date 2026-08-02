<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $status = trim($_GET['status'] ?? '');
    $deptId = (int)($_GET['dept_id'] ?? 0);

    $where = [];
    $params = [];

    if ($status !== '') {
        $where[] = "pa.status = :status";
        $params[':status'] = $status;
    }
    if ($deptId > 0) {
        $where[] = "pbi.dept_id = :dept_id";
        $params[':dept_id'] = $deptId;
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("
        SELECT pa.*,
               pbi.PBI_NAME, pbi.last_name, pbi.job_title, pbi.dept_id,
               d.name AS dept_name,
               des.name AS designation_name
        FROM elms_probation_appraisals pa
        LEFT JOIN elms_personnel_basic_info pbi ON pbi.PBI_ID = pa.employee_id
        LEFT JOIN elms_departments d ON d.id = pbi.dept_id
        LEFT JOIN elms_designations des ON des.id = (SELECT o.designation_id FROM elms_offers o WHERE o.id = pbi.offer_id LIMIT 1)
        $whereClause
        ORDER BY pa.created_at DESC
    ");
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $appraisals = [];
    foreach ($rows as $r) {
        $daysLeft = null;
        if ($r['end_date']) {
            $end = new DateTime($r['end_date']);
            $now = new DateTime();
            $daysLeft = (int)$now->diff($end)->format('%r%a');
        }

        $appraisals[] = [
            'id'               => (int)$r['id'],
            'employee_id'      => (int)$r['employee_id'],
            'employee_name'    => ($r['PBI_NAME'] ?? '') . ' ' . ($r['last_name'] ?? ''),
            'job_title'        => $r['job_title'] ?? 'N/A',
            'dept_id'          => (int)$r['dept_id'],
            'dept_name'        => $r['dept_name'] ?? 'N/A',
            'designation_name' => $r['designation_name'] ?? 'N/A',
            'joining_date'     => $r['joining_date'],
            'probation_months' => (int)$r['probation_months'],
            'end_date'         => $r['end_date'],
            'review_date'      => $r['review_date'],
            'status'           => $r['status'],
            'overall_score'    => $r['overall_score'] ? (float)$r['overall_score'] : null,
            'recommendation'   => $r['recommendation'] ?? '',
            'remarks'          => $r['remarks'] ?? '',
            'days_left'        => $daysLeft,
            'created_at'       => $r['created_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $appraisals]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
