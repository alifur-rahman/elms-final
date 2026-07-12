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

    $check = $pdo->prepare("SELECT id, candidate_id FROM interview_log WHERE id = :id");
    $check->execute([':id' => $id]);
    $existing = $check->fetch();
    if (!$existing) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Interview not found']);
        exit;
    }

    $candidate_id = (int) $existing['candidate_id'];

    $score_technical       = $body['score_technical'] ?? null;
    $score_communication   = $body['score_communication'] ?? null;
    $score_problem_solving = $body['score_problem_solving'] ?? null;
    $score_attitude        = $body['score_attitude'] ?? null;
    $decision              = trim($body['decision'] ?? '');
    $reject_reason         = trim($body['reject_reason'] ?? '');
    $feedback              = trim($body['feedback'] ?? '');
    $status                = trim($body['status'] ?? '');
    $is_final_round_val    = array_key_exists('is_final_round', $body) ? (!empty($body['is_final_round']) ? 1 : 0) : null;

    // Reschedule / Edit fields
    $date        = array_key_exists('date', $body) ? trim($body['date']) : null;
    $type        = array_key_exists('type', $body) ? trim($body['type']) : null;
    $interviewer = array_key_exists('interviewer', $body) ? trim($body['interviewer']) : null;
    $round_name  = array_key_exists('round_name', $body) ? trim($body['round_name']) : null;

    // Calculate overall rating from category scores
    $scores = array_filter([$score_technical, $score_communication, $score_problem_solving, $score_attitude], function($v) {
        return $v !== null && $v !== '';
    });
    $overall_rating = null;
    if (count($scores) > 0) {
        $overall_rating = round(array_sum($scores) / count($scores), 1);
    }

    $pdo->beginTransaction();

    $updates = [];
    $params = [':id' => $id];

    if ($score_technical !== null && $score_technical !== '') { $updates[] = 'score_technical = :st'; $params[':st'] = (float) $score_technical; }
    if ($score_communication !== null && $score_communication !== '') { $updates[] = 'score_communication = :sc'; $params[':sc'] = (float) $score_communication; }
    if ($score_problem_solving !== null && $score_problem_solving !== '') { $updates[] = 'score_problem_solving = :sps'; $params[':sps'] = (float) $score_problem_solving; }
    if ($score_attitude !== null && $score_attitude !== '') { $updates[] = 'score_attitude = :sa'; $params[':sa'] = (float) $score_attitude; }
    if ($overall_rating !== null) { $updates[] = 'rating = :rating'; $params[':rating'] = $overall_rating; }
    if ($decision !== '') { $updates[] = 'decision = :decision'; $params[':decision'] = $decision; }
    if ($reject_reason !== '') { $updates[] = 'reject_reason = :reject_reason'; $params[':reject_reason'] = $reject_reason; }
    if ($feedback !== '') { $updates[] = 'feedback = :feedback'; $params[':feedback'] = $feedback; }
    if ($status !== '') { $updates[] = 'status = :status'; $params[':status'] = $status; }
    if ($is_final_round_val !== null) { $updates[] = 'is_final_round = :ifr'; $params[':ifr'] = $is_final_round_val; }

    // Date / time / interviewer / round_name updates (for Edit)
    if ($date !== null) { $updates[] = 'date = :date'; $params[':date'] = str_replace('T', ' ', $date); }
    if ($type !== null) { $updates[] = 'type = :type'; $params[':type'] = $type; }
    if ($interviewer !== null) { $updates[] = 'interviewer = :interviewer'; $params[':interviewer'] = $interviewer; }
    if ($round_name !== null) { $updates[] = 'round_name = :round_name'; $params[':round_name'] = $round_name; }

    if (empty($updates)) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }

    $sql = "UPDATE interview_log SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Update candidate status based on decision + is_final_round
    if ($status === 'Completed' && $decision !== '') {
        $getIfr = $pdo->prepare("SELECT is_final_round FROM interview_log WHERE id = :id");
        $getIfr->execute([':id' => $id]);
        $finalRound = (bool) $getIfr->fetchColumn();

        $targetStatusId = null;
        $targetStatusName = null;
        $logAction = null;

        if ($decision === 'Pass') {
            if ($finalRound) {
                $targetStatusId = 4; // Selected
                $targetStatusName = 'Selected';
                $logAction = 'interview_pass';
            } else {
                $targetStatusId = 3; // Interview
                $targetStatusName = 'Interview';
                $logAction = 'interview_round_pass';
            }
        } elseif ($decision === 'Fail') {
            $targetStatusId = 7; // Rejected
            $targetStatusName = 'Rejected';
            $logAction = 'interview_fail';
        } elseif ($decision === 'Hold') {
            $targetStatusId = 8; // Hold
            $targetStatusName = 'Hold';
            $logAction = 'interview_hold';
        }

        if ($targetStatusId !== null) {
            $cs = $pdo->prepare("SELECT cs.code FROM candidates cc LEFT JOIN candidate_statuses cs ON cs.id = cc.status_id WHERE cc.id = :id");
            $cs->execute([':id' => $candidate_id]);
            $currentStatus = $cs->fetchColumn() ?: 'Interview';

            $upd = $pdo->prepare("UPDATE candidates SET status_id = :status_id WHERE id = :id");
            $upd->execute([':status_id' => $targetStatusId, ':id' => $candidate_id]);

            $logNotes = $feedback ?: null;
            if ($decision === 'Fail' && $reject_reason) {
                $logNotes = ($logNotes ? $logNotes . ' | ' : '') . 'Reason: ' . $reject_reason;
            }
            addCandidateLog($pdo, $candidate_id, $logAction, $currentStatus, $targetStatusName, $logNotes);
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Interview updated successfully!',
    ]);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
