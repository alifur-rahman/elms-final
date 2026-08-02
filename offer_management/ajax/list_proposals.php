<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    // Check if salary_breakdown column exists (graceful for pre-migration DB)
    $hasBreakdown = false;
    $cols = $pdo->query("SHOW COLUMNS FROM elms_salary_proposals LIKE 'salary_breakdown'")->fetchAll();
    if (!empty($cols)) $hasBreakdown = true;

    $breakdownSelect = $hasBreakdown ? 'sp.salary_breakdown' : 'NULL AS salary_breakdown';

    $stmt = $pdo->query("
        SELECT c.id, c.first_name, c.last_name, c.job_id, c.status_id,
               cs.name AS status_name, c.expected_salary, c.applied_at,
               r.title AS job_title, r.dept_id,
               sp.id AS proposal_id, sp.proposed_salary, sp.offered_salary,
               sp.status AS proposal_status, sp.version, sp.negotiation_notes,
               $breakdownSelect
        FROM elms_candidates c
        LEFT JOIN elms_candidate_statuses cs ON cs.id = c.status_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
        LEFT JOIN elms_salary_proposals sp ON sp.candidate_id = c.id AND sp.id = (
            SELECT MAX(sp2.id) FROM elms_salary_proposals sp2 WHERE sp2.candidate_id = c.id
        )
        WHERE c.status_id = 4
          AND c.id NOT IN (SELECT candidate_id FROM elms_offers WHERE status IN ('Pending','Approved'))
        ORDER BY c.applied_at DESC
    ");

    $rows = $stmt->fetchAll();
    $candidates = [];
    foreach ($rows as $r) {
        $breakdown = null;
        if ($r['salary_breakdown']) {
            $bd = json_decode($r['salary_breakdown'], true);
            if (is_array($bd)) $breakdown = $bd;
        }
        $candidates[] = [
            'id'                => (int)$r['id'],
            'first_name'        => $r['first_name'],
            'last_name'         => $r['last_name'],
            'job_title'         => $r['job_title'] ?? 'N/A',
            'dept_id'           => (int)$r['dept_id'],
            'status_name'       => $r['status_name'],
            'expected_salary'   => $r['expected_salary'] ? (float)$r['expected_salary'] : null,
            'applied_at'        => $r['applied_at'],
            'proposal_id'       => $r['proposal_id'] ? (int)$r['proposal_id'] : null,
            'proposed_amount'   => $r['offered_salary'] ? (float)$r['offered_salary'] : ($r['proposed_salary'] ? (float)$r['proposed_salary'] : null),
            'proposal_status'   => $r['proposal_status'] ?? 'None',
            'version'           => (int)$r['version'],
            'negotiation_notes' => $r['negotiation_notes'] ?? '',
            'salary_breakdown'  => $breakdown,
        ];
    }

    echo json_encode(['success' => true, 'data' => $candidates]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
