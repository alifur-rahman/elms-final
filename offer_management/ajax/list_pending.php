<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    // Fetch pending offers with candidate/job/designation info
    $stmt = $pdo->query("
        SELECT o.*,
               c.first_name, c.last_name, c.job_id,
               r.title AS job_title,
               d.name AS dept_name,
               des.name AS designation_name
        FROM elms_offers o
        LEFT JOIN elms_candidates c ON c.id = o.candidate_id
        LEFT JOIN elms_requisitions r ON r.id = c.job_id
        LEFT JOIN elms_departments d ON d.id = o.dept_id
        LEFT JOIN elms_designations des ON des.id = o.designation_id
        WHERE o.status = 'Pending'
        ORDER BY o.sent_at DESC
    ");

    $offers = [];
    while ($r = $stmt->fetch()) {
        // Fetch approvals for this offer
        $aStmt = $pdo->prepare("SELECT id, offer_id, level, approver, status, comment FROM elms_offer_approvals WHERE offer_id = :oid ORDER BY level");
        $aStmt->execute([':oid' => $r['id']]);
        $approvals = [];
        while ($a = $aStmt->fetch()) {
            $approvals[] = [
                'id'       => (int)$a['id'],
                'offer_id' => (int)$a['offer_id'],
                'level'    => (int)$a['level'],
                'approver' => $a['approver'],
                'status'   => $a['status'],
                'comment'  => $a['comment'],
            ];
        }

        $offers[] = [
            'id'               => (int)$r['id'],
            'candidate_id'     => (int)$r['candidate_id'],
            'candidate_name'   => $r['first_name'] . ' ' . $r['last_name'],
            'job_title'        => $r['job_title'] ?? 'N/A',
            'salary'           => (float)$r['salary'],
            'joining_date'     => $r['joining_date'],
            'designation_name' => $r['designation_name'] ?? 'N/A',
            'dept_name'        => $r['dept_name'] ?? 'N/A',
            'probation_months' => (int)$r['probation_months'],
            'notes'            => $r['notes'] ?? '',
            'status'           => $r['status'],
            'sent_at'          => $r['sent_at'],
            'approvals'        => $approvals,
        ];
    }

    echo json_encode(['success' => true, 'data' => $offers]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
