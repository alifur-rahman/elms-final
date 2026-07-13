<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $type = trim($_GET['type'] ?? '');

    $sql = "SELECT * FROM letter_templates";
    $params = [];
    if ($type !== '') {
        $sql .= " WHERE type = :type";
        $params[':type'] = $type;
    }
    $sql .= " ORDER BY name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $templates = [];
    foreach ($rows as $r) {
        $templates[] = [
            'id'         => (int)$r['id'],
            'name'       => $r['name'],
            'type'       => $r['type'],
            'content'    => $r['content'],
            'status'     => $r['status'],
            'created_at' => $r['created_at'],
            'updated_at' => $r['updated_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $templates]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
