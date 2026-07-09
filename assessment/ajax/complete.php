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

    $id = (int) ($body['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    $check = $pdo->prepare("
        SELECT c.id, c.first_name, c.last_name, cs.code AS status_code
        FROM candidates c
        LEFT JOIN candidate_statuses cs ON cs.id = c.status_id
        WHERE c.id = :id
    ");
    $check->execute([':id' => $id]);
    $candidate = $check->fetch();

    if (!$candidate) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Candidate not found']);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("UPDATE candidates SET status_id = 5 WHERE id = :id");
    $stmt->execute([':id' => $id]);

    addCandidateLog($pdo, $id, 'assessment_completed', $candidate['status_code'], 'Offered', 'Assessment completed');

    $pdo->commit();

    $name = $candidate['first_name'] . ' ' . $candidate['last_name'];

    echo json_encode([
        'success' => true,
        'message' => 'Assessment completed. "' . htmlspecialchars($name) . '" moved to Offered stage.',
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
