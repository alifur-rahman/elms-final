<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

$pdo = getDB();
$offerId = (int)($_GET['offer_id'] ?? 0);

if ($offerId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'offer_id required']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT o.*,
           c.first_name, c.last_name, c.email, c.phone, c.nid,
           c.blood_group, c.dob, c.gender,
           c.present_address, c.permanent_address,
           c.current_salary, c.expected_salary,
           r.title AS job_title,
           d.name AS dept_name, d.id AS dept_id,
           des.name AS designation_name, des.id AS designation_id
    FROM elms_offers o
    LEFT JOIN elms_candidates c ON c.id = o.candidate_id
    LEFT JOIN elms_requisitions r ON r.id = c.job_id
    LEFT JOIN elms_departments d ON d.id = o.dept_id
    LEFT JOIN elms_designations des ON des.id = o.designation_id
    WHERE o.id = :id
");
$stmt->execute([':id' => $offerId]);
$offer = $stmt->fetch();

if (!$offer) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Offer not found']);
    exit;
}

$stmt2 = $pdo->prepare("SELECT * FROM elms_personnel_basic_info WHERE offer_id = :oid");
$stmt2->execute([':oid' => $offerId]);
$existing = $stmt2->fetch();

$salary = null;
if ($existing) {
    $stmt3 = $pdo->prepare("SELECT * FROM elms_salary_info WHERE offer_id = :oid");
    $stmt3->execute([':oid' => $offerId]);
    $salary = $stmt3->fetch() ?: null;
}

echo json_encode([
    'success' => true,
    'data' => [
        'offer' => $offer,
        'employee_exists' => !empty($existing),
        'employee_id' => $existing['PBI_ID'] ?? null,
        'employee' => $existing ?: null,
        'salary' => $salary,
    ]
]);
