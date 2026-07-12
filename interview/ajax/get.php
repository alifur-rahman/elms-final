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
        SELECT i.*, c.first_name, c.last_name, r.title AS job_title
        FROM interview_log i
        LEFT JOIN candidates c ON c.id = i.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        WHERE i.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Interview not found']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $row]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
