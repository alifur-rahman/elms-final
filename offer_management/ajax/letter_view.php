<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $offerId = (int)($_GET['offer_id'] ?? 0);

    if ($offerId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id is required']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT ol.*, c.first_name, c.last_name, c.email
        FROM offer_letters ol
        LEFT JOIN offers o ON o.id = ol.offer_id
        LEFT JOIN candidates c ON c.id = o.candidate_id
        WHERE ol.offer_id = :oid
        ORDER BY ol.id DESC LIMIT 1
    ");
    $stmt->execute([':oid' => $offerId]);
    $letter = $stmt->fetch();

    if (!$letter) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'No letter found for this offer.']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => [
        'id'             => (int)$letter['id'],
        'offer_id'       => (int)$letter['offer_id'],
        'letter_content' => $letter['letter_content'],
        'letter_format'  => $letter['letter_format'],
        'status'         => $letter['status'],
        'generated_at'   => $letter['generated_at'],
        'sent_at'        => $letter['sent_at'],
        'sent_to_email'  => $letter['sent_to_email'],
        'candidate_name' => $letter['first_name'] . ' ' . $letter['last_name'],
        'candidate_email'=> $letter['email'] ?? '',
    ]]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
