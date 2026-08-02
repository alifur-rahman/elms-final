<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $candidateId = isset($_GET['candidate_id']) ? (int) $_GET['candidate_id'] : 0;
    if ($candidateId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'candidate_id is required']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT cl.*, cs.name AS status_name
        FROM elms_candidate_logs cl
        LEFT JOIN elms_candidate_statuses cs ON cs.code = cl.to_status
        WHERE cl.candidate_id = :candidate_id
        ORDER BY cl.created_at ASC
    ");
    $stmt->execute([':candidate_id' => $candidateId]);

    $rows = $stmt->fetchAll();

    $logs = [];
    foreach ($rows as $r) {
        $logs[] = [
            'id'           => (int) $r['id'],
            'candidate_id' => (int) $r['candidate_id'],
            'action'       => $r['action'],
            'from_status'  => $r['from_status'],
            'to_status'    => $r['to_status'],
            'notes'        => $r['notes'] ?? '',
            'created_by'   => $r['created_by'],
            'created_at'   => $r['created_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $logs]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
