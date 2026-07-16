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
        $where[] = "c.dept_id = :dept_id";
        $params[':dept_id'] = $deptId;
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("
        SELECT pa.*,
               c.first_name, c.last_name, c.job_id,
               r.title AS job_title, o.dept_id,
               d.name AS dept_name,
               des.name AS designation_name
        FROM probation_appraisals pa
        LEFT JOIN candidates c ON c.id = pa.employee_id
        LEFT JOIN offers o ON o.candidate_id = c.id AND o.response_status = 'Accepted'
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
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
            'employee_name'    => ($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''),
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
