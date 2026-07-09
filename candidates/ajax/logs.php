<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $candidateId = isset($_GET['candidate_id']) ? (int)$_GET['candidate_id'] : 0;

    if ($candidateId > 0) {
        $stmt = $pdo->prepare("
            SELECT id, candidate_id, action, from_status, to_status, notes, created_by, created_at
            FROM candidate_logs
            WHERE candidate_id = :cid
            ORDER BY created_at DESC
        ");
        $stmt->execute([':cid' => $candidateId]);
    } else {
        $stmt = $pdo->query("
            SELECT cl.*, CONCAT(c.first_name, ' ', c.last_name) AS candidate_name
            FROM candidate_logs cl
            LEFT JOIN candidates c ON c.id = cl.candidate_id
            ORDER BY cl.created_at DESC
            LIMIT 100
        ");
    }

    $rows = $stmt->fetchAll();
    $logs = [];
    foreach ($rows as $r) {
        $logs[] = [
            'id'            => (int)$r['id'],
            'candidate_id'  => (int)$r['candidate_id'],
            'candidate_name'=> $r['candidate_name'] ?? null,
            'action'        => $r['action'],
            'from_status'   => $r['from_status'],
            'to_status'     => $r['to_status'],
            'notes'         => $r['notes'],
            'created_by'    => $r['created_by'],
            'created_at'    => $r['created_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $logs]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
