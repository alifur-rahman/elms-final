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

    $appraisalId = (int)($body['appraisal_id'] ?? 0);
    $action      = trim($body['action'] ?? '');
    $comments    = trim($body['comments'] ?? '');

    if ($appraisalId <= 0 || !in_array($action, ['approve', 'sendback', 'reject'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Valid appraisal_id and action required.']);
        exit;
    }

    $pdo->beginTransaction();

    // Get current pending workflow entry
    $wfStmt = $pdo->prepare("SELECT id, level, status FROM probation_workflow WHERE appraisal_id = :aid AND status = 'Pending' ORDER BY FIELD(level, 'Self Assessment','Supervisor','Dept Head','HR Review','GM','Managing Director') ASC LIMIT 1");
    $wfStmt->execute([':aid' => $appraisalId]);
    $wf = $wfStmt->fetch();

    if (!$wf) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No pending workflow step found.']);
        exit;
    }

    $wfId = (int)$wf['id'];
    $currentLevel = $wf['level'];

    // Get employee_id
    $appStmt = $pdo->prepare("SELECT employee_id FROM probation_appraisals WHERE id = :id");
    $appStmt->execute([':id' => $appraisalId]);
    $empId = (int)$appStmt->fetchColumn();

    // Resolve candidate_id from personnel_basic_info
    $candStmt = $pdo->prepare("SELECT candidate_id FROM personnel_basic_info WHERE PBI_ID = :eid");
    $candStmt->execute([':eid' => $empId]);
    $candId = (int)$candStmt->fetchColumn();

    require_once __DIR__ . '/../../includes/functions.php';

    if ($action === 'approve') {
        // Mark current level as approved
        $updStmt = $pdo->prepare("UPDATE probation_workflow SET status = 'Approved', approved_by = 'Admin User', comments = :comments, action_at = NOW() WHERE id = :id");
        $updStmt->execute([':comments' => $comments ?: null, ':id' => $wfId]);

        // Check if this was the last level
        $levelOrder = ['Self Assessment', 'Supervisor', 'Dept Head', 'HR Review', 'GM', 'Managing Director'];
        $lastLevel = end($levelOrder);

        if ($currentLevel === $lastLevel) {
            // All levels approved → mark as Completed
            $pdo->prepare("UPDATE probation_appraisals SET status = 'Completed' WHERE id = :id")->execute([':id' => $appraisalId]);
            if ($candId > 0) addCandidateLog($pdo, $candId, 'probation_completed', null, null, 'Probation appraisal #' . $appraisalId . ' fully approved.');
        } else {
            // Move to In Progress
            $pdo->prepare("UPDATE probation_appraisals SET status = 'In Progress' WHERE id = :id")->execute([':id' => $appraisalId]);
            if ($candId > 0) addCandidateLog($pdo, $candId, 'probation_approved', null, null, 'Probation appraisal #' . $appraisalId . ' approved at ' . $currentLevel . ' level.');
        }

        echo json_encode(['success' => true, 'message' => 'Approved at ' . $currentLevel . ' level.']);
    } elseif ($action === 'sendback') {
        // Mark as Sent Back
        $updStmt = $pdo->prepare("UPDATE probation_workflow SET status = 'Sent Back', approved_by = 'Admin User', comments = :comments, action_at = NOW() WHERE id = :id");
        $updStmt->execute([':comments' => $comments ?: null, ':id' => $wfId]);

        $pdo->prepare("UPDATE probation_appraisals SET status = 'Draft' WHERE id = :id")->execute([':id' => $appraisalId]);
        if ($candId > 0) addCandidateLog($pdo, $candId, 'probation_sentback', null, null, 'Probation appraisal #' . $appraisalId . ' sent back at ' . $currentLevel . ' level.');

        echo json_encode(['success' => true, 'message' => 'Sent back to previous level.']);
    } elseif ($action === 'reject') {
        // Mark as Rejected
        $updStmt = $pdo->prepare("UPDATE probation_workflow SET status = 'Rejected', approved_by = 'Admin User', comments = :comments, action_at = NOW() WHERE id = :id");
        $updStmt->execute([':comments' => $comments ?: null, ':id' => $wfId]);

        $pdo->prepare("UPDATE probation_appraisals SET status = 'Rejected' WHERE id = :id")->execute([':id' => $appraisalId]);
        if ($candId > 0) addCandidateLog($pdo, $candId, 'probation_rejected', null, null, 'Probation appraisal #' . $appraisalId . ' rejected at ' . $currentLevel . ' level.');

        echo json_encode(['success' => true, 'message' => 'Rejected.']);
    }

    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
