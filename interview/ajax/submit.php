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

    $candidate_id  = (int) ($body['candidate_id'] ?? 0);
    $date          = trim($body['date'] ?? '');
    $type          = trim($body['type'] ?? '');
    $interviewer   = trim($body['interviewer'] ?? '');
    $round_name    = trim($body['round_name'] ?? '');
    $status        = trim($body['status'] ?? 'Waiting for Feedback');
    $is_final_round = !empty($body['is_final_round']) ? 1 : 0;

    $errors = [];
    if ($candidate_id <= 0) $errors[] = 'Candidate is required';
    if ($date === '') $errors[] = 'Date & time is required';
    if ($type === '') $errors[] = 'Interview type is required';
    if ($interviewer === '') $errors[] = 'Interviewer is required';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => implode('. ', $errors)]);
        exit;
    }

    $pdo->beginTransaction();

    // Calculate next round based on ALL existing entries for this candidate
    $maxStmt = $pdo->prepare("SELECT COALESCE(MAX(`round`), 0) FROM interview_log WHERE candidate_id = :cid");
    $maxStmt->execute([':cid' => $candidate_id]);
    $round = (int) $maxStmt->fetchColumn() + 1;

    $stmt = $pdo->prepare("
        INSERT INTO interview_log
            (candidate_id, date, type, interviewer, round, round_name, is_final_round, status)
        VALUES
            (:candidate_id, :date, :type, :interviewer, :round, :round_name, :is_final_round, :status)
    ");

    $dt = str_replace('T', ' ', $date);

    $stmt->execute([
        ':candidate_id'  => $candidate_id,
        ':date'          => $dt,
        ':type'          => $type,
        ':interviewer'   => $interviewer,
        ':round'         => $round,
        ':round_name'    => $round_name ?: ('Round ' . $round),
        ':is_final_round' => $is_final_round,
        ':status'        => $status,
    ]);

    $insertId = $pdo->lastInsertId();

    // Update candidate status to Interview (status_id = 3)
    $upd = $pdo->prepare("UPDATE candidates SET status_id = 3 WHERE id = :id");
    $upd->execute([':id' => $candidate_id]);

    $logFromStatus = ($round > 1) ? 'Interview' : 'Shortlisted';
    addCandidateLog($pdo, $candidate_id, 'interview_scheduled', $logFromStatus, 'Interview', 'Scheduled: Round ' . $round . ' (' . $round_name . ') - ' . $type . ' with ' . $interviewer);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Interview scheduled successfully!',
        'id'      => (int) $insertId,
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
