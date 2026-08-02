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

    $type  = trim($body['type'] ?? 'Offer');
    $tag   = strtoupper(trim($body['tag'] ?? ''));
    $label = trim($body['label'] ?? '');
    $desc  = trim($body['description'] ?? '');
    $order = (int)($body['sort_order'] ?? 0);

    $errors = [];
    if ($tag === '') $errors[] = 'Tag name is required';
    if ($label === '') $errors[] = 'Label is required';
    if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $tag)) $errors[] = 'Tag must contain only uppercase letters, numbers, and underscores';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => implode('. ', $errors)]);
        exit;
    }

    $check = $pdo->prepare("SELECT id FROM elms_merge_tags WHERE tag = :tag AND type = :type");
    $check->execute([':tag' => $tag, ':type' => $type]);
    if ($check->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => "Tag $tag already exists for module $type"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO elms_merge_tags (type, tag, label, description, sort_order) VALUES (:type, :tag, :label, :desc, :order)");
    $stmt->execute([':type' => $type, ':tag' => $tag, ':label' => $label, ':desc' => $desc, ':order' => $order]);

    echo json_encode(['success' => true, 'message' => 'Merge tag created successfully!', 'id' => (int)$pdo->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
