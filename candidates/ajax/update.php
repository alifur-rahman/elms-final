<?php
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST' && $method !== 'PUT') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) $body = $_POST;

    $id = (int) ($body['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    $check = $pdo->prepare("SELECT id, first_name, last_name FROM elms_candidates WHERE id = :id");
    $check->execute([':id' => $id]);
    $candidate = $check->fetch();
    if (!$candidate) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Candidate not found']);
        exit;
    }

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

    $errors = [];
    if ($job_id <= 0) $errors[] = 'Job is required';
    if ($first_name === '') $errors[] = 'First name is required';
    if ($last_name === '') $errors[] = 'Last name is required';
    if ($email === '') $errors[] = 'Email is required';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => implode('. ', $errors)]);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE elms_candidates SET
            job_id = :job_id, first_name = :first_name, last_name = :last_name,
            email = :email, phone = :phone, nid = :nid, blood_group = :blood_group,
            dob = :dob, gender = :gender, present_address = :present_address,
            permanent_address = :permanent_address, current_salary = :current_salary,
            expected_salary = :expected_salary
        WHERE id = :id
    ");

    $stmt->execute([
        ':id'               => $id,
        ':job_id'           => $job_id,
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
    ]);

    addCandidateLog($pdo, $id, 'candidate_updated', null, null, 'Profile updated');

    echo json_encode([
        'success' => true,
        'message' => 'Candidate "' . htmlspecialchars($first_name . ' ' . $last_name) . '" updated successfully!',
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
