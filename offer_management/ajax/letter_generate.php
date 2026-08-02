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
    $content   = trim($body['letter_content'] ?? '');

    if ($offerId <= 0 || empty($content)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id and letter_content are required']);
        exit;
    }

    // Check if a letter already exists
    $chk = $pdo->prepare("SELECT id FROM elms_offer_letters WHERE offer_id = :oid LIMIT 1");
    $chk->execute([':oid' => $offerId]);
    $existing = $chk->fetch();

    if ($existing) {
        $uStmt = $pdo->prepare("UPDATE elms_offer_letters SET letter_content = :content, status = 'Generated', generated_at = NOW() WHERE id = :id");
        $uStmt->execute([':content' => $content, ':id' => $existing['id']]);
        $letterId = $existing['id'];
    } else {
        $iStmt = $pdo->prepare("INSERT INTO elms_offer_letters (offer_id, letter_content, letter_format, status, generated_at) VALUES (:oid, :content, 'html', 'Generated', NOW())");
        $iStmt->execute([':oid' => $offerId, ':content' => $content]);
        $letterId = $pdo->lastInsertId();
    }

    // Log lifecycle
    $oInfo = $pdo->prepare("SELECT candidate_id FROM elms_offers WHERE id = :id");
    $oInfo->execute([':id' => $offerId]);
    $candidateId = (int)$oInfo->fetchColumn();
    if ($candidateId) addCandidateLog($pdo, $candidateId, 'offer_letter_generated', null, null, 'Offer letter generated.');

    echo json_encode(['success' => true, 'message' => 'Offer letter generated.', 'letter_id' => $letterId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
