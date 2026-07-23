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

    $employeeId = (int)($body['employee_id'] ?? 0);
    $content    = trim($body['content'] ?? '');

    if ($employeeId <= 0 || $content === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'employee_id and content are required']);
        exit;
    }

    $renderPath = ROOT_PATH . '/templates/render/confirmation.php';
    if (!file_exists($renderPath)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Render file not found']);
        exit;
    }

    require_once $renderPath;

    $tagMap = getConfirmationTagMap($pdo, $employeeId);

    $rendered = preg_replace_callback('/\{\{([A-Z_][A-Z0-9_]*)\}\}/', function ($m) use ($tagMap) {
        return $tagMap[$m[1]] ?? $m[0];
    }, $content);

    echo json_encode([
        'success' => true,
        'data' => [
            'rendered_content' => $rendered,
            'tag_count'        => count($tagMap),
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
