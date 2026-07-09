<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT c.*, r.title AS job_title, d.name AS dept_name
        FROM candidates c
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = r.dept_id
        WHERE c.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Candidate not found']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $row]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
