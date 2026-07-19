<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $type = trim($_GET['type'] ?? '');
    $sql = "SELECT * FROM merge_tags";
    $params = [];
    if ($type !== '') {
        $sql .= " WHERE type = :type";
        $params[':type'] = $type;
    }
    $sql .= " ORDER BY sort_order ASC, tag ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    $tags = [];
    foreach ($rows as $r) {
        $tags[] = [
            'id'          => (int)$r['id'],
            'type'        => $r['type'],
            'tag'         => $r['tag'],
            'label'       => $r['label'],
            'description' => $r['description'] ?? '',
            'sort_order'  => (int)$r['sort_order'],
        ];
    }
    echo json_encode(['success' => true, 'data' => $tags]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
