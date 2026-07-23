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

    $letterId = (int)($body['letter_id'] ?? 0);
    $action   = trim($body['action'] ?? '');

    if ($letterId <= 0 || !in_array($action, ['approve', 'reject'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Valid letter_id and action required']);
        exit;
    }

    $newStatus = $action === 'approve' ? 'Approved' : 'Rejected';
    $uStmt = $pdo->prepare("UPDATE confirmation_letters SET status = :status, approved_at = NOW(), approved_by = 'Admin User' WHERE id = :id");
    $uStmt->execute([':status' => $newStatus, ':id' => $letterId]);

    echo json_encode(['success' => true, 'message' => 'Confirmation letter ' . strtolower($newStatus) . '.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
