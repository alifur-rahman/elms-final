<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    // Get candidates who have accepted offers (joined employees)
    $stmt = $pdo->query("
        SELECT c.id, c.first_name, c.last_name, c.code AS employee_code, c.job_id,
               r.title AS job_title, o.dept_id,
               d.name AS dept_name,
               des.name AS designation_name,
               o.joining_date, o.probation_months,
               (SELECT COUNT(*) FROM probation_appraisals pa2 WHERE pa2.employee_id = c.id) AS existing_count
        FROM candidates c
        INNER JOIN offers o ON o.candidate_id = c.id AND o.response_status = 'Accepted'
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        WHERE c.status_id >= 4
        ORDER BY c.first_name ASC
    ");
    $rows = $stmt->fetchAll();
    $employees = [];
    foreach ($rows as $r) {
        $employees[] = [
            'id'               => (int)$r['id'],
            'name'             => $r['first_name'] . ' ' . $r['last_name'],
            'first_name'       => $r['first_name'],
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
