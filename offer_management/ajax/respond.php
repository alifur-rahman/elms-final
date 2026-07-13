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

    $offerId  = (int)($body['offer_id'] ?? 0);
    $response = trim($body['response'] ?? 'Accepted');
    $notes    = trim($body['notes'] ?? '');

    if ($offerId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id is required']);
        exit;
    }

    if (!in_array($response, ['Accepted', 'Rejected'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Response must be Accepted or Rejected']);
        exit;
    }

    $pdo->beginTransaction();

    // Update offer response
    $oStmt = $pdo->prepare("UPDATE offers SET response_status = :resp, responded_at = NOW(), response_notes = :notes WHERE id = :id");
    $oStmt->execute([':resp' => $response, ':notes' => $notes, ':id' => $offerId]);

    // Update candidate status accordingly
    $offer = $pdo->prepare("SELECT candidate_id FROM offers WHERE id = :id");
    $offer->execute([':id' => $offerId]);
    $oData = $offer->fetch();

    if ($oData) {
        $candidateId = (int)$oData['candidate_id'];

        if ($response === 'Accepted') {
            // Move candidate to Offered status (5) if not already
            $cStmt = $pdo->prepare("SELECT status_id FROM candidates WHERE id = :id");
            $cStmt->execute([':id' => $candidateId]);
            $cs = $cStmt->fetchColumn();

            if ((int)$cs < 5) {
                $uStmt = $pdo->prepare("UPDATE candidates SET status_id = 5 WHERE id = :id");
                $uStmt->execute([':id' => $candidateId]);
                addCandidateLog($pdo, $candidateId, 'offer_accepted', null, null, 'Offer accepted: ' . $notes);
            }
        } elseif ($response === 'Rejected') {
            // Move candidate to Rejected status (99)
            $uStmt = $pdo->prepare("UPDATE candidates SET status_id = 99 WHERE id = :id");
            $uStmt->execute([':id' => $candidateId]);
            addCandidateLog($pdo, $candidateId, 'offer_rejected_candidate', null, null, 'Offer rejected by candidate: ' . $notes);
        }
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Candidate response recorded as ' . $response . '.']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
