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

    $check = $pdo->prepare("SELECT id FROM elms_requisitions WHERE id = :id");
    $check->execute([':id' => $id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Requisition not found']);
        exit;
    }

    $title          = trim($body['title'] ?? '');
    $dept_id        = (int) ($body['dept_id'] ?? 0);
    $location       = trim($body['location'] ?? '');
    $type           = trim($body['type'] ?? 'Full Time');
    $vacancies      = (int) ($body['vacancies'] ?? 1);
    $deadline       = trim($body['deadline'] ?? '');
    $education      = trim($body['education'] ?? '');
    $experience     = (float) ($body['experience'] ?? 0);
    $salary_min     = (float) ($body['salary_min'] ?? 0);
    $salary_max     = (float) ($body['salary_max'] ?? 0);
    $skills         = trim($body['skills'] ?? '');
    $description    = trim($body['description'] ?? '');
    $responsibilities = trim($body['responsibilities'] ?? '');
    $benefits       = trim($body['benefits'] ?? '');
    $priority       = trim($body['priority'] ?? 'Medium');
    $status         = trim($body['status'] ?? 'Active');

    $errors = [];
    if ($title === '') $errors[] = 'Job title is required';
    if ($dept_id <= 0) $errors[] = 'Department is required';
    if ($deadline === '') $errors[] = 'Deadline is required';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => implode('. ', $errors)]);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE elms_requisitions SET
            title = :title, dept_id = :dept_id, location = :location, type = :type,
            vacancies = :vacancies, deadline = :deadline, education = :education,
            experience = :experience, salary_min = :salary_min, salary_max = :salary_max,
            skills = :skills, description = :description, responsibilities = :responsibilities,
            benefits = :benefits, priority = :priority, status = :status
        WHERE id = :id
    ");

    $stmt->execute([
        ':id'             => $id,
        ':title'          => $title,
        ':dept_id'        => $dept_id,
        ':location'       => $location ?: null,
        ':type'           => $type,
        ':vacancies'      => $vacancies,
        ':deadline'       => $deadline ?: null,
        ':education'      => $education ?: null,
        ':experience'     => $experience,
        ':salary_min'     => $salary_min ?: 0,
        ':salary_max'     => $salary_max ?: 0,
        ':skills'         => $skills ?: null,
        ':description'    => $description ?: null,
        ':responsibilities' => $responsibilities ?: null,
        ':benefits'       => $benefits ?: null,
        ':priority'       => $priority,
        ':status'         => $status,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Requisition "' . htmlspecialchars($title) . '" updated successfully!',
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
