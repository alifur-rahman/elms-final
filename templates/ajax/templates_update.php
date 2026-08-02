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

    $id      = (int)($body['id'] ?? 0);
    $name    = trim($body['name'] ?? '');
    $type    = trim($body['type'] ?? 'Offer');
    $content = trim($body['content'] ?? '');
    $status  = trim($body['status'] ?? 'Active');

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'id is required']);
        exit;
    }

    if (empty($name) || empty($content)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Name and content are required']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE elms_letter_templates SET name = :name, type = :type, content = :content, status = :status WHERE id = :id");
    $stmt->execute([':id' => $id, ':name' => $name, ':type' => $type, ':content' => $content, ':status' => $status]);

    echo json_encode(['success' => true, 'message' => 'Template updated.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
