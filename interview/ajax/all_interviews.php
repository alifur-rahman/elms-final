<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $candidateId = isset($_GET['candidate_id']) ? (int) $_GET['candidate_id'] : 0;

    $sql = "
        SELECT i.*, c.first_name, c.last_name, r.title AS job_title
        FROM interview_log i
        LEFT JOIN candidates c ON c.id = i.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
    ";
    $params = [];
    if ($candidateId > 0) {
        $sql .= " WHERE i.candidate_id = :cid";
        $params[':cid'] = $candidateId;
    }
    $sql .= " ORDER BY i.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $interviews = [];
    foreach ($rows as $r) {
        $interviews[] = [
            'id'                  => (int) $r['id'],
            'candidate_id'        => (int) $r['candidate_id'],
            'date'                => $r['date'],
            'type'                => $r['type'],
            'interviewer'         => $r['interviewer'],
            'round'               => (int) $r['round'],
            'round_name'          => $r['round_name'] ?? '',
            'rating'              => $r['rating'] ? (float) $r['rating'] : null,
            'score_technical'     => $r['score_technical'] ? (float) $r['score_technical'] : null,
            'score_communication' => $r['score_communication'] ? (float) $r['score_communication'] : null,
            'score_problem_solving' => $r['score_problem_solving'] ? (float) $r['score_problem_solving'] : null,
            'score_attitude'      => $r['score_attitude'] ? (float) $r['score_attitude'] : null,
            'reject_reason'       => $r['reject_reason'] ?? '',
            'is_final_round'      => (bool) $r['is_final_round'],
            'feedback'            => $r['feedback'] ?? '',
            'decision'            => $r['decision'] ?? '',
            'status'              => $r['status'],
            'first_name'          => $r['first_name'],
            'last_name'           => $r['last_name'],
            'job_title'           => $r['job_title'] ?? 'N/A',
        ];
    }

    echo json_encode(['success' => true, 'data' => $interviews]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
