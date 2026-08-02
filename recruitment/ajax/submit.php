<?php
// ============================================================
// ERP HRIS - AJAX: Submit Requisition
// ============================================================

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
        INSERT INTO elms_requisitions 
            (title, dept_id, location, type, vacancies, deadline, education, experience, 
             salary_min, salary_max, skills, description, responsibilities, benefits, priority, status)
        VALUES 
            (:title, :dept_id, :location, :type, :vacancies, :deadline, :education, :experience,
             :salary_min, :salary_max, :skills, :description, :responsibilities, :benefits, :priority, :status)
    ");

    $stmt->execute([
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

    $insertId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Requisition "' . htmlspecialchars($title) . '" created successfully!',
        'id'      => (int) $insertId,
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
