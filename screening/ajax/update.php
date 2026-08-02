<?php
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST' && $method !== 'PUT') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) $body = $_POST;

    $id = (int) ($body['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    $check = $pdo->prepare("SELECT id, candidate_id FROM elms_screenings WHERE id = :id");
    $check->execute([':id' => $id]);
    $existing = $check->fetch();
    if (!$existing) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Screening not found']);
        exit;
    }

    $candidate_id      = (int) ($body['candidate_id'] ?? $existing['candidate_id']);
    $rating            = $body['rating'] ?? null;
    $communication     = $body['communication_score'] ?? null;
    $experience_match  = trim($body['experience_match'] ?? '');
    $skill_match       = trim($body['skill_match'] ?? '');
    $education_match   = trim($body['education_match'] ?? '');
    $result            = trim($body['result'] ?? '');
    $remarks           = trim($body['remarks'] ?? '');
    $screened_by       = trim($body['screened_by'] ?? 'Admin User');
    $date              = trim($body['date'] ?? date('Y-m-d'));

    $errors = [];
    if ($rating === null || $rating === '') $errors[] = 'Rating is required';
    if ($communication === null || $communication === '') $errors[] = 'Communication score is required';
    if ($experience_match === '') $errors[] = 'Experience match is required';
    if ($skill_match === '') $errors[] = 'Skill match is required';
    if ($education_match === '') $errors[] = 'Education match is required';
    if ($result === '') $errors[] = 'Result is required';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => implode('. ', $errors)]);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        UPDATE elms_screenings SET
            candidate_id = :candidate_id, screened_by = :screened_by, date = :date,
            rating = :rating, communication_score = :communication,
            experience_match = :experience_match, skill_match = :skill_match,
            education_match = :education_match, remarks = :remarks, result = :result
        WHERE id = :id
    ");

    $stmt->execute([
        ':id'                => $id,
        ':candidate_id'      => $candidate_id,
        ':screened_by'       => $screened_by,
        ':date'              => $date,
        ':rating'            => (float) $rating,
        ':communication'     => (float) $communication,
        ':experience_match'  => $experience_match,
        ':skill_match'       => $skill_match,
        ':education_match'   => $education_match,
        ':remarks'           => $remarks ?: null,
        ':result'            => $result,
    ]);

    $targetStatusId = match ($result) {
        'Shortlist' => 2,
        'Reject'    => 7,
        'Hold'      => 8,
        default     => 1,
    };
    $targetStatusName = match ($result) {
        'Shortlist' => 'Shortlisted',
        'Reject'    => 'Rejected',
        'Hold'      => 'Hold',
        default     => 'Applied',
    };

    $cs = $pdo->prepare("SELECT cs.code FROM elms_candidates cc LEFT JOIN elms_candidate_statuses cs ON cs.id = cc.status_id WHERE cc.id = :id");
    $cs->execute([':id' => $candidate_id]);
    $currentStatus = $cs->fetchColumn() ?: 'Applied';

    $upd = $pdo->prepare("UPDATE elms_candidates SET status_id = :status_id WHERE id = :id");
    $upd->execute([':status_id' => $targetStatusId, ':id' => $candidate_id]);

    addCandidateLog($pdo, $candidate_id, 'screening_' . strtolower($result) . '_update', $currentStatus, $targetStatusName, $remarks ?: null);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Screening updated successfully!',
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
