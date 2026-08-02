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

    $proposalId    = (int)($body['proposal_id'] ?? 0);
    $action        = trim($body['action'] ?? '');
    $comments      = trim($body['comments'] ?? '');
    $joiningDate   = trim($body['joining_date'] ?? '');
    $designationId = (int)($body['designation_id'] ?? 0);
    $probation     = (int)($body['probation_months'] ?? 6);
    $notes         = trim($body['notes'] ?? '');

    if ($proposalId <= 0 || !in_array($action, ['Approved', 'Rejected'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'proposal_id and action (Approved/Rejected) are required']);
        exit;
    }

    $check = $pdo->prepare("SELECT sp.*, c.job_id, c.first_name, c.last_name, c.status_id, r.title AS job_title, r.dept_id 
                            FROM elms_salary_proposals sp
                            JOIN elms_candidates c ON c.id = sp.candidate_id
                            LEFT JOIN elms_requisitions r ON r.id = c.job_id
                            WHERE sp.id = :id");
    $check->execute([':id' => $proposalId]);
    $prop = $check->fetch();

    if (!$prop) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Proposal not found']);
        exit;
    }

    if ($prop['status'] !== 'Pending_Approval' && $prop['status'] !== 'Proposed') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Proposal is not pending approval']);
        exit;
    }

    $candidateId = (int)$prop['candidate_id'];

    $pdo->beginTransaction();

    if ($action === 'Rejected') {
        $uStmt = $pdo->prepare("UPDATE elms_salary_proposals SET status = 'Rejected' WHERE id = :id");
        $uStmt->execute([':id' => $proposalId]);
        addCandidateLog($pdo, $candidateId, 'proposal_rejected', null, null, 'Proposal #' . $proposalId . ' rejected: ' . ($comments ?: 'No comments'));
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Proposal rejected.']);
        exit;
    }

    // --- APPROVED ---
    $finalSalary = $prop['offered_salary'] ?: $prop['proposed_salary'];

    // Update proposal to Finalized
    $uStmt = $pdo->prepare("UPDATE elms_salary_proposals SET status = 'Finalized', offered_salary = :sal, finalized_at = NOW() WHERE id = :id");
    $uStmt->execute([':sal' => $finalSalary, ':id' => $proposalId]);

    // Create offer with status 'Approved' (skip multi-level approval)
    $deptId = (int)($prop['dept_id'] ?: $body['dept_id'] ?? 0);

    $iStmt = $pdo->prepare("INSERT INTO elms_offers (candidate_id, salary, joining_date, designation_id, dept_id, probation_months, notes, status, sent_at) 
                            VALUES (:cid, :sal, :jdate, :did, :dept, :prob, :notes, 'Approved', NOW())");
    $iStmt->execute([
        ':cid'  => $candidateId,
        ':sal'  => $finalSalary,
        ':jdate'=> $joiningDate ?: date('Y-m-d', strtotime('+30 days')),
        ':did'  => $designationId ?: null,
        ':dept' => $deptId ?: null,
        ':prob' => $probation,
        ':notes'=> $notes,
    ]);
    $offerId = $pdo->lastInsertId();

    // Update candidate status to Offered (5) if currently Selected (4)
    if ((int)$prop['status_id'] === 4) {
        $uStmt = $pdo->prepare("UPDATE elms_candidates SET status_id = 5 WHERE id = :id");
        $uStmt->execute([':id' => $candidateId]);
    }

    addCandidateLog($pdo, $candidateId, 'proposal_approved', null, null, 'Proposal #' . $proposalId . ' approved. Offer #' . $offerId . ' auto-created with salary ' . $finalSalary);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Proposal approved. Offer created and candidate moved to Offer Letter stage.', 'offer_id' => $offerId]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
