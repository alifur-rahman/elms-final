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

    $candidateId   = (int)($body['candidate_id'] ?? 0);
    $proposedSalary = (float)($body['proposed_salary'] ?? 0);
    $notes         = trim($body['negotiation_notes'] ?? '');

    if ($candidateId <= 0 || $proposedSalary <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'candidate_id and proposed_salary are required']);
        exit;
    }

    // Salary breakdown (optional JSON)
    $breakdown = isset($body['salary_breakdown']) ? $body['salary_breakdown'] : null;
    $breakdownJson = null;
    if ($breakdown && is_array($breakdown)) {
        // Ensure numeric fields
        $numericFields = ['gross_salary','basic','house_rent','medical_allowance','conveyance_allowance','food_allowance','special_allowance','mobile_allowance','personal_pf','company_pf','pf_deductible','monthly_income_tax','cash_paid','bank_paid','total_salary','total_payable'];
        foreach ($numericFields as $f) {
            if (isset($breakdown[$f])) $breakdown[$f] = (float)$breakdown[$f];
        }
        $breakdownJson = json_encode($breakdown);
    }

    // Get current latest version
    $vStmt = $pdo->prepare("SELECT COALESCE(MAX(version), 0) + 1 AS next_ver FROM salary_proposals WHERE candidate_id = :cid");
    $vStmt->execute([':cid' => $candidateId]);
    $nextVer = (int)$vStmt->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO salary_proposals (candidate_id, proposed_salary, negotiation_notes, salary_breakdown, status, version, created_by) VALUES (:cid, :sal, :notes, :breakdown, 'Proposed', :ver, 'Admin User')");
    $stmt->execute([
        ':cid'       => $candidateId,
        ':sal'       => $proposedSalary,
        ':notes'     => $notes,
        ':breakdown' => $breakdownJson,
        ':ver'       => $nextVer,
    ]);

    $propId = $pdo->lastInsertId();

    addCandidateLog($pdo, $candidateId, 'salary_proposed', null, null, 'Salary proposed: ' . $proposedSalary . ' (v' . $nextVer . ')');

    echo json_encode(['success' => true, 'message' => 'Salary proposal created (v' . $nextVer . ').', 'proposal_id' => $propId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
