<?php
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST' && $method !== 'DELETE') {
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

    $check = $pdo->prepare("SELECT id, first_name, last_name FROM candidates WHERE id = :id");
    $check->execute([':id' => $id]);
    $candidate = $check->fetch();

    if (!$candidate) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Candidate not found']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM candidates WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $name = $candidate['first_name'] . ' ' . $candidate['last_name'];

    echo json_encode([
        'success' => true,
        'message' => 'Candidate "' . htmlspecialchars($name) . '" deleted successfully!',
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
