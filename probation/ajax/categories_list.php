<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, name, weight, sort_order FROM probation_categories WHERE status = 'Active' ORDER BY sort_order ASC");
    $rows = $stmt->fetchAll();
    $cats = [];
    foreach ($rows as $r) {
        $cats[] = [
            'id'     => (int)$r['id'],
            'name'   => $r['name'],
            'weight' => (float)$r['weight'],
            'order'  => (int)$r['sort_order'],
        ];
    }
    echo json_encode(['success' => true, 'data' => $cats]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
