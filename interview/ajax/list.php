<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT
            i.*,
            c.first_name, c.last_name, c.job_id, c.status AS candidate_status,
            r.title AS job_title, d.name AS dept_name
        FROM interviews i
        LEFT JOIN candidates c ON c.id = i.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = r.dept_id
        ORDER BY i.date DESC
    ");

    $rows = $stmt->fetchAll();

    $interviews = [];
    foreach ($rows as $r) {
        $interviews[] = [
            'id'               => (int) $r['id'],
            'candidate_id'     => (int) $r['candidate_id'],
            'date'             => $r['date'],
            'type'             => $r['type'],
            'interviewer'      => $r['interviewer'],
            'round'            => (int) $r['round'],
            'round_name'       => $r['round_name'] ?? '',
            'rating'           => $r['rating'] ? (float) $r['rating'] : null,
            'feedback'         => $r['feedback'] ?? '',
            'decision'         => $r['decision'] ?? '',
            'status'           => $r['status'],

            'first_name'       => $r['first_name'],
            'last_name'        => $r['last_name'],
            'job_title'        => $r['job_title'] ?? 'N/A',
            'dept_name'        => $r['dept_name'] ?? 'N/A',
            'candidate_status' => $r['candidate_status'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $interviews]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
