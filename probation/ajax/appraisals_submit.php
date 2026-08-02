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

    $id = (int)($body['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'id is required.']);
        exit;
    }

    // Check appraisal exists and is Draft
    $chk = $pdo->prepare("SELECT status, employee_id FROM elms_probation_appraisals WHERE id = :id");
    $chk->execute([':id' => $id]);
    $app = $chk->fetch();
    if (!$app) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Appraisal not found.']);
        exit;
    }

    $pdo->beginTransaction();

    // Update status to Pending (submitted for workflow)
    $stmt = $pdo->prepare("UPDATE elms_probation_appraisals SET status = 'Pending' WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Mark first workflow level (Self Assessment) as completed
    $wfStmt = $pdo->prepare("UPDATE elms_probation_workflow SET status = 'Approved', approved_by = 'Admin User', action_at = NOW() WHERE appraisal_id = :aid AND level = 'Self Assessment'");
    $wfStmt->execute([':aid' => $id]);

    // Log lifecycle (resolve candidate_id from elms_personnel_basic_info)
    $candStmt = $pdo->prepare("SELECT candidate_id FROM elms_personnel_basic_info WHERE PBI_ID = :eid");
    $candStmt->execute([':eid' => $app['employee_id']]);
    $candId = (int)$candStmt->fetchColumn();
    if ($candId > 0) {
        require_once __DIR__ . '/../../includes/functions.php';
        addCandidateLog($pdo, $candId, 'probation_submitted', null, null, 'Probation appraisal #' . $id . ' submitted for approval.');
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Appraisal submitted for approval.']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
