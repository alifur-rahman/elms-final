<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT c.id, c.first_name, c.last_name, c.code AS employee_code, c.job_id,
               r.title AS job_title, o.dept_id,
               d.name AS dept_name,
               des.name AS designation_name,
               o.joining_date, o.probation_months,
               DATEDIFF(DATE_ADD(o.joining_date, INTERVAL o.probation_months MONTH), CURDATE()) AS days_left
        FROM candidates c
        INNER JOIN offers o ON o.candidate_id = c.id AND o.response_status = 'Accepted'
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        WHERE c.status_id >= 4
          AND NOT EXISTS (SELECT 1 FROM probation_appraisals pa WHERE pa.employee_id = c.id)
        ORDER BY c.first_name ASC
    ");
    $rows = $stmt->fetchAll();
    $employees = [];
    foreach ($rows as $r) {
        $employees[] = [
            'id'               => (int)$r['id'],
            'name'             => $r['first_name'] . ' ' . $r['last_name'],
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
