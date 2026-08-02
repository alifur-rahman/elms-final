<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $deptId = isset($_GET['dept_id']) ? (int)$_GET['dept_id'] : 0;

    if ($deptId > 0) {
        $stmt = $pdo->prepare("SELECT id, name, code, dept_id FROM elms_designations WHERE dept_id = :dept_id ORDER BY name");
        $stmt->execute([':dept_id' => $deptId]);
    } else {
        $stmt = $pdo->query("SELECT id, name, code, dept_id FROM elms_designations ORDER BY name");
    }

    $rows = $stmt->fetchAll();
    $data = [];
    foreach ($rows as $r) {
        $data[] = [
            'id'      => (int)$r['id'],
            'name'    => $r['name'],
            'code'    => $r['code'] ?? '',
            'dept_id' => (int)$r['dept_id'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
