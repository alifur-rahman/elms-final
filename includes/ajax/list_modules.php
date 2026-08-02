<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT * FROM elms_template_modules ORDER BY id ASC");
    $rows = $stmt->fetchAll();
    $modules = [];
    foreach ($rows as $r) {
        $modules[] = [
            'id'          => (int)$r['id'],
            'module_name' => $r['module_name'],
            'label'       => $r['label'],
            'render_file' => $r['render_file'],
            'description' => $r['description'] ?? '',
            'is_active'   => (int)$r['is_active'],
        ];
    }
    echo json_encode(['success' => true, 'data' => $modules]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
