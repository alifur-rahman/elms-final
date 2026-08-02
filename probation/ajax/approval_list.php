<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $level = trim($_GET['level'] ?? '');
    $deptId = (int)($_GET['dept_id'] ?? 0);

    $where = ["pa.status IN ('Pending','In Progress')"];
    $params = [];

    if ($level !== '') {
        $where[] = "pw_next.level = :level";
        $params[':level'] = $level;
    }
    if ($deptId > 0) {
        $where[] = "pbi.dept_id = :dept_id";
        $params[':dept_id'] = $deptId;
    }

    $whereClause = 'WHERE ' . implode(' AND ', $where);

    $stmt = $pdo->prepare("
        SELECT pa.*,
               pbi.PBI_NAME, pbi.last_name, pbi.job_title, pbi.dept_id,
               d.name AS dept_name,
               des.name AS designation_name,
               pw_next.level AS current_level,
               pw_next.id AS wf_id
        FROM elms_probation_appraisals pa
        LEFT JOIN elms_personnel_basic_info pbi ON pbi.PBI_ID = pa.employee_id
        LEFT JOIN elms_departments d ON d.id = pbi.dept_id
        LEFT JOIN elms_designations des ON des.id = (SELECT o.designation_id FROM elms_offers o WHERE o.id = pbi.offer_id LIMIT 1)
        LEFT JOIN elms_probation_workflow pw_next ON pw_next.appraisal_id = pa.id
            AND pw_next.status = 'Pending'
            AND pw_next.id = (
                SELECT pw1.id FROM elms_probation_workflow pw1
                WHERE pw1.appraisal_id = pa.id
                  AND pw1.status = 'Pending'
                ORDER BY CASE pw1.level
                    WHEN 'Self Assessment' THEN 1
                    WHEN 'Supervisor' THEN 2
                    WHEN 'Dept Head' THEN 3
                    WHEN 'HR Review' THEN 4
                    WHEN 'GM' THEN 5
                    WHEN 'Managing Director' THEN 6
                    ELSE 99
                END ASC
                LIMIT 1
            )
        $whereClause
        ORDER BY pa.updated_at DESC
    ");
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $approvals = [];
    foreach ($rows as $r) {
        $daysLeft = null;
        if ($r['end_date']) {
            $end = new DateTime($r['end_date']);
            $now = new DateTime();
            $daysLeft = (int)$now->diff($end)->format('%r%a');
        }

        $approvals[] = [
            'id'               => (int)$r['id'],
            'employee_id'      => (int)$r['employee_id'],
            'employee_name'    => ($r['PBI_NAME'] ?? '') . ' ' . ($r['last_name'] ?? ''),
            'job_title'        => $r['job_title'] ?? 'N/A',
            'dept_name'        => $r['dept_name'] ?? 'N/A',
            'designation_name' => $r['designation_name'] ?? 'N/A',
            'joining_date'     => $r['joining_date'],
            'probation_months' => (int)$r['probation_months'],
            'end_date'         => $r['end_date'],
            'status'           => $r['status'],
            'overall_score'    => $r['overall_score'] ? (float)$r['overall_score'] : null,
            'recommendation'   => $r['recommendation'] ?? '',
            'current_level'    => $r['current_level'] ?? '',
            'wf_id'            => $r['wf_id'] ? (int)$r['wf_id'] : null,
            'days_left'        => $daysLeft,
            'created_at'       => $r['created_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $approvals]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
