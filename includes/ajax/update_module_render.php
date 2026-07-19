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

    $id         = (int)($body['id'] ?? 0);
    $renderFile = trim($body['render_file'] ?? '');
    $label      = trim($body['label'] ?? '');
    $desc       = trim($body['description'] ?? '');
    $isActive   = isset($body['is_active']) ? (int)$body['is_active'] : null;

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    $updates = [];
    $params  = [':id' => $id];

    if ($renderFile !== '') {
        $updates[] = 'render_file = :rf';
        $params[':rf'] = $renderFile;
    }
    if ($label !== '') {
        $updates[] = 'label = :lbl';
        $params[':lbl'] = $label;
    }
    if (isset($body['description'])) {
        $updates[] = 'description = :desc';
        $params[':desc'] = $desc;
    }
    if ($isActive !== null) {
        $updates[] = 'is_active = :act';
        $params[':act'] = $isActive;
    }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }

    $sql = "UPDATE template_modules SET " . implode(', ', $updates) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true, 'message' => 'Module updated successfully!']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
