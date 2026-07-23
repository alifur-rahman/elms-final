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

    $id              = (int)($body['id'] ?? 0);
    $status          = trim($body['status'] ?? '');
    $overallScore    = isset($body['overall_score']) ? (float)$body['overall_score'] : null;
    $recommendation  = trim($body['recommendation'] ?? '');
    $remarks         = trim($body['remarks'] ?? '');
    $supervisorCmt   = trim($body['supervisor_comments'] ?? '');
    $deptHeadCmt     = trim($body['dept_head_comments'] ?? '');
    $hrReview        = trim($body['hr_review'] ?? '');
    $scores          = $body['scores'] ?? [];

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'id is required.']);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("UPDATE probation_appraisals SET overall_score = :score, recommendation = :rec, remarks = :remarks, supervisor_comments = :sup, dept_head_comments = :dept, hr_review = :hr" . ($status ? ", status = :status" : "") . " WHERE id = :id");
    $params = [
        ':score'   => $overallScore,
        ':rec'     => $recommendation ?: null,
        ':remarks' => $remarks ?: null,
        ':sup'     => $supervisorCmt ?: null,
        ':dept'    => $deptHeadCmt ?: null,
        ':hr'      => $hrReview ?: null,
        ':id'      => $id,
    ];
    if ($status) $params[':status'] = $status;
    $stmt->execute($params);

    // Save/update scores
    if (!empty($scores)) {
        $delStmt = $pdo->prepare("DELETE FROM probation_scores WHERE appraisal_id = :aid");
        $delStmt->execute([':aid' => $id]);

        $insStmt = $pdo->prepare("INSERT INTO probation_scores (appraisal_id, category_id, score, comments) VALUES (:aid, :cat, :score, :comments)");
        foreach ($scores as $s) {
            $catId = (int)($s['category_id'] ?? 0);
            $score = (float)($s['score'] ?? 0);
            $comments = trim($s['comments'] ?? '');
            if ($catId > 0) {
                $insStmt->execute([':aid' => $id, ':cat' => $catId, ':score' => $score, ':comments' => $comments ?: null]);
            }
        }
    }

    // Log lifecycle event (resolve candidate_id from personnel_basic_info)
    $appStmt = $pdo->prepare("SELECT employee_id FROM probation_appraisals WHERE id = :id");
    $appStmt->execute([':id' => $id]);
    $empId = (int)$appStmt->fetchColumn();
    if ($empId) {
        $candStmt = $pdo->prepare("SELECT candidate_id FROM personnel_basic_info WHERE PBI_ID = :eid");
        $candStmt->execute([':eid' => $empId]);
        $candId = (int)$candStmt->fetchColumn();
        if ($candId > 0) {
            require_once __DIR__ . '/../../includes/functions.php';
            addCandidateLog($pdo, $candId, 'probation_updated', null, null, 'Probation appraisal #' . $id . ' updated.');
        }
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Appraisal updated.']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
