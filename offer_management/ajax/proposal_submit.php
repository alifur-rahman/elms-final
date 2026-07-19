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

    $proposalId  = (int)($body['proposal_id'] ?? 0);
    $candidateId = (int)($body['candidate_id'] ?? 0);

    if ($proposalId <= 0 && $candidateId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'proposal_id or candidate_id is required']);
        exit;
    }

    // If only candidate_id given, find the latest proposal
    if ($proposalId <= 0) {
        $pStmt = $pdo->prepare("SELECT id FROM salary_proposals WHERE candidate_id = :cid ORDER BY version DESC LIMIT 1");
        $pStmt->execute([':cid' => $candidateId]);
        $proposalId = (int)$pStmt->fetchColumn();
        if ($proposalId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No proposal found for this candidate']);
            exit;
        }
    }

    $check = $pdo->prepare("SELECT id, status, candidate_id FROM salary_proposals WHERE id = :id");
    $check->execute([':id' => $proposalId]);
    $prop = $check->fetch();

    if (!$prop) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Proposal not found']);
        exit;
    }

    if ($prop['status'] !== 'Proposed') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Only proposals with status Proposed can be submitted for approval']);
        exit;
    }

    $uStmt = $pdo->prepare("UPDATE salary_proposals SET status = 'Pending_Approval' WHERE id = :id");
    $uStmt->execute([':id' => $proposalId]);

    addCandidateLog($pdo, (int)$prop['candidate_id'], 'proposal_submitted_approval', null, null, 'Proposal #' . $proposalId . ' submitted for approval');

    echo json_encode(['success' => true, 'message' => 'Proposal submitted for approval.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
