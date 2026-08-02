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

    $candidateId  = (int)($body['candidate_id'] ?? 0);
    $offeredSalary = (float)($body['offered_salary'] ?? 0);

    if ($candidateId <= 0 || $offeredSalary <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'candidate_id and offered_salary are required']);
        exit;
    }

    // Get the latest proposal for this candidate and finalize it
    $pStmt = $pdo->prepare("SELECT id FROM elms_salary_proposals WHERE candidate_id = :cid ORDER BY version DESC LIMIT 1");
    $pStmt->execute([':cid' => $candidateId]);
    $prop = $pStmt->fetch();

    if ($prop) {
        $uStmt = $pdo->prepare("UPDATE elms_salary_proposals SET status = 'Finalized', offered_salary = :sal, finalized_at = NOW() WHERE id = :id");
        $uStmt->execute([':sal' => $offeredSalary, ':id' => $prop['id']]);
    } else {
        // No prior proposal, create one as finalized
        $iStmt = $pdo->prepare("INSERT INTO elms_salary_proposals (candidate_id, proposed_salary, offered_salary, status, version, finalized_at) VALUES (:cid, :sal, :sal, 'Finalized', 1, NOW())");
        $iStmt->execute([':cid' => $candidateId, ':sal' => $offeredSalary]);
    }

    addCandidateLog($pdo, $candidateId, 'salary_finalized', null, null, 'Salary finalized: ' . $offeredSalary);

    echo json_encode(['success' => true, 'message' => 'Proposal finalized. Candidate is ready for offer.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
