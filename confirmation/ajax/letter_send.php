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

    $letterId  = (int)($body['letter_id'] ?? 0);
    $recipient = trim($body['recipient'] ?? '');
    $subject   = trim($body['subject'] ?? '');
    $bodyText  = trim($body['body'] ?? '');

    if ($letterId <= 0 || empty($recipient) || empty($subject)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'letter_id, recipient, and subject are required']);
        exit;
    }

    $uStmt = $pdo->prepare("UPDATE confirmation_letters SET status = 'Sent', sent_at = NOW(), sent_to_email = :rec WHERE id = :id");
    $uStmt->execute([':rec' => $recipient, ':id' => $letterId]);

    echo json_encode(['success' => true, 'message' => 'Confirmation letter sent to ' . htmlspecialchars($recipient) . '.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
