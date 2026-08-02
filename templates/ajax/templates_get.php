<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'id is required']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM elms_letter_templates WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $r = $stmt->fetch();

    if (!$r) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Template not found.']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => [
        'id'         => (int)$r['id'],
        'name'       => $r['name'],
        'type'       => $r['type'],
        'content'    => $r['content'],
        'status'     => $r['status'],
        'created_at' => $r['created_at'],
        'updated_at' => $r['updated_at'],
    ]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
