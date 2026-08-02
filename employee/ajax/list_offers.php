<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

$pdo = getDB();

$status = $_GET['status'] ?? '';
$responseStatus = $_GET['response_status'] ?? '';

$sql = "SELECT o.*,
               c.first_name, c.last_name, c.email, c.phone,
               r.title AS job_title,
               d.name AS dept_name,
               des.name AS designation_name,
               p.PBI_ID AS employee_id
        FROM elms_offers o
        LEFT JOIN elms_candidates c ON c.id = o.candidate_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
        LEFT JOIN elms_departments d ON d.id = o.dept_id
        LEFT JOIN elms_designations des ON des.id = o.designation_id
        LEFT JOIN elms_personnel_basic_info p ON p.offer_id = o.id
        WHERE 1=1";

$params = [];

if ($status) {
    $sql .= " AND o.status = :status";
    $params[':status'] = $status;
}

if ($responseStatus) {
    $statuses = array_map('trim', explode(',', $responseStatus));
    $placeholders = [];
    foreach ($statuses as $i => $s) {
        $key = ":rs$i";
        $placeholders[] = $key;
        $params[$key] = $s;
    }
    $sql .= " AND o.response_status IN (" . implode(',', $placeholders) . ")";
}

$sql .= " ORDER BY o.accepted_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$offers = $stmt->fetchAll();

$result = [];
foreach ($offers as &$o) {
    $o['candidate_name'] = trim(($o['first_name'] ?? '') . ' ' . ($o['last_name'] ?? ''));
    $o['employee_created'] = !empty($o['employee_id']);
    unset($o['employee_id']);
}
$result = $offers;

echo json_encode(['success' => true, 'data' => $result]);
