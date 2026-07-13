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

    $offerId   = (int)($body['offer_id'] ?? 0);
    $recipient = trim($body['recipient'] ?? '');
    $subject   = trim($body['subject'] ?? '');
    $bodyText  = trim($body['body'] ?? '');

    if ($offerId <= 0 || empty($recipient) || empty($subject)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id, recipient, and subject are required']);
        exit;
    }

    // Log the email attempt
    $logStmt = $pdo->prepare("INSERT INTO offer_email_log (offer_id, recipient, subject, body, status) VALUES (:oid, :rec, :sub, :body, 'Sent')");
    $logStmt->execute([
        ':oid' => $offerId,
        ':rec' => $recipient,
        ':sub' => $subject,
        ':body'=> $bodyText,
    ]);

    // Update the offer letter as sent
    $lStmt = $pdo->prepare("UPDATE offer_letters SET status = 'Sent', sent_at = NOW(), sent_to_email = :rec WHERE offer_id = :oid");
    $lStmt->execute([':rec' => $recipient, ':oid' => $offerId]);

    // Update the offer sent_at date
    $oStmt = $pdo->prepare("UPDATE offers SET sent_at = NOW() WHERE id = :id");
    $oStmt->execute([':id' => $offerId]);

    // Log lifecycle
    $oInfo = $pdo->prepare("SELECT candidate_id FROM offers WHERE id = :id");
    $oInfo->execute([':id' => $offerId]);
    $candidateId = (int)$oInfo->fetchColumn();
    if ($candidateId) addCandidateLog($pdo, $candidateId, 'offer_sent', null, null, 'Offer sent via email to ' . $recipient);

    echo json_encode(['success' => true, 'message' => 'Offer email logged as sent to ' . htmlspecialchars($recipient) . '.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
