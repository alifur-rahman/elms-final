<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $stmt = $pdo->query("
        SELECT c.id, c.first_name, c.last_name, c.job_id, c.status_id,
               cs.name AS status_name, r.title AS job_title
        FROM elms_candidates c
        LEFT JOIN elms_candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
        WHERE c.status_id IN (2, 3)
        ORDER BY c.first_name ASC
    ");

    $rows = $stmt->fetchAll();

    $candidates = [];
    foreach ($rows as $r) {
        $cid = (int) $r['id'];

        // Count existing interviews & get max round
        $cntStmt = $pdo->prepare("SELECT COUNT(*) AS cnt, COALESCE(MAX(`round`),0) AS mx FROM elms_interview_log WHERE candidate_id = :id");
        $cntStmt->execute([':id' => $cid]);
        $cntRow = $cntStmt->fetch();
        $interviewCount = (int) ($cntRow['cnt'] ?? 0);
        $maxRound = (int) ($cntRow['mx'] ?? 0);

        // Check if there's a pending interview waiting for feedback
        $pendStmt = $pdo->prepare("SELECT id FROM elms_interview_log WHERE candidate_id = :id AND status = 'Waiting for Feedback' LIMIT 1");
        $pendStmt->execute([':id' => $cid]);
        $scheduledId = $pendStmt->fetchColumn();
        $scheduledId = $scheduledId ? (int) $scheduledId : null;

        $candidates[] = [
            'id'                => $cid,
            'first_name'        => $r['first_name'],
            'last_name'         => $r['last_name'],
            'job_id'            => (int) $r['job_id'],
            'job_title'         => $r['job_title'] ?? 'N/A',
            'status_id'         => (int) $r['status_id'],
            'status_name'       => $r['status_name'],
            'interview_count'   => $interviewCount,
            'max_round'         => $maxRound,
            'next_round'        => $maxRound + 1,
            'scheduled_id'      => $scheduledId,
        ];
    }

    echo json_encode(['success' => true, 'data' => $candidates]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
