<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) $body = $_POST;

    $job_id         = (int) ($body['job_id'] ?? 0);
    $first_name     = trim($body['first_name'] ?? '');
    $last_name      = trim($body['last_name'] ?? '');
    $email          = trim($body['email'] ?? '');
    $phone          = trim($body['phone'] ?? '');
    $nid            = trim($body['nid'] ?? '');
    $blood_group    = trim($body['blood_group'] ?? '');
    $dob            = trim($body['dob'] ?? '');
    $gender         = trim($body['gender'] ?? '');
    $present_address = trim($body['present_address'] ?? '');
    $permanent_address = trim($body['permanent_address'] ?? '');
    $current_salary = (float) ($body['current_salary'] ?? 0);
    $expected_salary = (float) ($body['expected_salary'] ?? 0);
    $status         = trim($body['status'] ?? 'Applied');

    $errors = [];
    if ($job_id <= 0) $errors[] = 'Job is required';
    if ($first_name === '') $errors[] = 'First name is required';
    if ($last_name === '') $errors[] = 'Last name is required';
    if ($email === '') $errors[] = 'Email is required';
    if ($status === '') $errors[] = 'Status is required';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => implode('. ', $errors)]);
        exit;
    }

    // Auto-generate candidate code
    $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM candidates");
    $maxRow = $stmt->fetch();
    $nextId = ($maxRow['max_id'] ?? 0) + 1;
    $code = 'CAND-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare("
        INSERT INTO candidates
            (job_id, code, first_name, last_name, email, phone, nid, blood_group,
             dob, gender, present_address, permanent_address,
             current_salary, expected_salary, status)
        VALUES
            (:job_id, :code, :first_name, :last_name, :email, :phone, :nid, :blood_group,
             :dob, :gender, :present_address, :permanent_address,
             :current_salary, :expected_salary, :status)
    ");

    $stmt->execute([
        ':job_id'           => $job_id,
        ':code'             => $code,
        ':first_name'       => $first_name,
        ':last_name'        => $last_name,
        ':email'            => $email,
        ':phone'            => $phone ?: null,
        ':nid'              => $nid ?: null,
        ':blood_group'      => $blood_group ?: null,
        ':dob'              => $dob ?: null,
        ':gender'           => $gender ?: null,
        ':present_address'  => $present_address ?: null,
        ':permanent_address' => $permanent_address ?: null,
        ':current_salary'   => $current_salary ?: 0,
        ':expected_salary'  => $expected_salary ?: 0,
        ':status'           => $status,
    ]);

    $insertId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Candidate "' . htmlspecialchars($first_name . ' ' . $last_name) . '" added successfully!',
        'id'      => (int) $insertId,
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
