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

    $offerId    = (int)($body['offer_id'] ?? 0);
    $approvalId = (int)($body['approval_id'] ?? 0);
    $action     = trim($body['action'] ?? 'Approved');
    $comment    = trim($body['comment'] ?? '');

    if ($offerId <= 0 || $approvalId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id and approval_id are required']);
        exit;
    }

    if (!in_array($action, ['Approved', 'Rejected'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Action must be Approved or Rejected']);
        exit;
    }

    // Verify the approval record exists and is pending
    $check = $pdo->prepare("SELECT id, status FROM offer_approvals WHERE id = :id AND offer_id = :oid");
    $check->execute([':id' => $approvalId, ':oid' => $offerId]);
    $approval = $check->fetch();

    if (!$approval) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Approval record not found']);
        exit;
    }

    if ($approval['status'] !== 'Pending') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Approval already ' . $approval['status']]);
        exit;
    }

    $pdo->beginTransaction();

    // Update approval record
    $uStmt = $pdo->prepare("UPDATE offer_approvals SET status = :status, comment = :comment WHERE id = :id");
    $uStmt->execute([':status' => $action, ':comment' => $comment, ':id' => $approvalId]);

    if ($action === 'Rejected') {
        // Reject the entire offer
        $oStmt = $pdo->prepare("UPDATE offers SET status = 'Rejected' WHERE id = :id");
        $oStmt->execute([':id' => $offerId]);
    } else {
        // Approved: check if all levels for this offer are now approved
        $pendingCount = $pdo->prepare("SELECT COUNT(*) FROM offer_approvals WHERE offer_id = :oid AND status = 'Pending'");
        $pendingCount->execute([':oid' => $offerId]);
        $remaining = $pendingCount->fetchColumn();

        if ($remaining == 0) {
            $oStmt = $pdo->prepare("UPDATE offers SET status = 'Approved' WHERE id = :id");
            $oStmt->execute([':id' => $offerId]);
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Offer ' . strtolower($action) . ' successfully.',
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
