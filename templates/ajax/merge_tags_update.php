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

    $id    = (int)($body['id'] ?? 0);
    $tag   = strtoupper(trim($body['tag'] ?? ''));
    $label = trim($body['label'] ?? '');
    $desc  = trim($body['description'] ?? '');
    $order = (int)($body['sort_order'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    $check = $pdo->prepare("SELECT id, type FROM merge_tags WHERE id = :id");
    $check->execute([':id' => $id]);
    $existing = $check->fetch();
    if (!$existing) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Merge tag not found']);
        exit;
    }

    $updates = [];
    $params = [':id' => $id];

    if ($tag !== '') {
        if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $tag)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Tag must contain only uppercase letters, numbers, and underscores']);
            exit;
        }
        $updates[] = 'tag = :tag';
        $params[':tag'] = $tag;
    }
    if ($label !== '') { $updates[] = 'label = :label'; $params[':label'] = $label; }
    if (array_key_exists('description', $body)) { $updates[] = 'description = :desc'; $params[':desc'] = $desc; }
    if (array_key_exists('sort_order', $body)) { $updates[] = 'sort_order = :order'; $params[':order'] = $order; }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }

    $sql = "UPDATE merge_tags SET " . implode(', ', $updates) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true, 'message' => 'Merge tag updated successfully!']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
