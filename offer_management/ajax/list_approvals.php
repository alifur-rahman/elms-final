<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $statusFilter = trim($_GET['status'] ?? 'Pending_Approval');

    $where = "sp.status = :status";
    $params = [':status' => $statusFilter];

    if ($statusFilter === 'all') {
        $where = "sp.status IN ('Pending_Approval','Finalized','Rejected')";
        $params = [];
    }

    $stmt = $pdo->prepare("
        SELECT sp.id AS proposal_id, sp.proposed_salary, sp.offered_salary, sp.status AS proposal_status, 
               sp.version, sp.negotiation_notes, sp.created_at AS submitted_at, sp.finalized_at,
               sp.salary_breakdown,
               c.id AS candidate_id, c.first_name, c.last_name, c.expected_salary,
               r.title AS job_title, r.dept_id
        FROM elms_salary_proposals sp
        JOIN elms_candidates c ON c.id = sp.candidate_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
        WHERE $where
        ORDER BY sp.created_at DESC
    ");
    $stmt->execute($params);

    $data = [];
    while ($r = $stmt->fetch()) {
        $bd = $r['salary_breakdown'] ? json_decode($r['salary_breakdown'], true) : null;
        $data[] = [
            'proposal_id'       => (int)$r['proposal_id'],
            'candidate_id'      => (int)$r['candidate_id'],
            'candidate_name'    => $r['first_name'] . ' ' . $r['last_name'],
            'job_title'         => $r['job_title'] ?? 'N/A',
            'expected_salary'   => $r['expected_salary'] ? (float)$r['expected_salary'] : null,
            'proposed_salary'   => (float)$r['proposed_salary'],
            'offered_salary'    => $r['offered_salary'] ? (float)$r['offered_salary'] : null,
            'proposal_status'   => $r['proposal_status'],
            'version'           => (int)$r['version'],
            'negotiation_notes' => $r['negotiation_notes'] ?? '',
            'submitted_at'      => $r['submitted_at'],
            'finalized_at'      => $r['finalized_at'],
            'dept_id'           => (int)$r['dept_id'],
            'salary_breakdown'  => $bd,
        ];
    }

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
