<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, name FROM departments ORDER BY name ASC");
    $rows = $stmt->fetchAll();
    $depts = [];
    foreach ($rows as $r) {
        $depts[] = ['id' => (int)$r['id'], 'name' => $r['name']];
    }
    echo json_encode(['success' => true, 'data' => $depts]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
