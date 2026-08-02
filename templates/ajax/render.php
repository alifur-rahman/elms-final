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

    $module   = trim($body['module'] ?? '');
    $recordId = (int)($body['record_id'] ?? 0);
    $content  = trim($body['content'] ?? '');

    if ($module === '' || $recordId <= 0 || $content === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'module, record_id, and content are required']);
        exit;
    }

    // Load the module config to get the render file path
    $modStmt = $pdo->prepare("SELECT render_file FROM elms_template_modules WHERE module_name = :m AND is_active = 1");
    $modStmt->execute([':m' => $module]);
    $mod = $modStmt->fetch();

    if (!$mod || empty($mod['render_file'])) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => "No render file configured for module '$module'"]);
        exit;
    }

    $renderPath = ROOT_PATH . '/' . $mod['render_file'];
    if (!file_exists($renderPath)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => "Render file not found: {$mod['render_file']}"]);
        exit;
    }

    require_once $renderPath;

    // Call the module-specific function to get the tag map
    $funcName = 'get' . ucfirst($module) . 'TagMap';
    if (!function_exists($funcName)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => "Render function '$funcName' not found in {$mod['render_file']}"]);
        exit;
    }

    $tagMap = $funcName($pdo, $recordId);

    // Replace all {{TAG}} in content
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
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
