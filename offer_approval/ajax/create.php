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

    $candidateId = (int)($body['candidate_id'] ?? 0);
    $salary      = (float)($body['salary'] ?? 0);
    $joiningDate = trim($body['joining_date'] ?? '');
    $designationId = (int)($body['designation_id'] ?? 0);
    $probation   = (int)($body['probation_months'] ?? 6);
    $notes       = trim($body['notes'] ?? '');

    if ($candidateId <= 0 || $salary <= 0 || !$joiningDate || $designationId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'candidate_id, salary, joining_date, and designation_id are required']);
        exit;
    }

    $cStmt = $pdo->prepare("
        SELECT c.*, r.dept_id, cs.code AS status_code
        FROM candidates c
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN candidate_statuses cs ON cs.id = c.status_id
        WHERE c.id = :id
    ");
    $cStmt->execute([':id' => $candidateId]);
    $candidate = $cStmt->fetch();

    if (!$candidate) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Candidate not found']);
        exit;
    }

    $deptId = (int)($candidate['dept_id'] ?? 0);

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO offers (candidate_id, salary, joining_date, designation_id, dept_id, probation_months, notes, status, sent_at) VALUES (:candidate_id, :salary, :joining_date, :designation_id, :dept_id, :probation, :notes, 'Pending', NOW())");
    $stmt->execute([
        ':candidate_id'    => $candidateId,
        ':salary'          => $salary,
        ':joining_date'    => $joiningDate,
        ':designation_id'  => $designationId,
        ':dept_id'         => $deptId,
        ':probation'       => $probation,
        ':notes'           => $notes,
    ]);
    $offerId = $pdo->lastInsertId();

    $aStmt = $pdo->prepare("INSERT INTO offer_approvals (offer_id, level, approver, status) VALUES (:offer_id, 1, 'Admin User', 'Pending')");
    $aStmt->execute([':offer_id' => $offerId]);

    // Update candidate status to Offered (status_id = 5) if currently Selected (status_id = 4)
    if ((int)$candidate['status_id'] === 4) {
        $uStmt = $pdo->prepare("UPDATE candidates SET status_id = 5 WHERE id = :id");
        $uStmt->execute([':id' => $candidateId]);
        addCandidateLog($pdo, $candidateId, 'offer_created', $candidate['status_code'], 'Offered', 'Offer created with salary ' . $salary);
    }

    $pdo->commit();

    $name = $candidate['first_name'] . ' ' . $candidate['last_name'];
    echo json_encode(['success' => true, 'message' => 'Offer created for "' . htmlspecialchars($name) . '" and sent for approval.', 'offer_id' => $offerId]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
