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

    $check = $pdo->prepare("SELECT id, candidate_id FROM interviews WHERE id = :id");
    $check->execute([':id' => $id]);
    $existing = $check->fetch();
    if (!$existing) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Interview not found']);
        exit;
    }

    // Fields for feedback update
    $rating      = $body['rating'] ?? null;
    $decision    = trim($body['decision'] ?? '');
    $feedback    = trim($body['feedback'] ?? '');
    $status      = trim($body['status'] ?? 'Completed');

    // Fields for reschedule (optional)
    $date        = array_key_exists('date', $body) ? trim($body['date']) : null;
    $type        = array_key_exists('type', $body) ? trim($body['type']) : null;
    $interviewer = array_key_exists('interviewer', $body) ? trim($body['interviewer']) : null;
    $round       = array_key_exists('round', $body) ? (int) $body['round'] : null;
    $round_name  = array_key_exists('round_name', $body) ? trim($body['round_name']) : null;

    $pdo->beginTransaction();

    $updates = [];
    $params = [':id' => $id];

    if ($rating !== null) { $updates[] = 'rating = :rating'; $params[':rating'] = (float) $rating; }
    if ($decision !== '') { $updates[] = 'decision = :decision'; $params[':decision'] = $decision; }
    if ($feedback !== '') { $updates[] = 'feedback = :feedback'; $params[':feedback'] = $feedback; }
    if ($status !== '') { $updates[] = 'status = :status'; $params[':status'] = $status; }
    if ($date !== null) { $updates[] = 'date = :date'; $params[':date'] = str_replace('T', ' ', $date); }
    if ($type !== null) { $updates[] = 'type = :type'; $params[':type'] = $type; }
    if ($interviewer !== null) { $updates[] = 'interviewer = :interviewer'; $params[':interviewer'] = $interviewer; }
    if ($round !== null) { $updates[] = 'round = :round'; $params[':round'] = $round; }
    if ($round_name !== null) { $updates[] = 'round_name = :round_name'; $params[':round_name'] = $round_name ?: ('Round ' . ($round ?? 1)); }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }

    $sql = "UPDATE interviews SET " . implode(', ', $updates) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Update candidate status based on decision
    if ($status === 'Completed' && $decision !== '') {
        $candidateStatus = match ($decision) {
            'Pass' => 'Shortlisted',
            'Fail' => 'Rejected',
            default => 'Applied',
        };
        $upd = $pdo->prepare("UPDATE candidates SET status = :status WHERE id = :id");
        $upd->execute([':status' => $candidateStatus, ':id' => $existing['candidate_id']]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Interview updated successfully!',
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
