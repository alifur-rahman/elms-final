<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $candidate_id = (int) ($_GET['candidate_id'] ?? 0);
    if ($candidate_id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid candidate ID']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT i.*, c.first_name, c.last_name, r.title AS job_title
        FROM interview_log i
        LEFT JOIN candidates c ON c.id = i.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        WHERE i.candidate_id = :candidate_id
        ORDER BY i.round ASC
    ");
    $stmt->execute([':candidate_id' => $candidate_id]);
    $rows = $stmt->fetchAll();

    $interviews = [];
    foreach ($rows as $r) {
        $interviews[] = [
            'id'           => (int) $r['id'],
            'candidate_id' => (int) $r['candidate_id'],
            'date'         => $r['date'],
            'type'         => $r['type'],
            'interviewer'  => $r['interviewer'],
            'round'        => (int) $r['round'],
            'round_name'   => $r['round_name'] ?? '',
            'rating'       => $r['rating'] ? (float) $r['rating'] : null,
            'feedback'     => $r['feedback'] ?? '',
            'decision'     => $r['decision'] ?? '',
            'status'       => $r['status'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $interviews]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
