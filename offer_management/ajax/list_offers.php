<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $status = trim($_GET['status'] ?? 'all');
    $responseStatus = trim($_GET['response_status'] ?? '');
    $letterStatus = trim($_GET['letter_status'] ?? '');
    $withLetter = (int)($_GET['with_letter'] ?? 0);

    $where = [];
    $params = [];

    if ($status !== 'all') {
        $where[] = "o.status = :status";
        $params[':status'] = $status;
    }

    if ($responseStatus === 'Pending') {
        $where[] = "(o.response_status = 'Pending' OR o.response_status IS NULL)";
    } elseif ($responseStatus === 'all') {
        // include all response statuses
    } elseif ($responseStatus !== '') {
        $statuses = array_filter(array_map('trim', explode(',', $responseStatus)));
        if (!empty($statuses)) {
            $ph = [];
            foreach ($statuses as $i => $sv) {
                $key = ':rs' . $i;
                $ph[] = $key;
                $params[$key] = $sv;
            }
            $where[] = "o.response_status IN (" . implode(',', $ph) . ")";
        }
    }

    if ($withLetter) {
        $where[] = "o.status = 'Approved'";
    }

    if ($letterStatus !== '') {
        $where[] = "ol.status = :letter_status";
        $params[':letter_status'] = $letterStatus;
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("
        SELECT o.*,
               c.first_name, c.last_name, c.email,
               r.title AS job_title,
               d.name AS dept_name,
               des.name AS designation_name,
               ol.id AS letter_id, ol.status AS letter_status, ol.generated_at AS letter_generated_at, ol.sent_at AS letter_sent_at
        FROM offers o
        LEFT JOIN candidates c ON c.id = o.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        LEFT JOIN offer_letters ol ON ol.offer_id = o.id AND ol.id = (SELECT MAX(ol2.id) FROM offer_letters ol2 WHERE ol2.offer_id = o.id)
        $whereClause
        ORDER BY o.sent_at DESC
    ");
    $stmt->execute($params);

    $offers = [];
    while ($r = $stmt->fetch()) {
        $offers[] = [
            'id'                 => (int)$r['id'],
            'candidate_id'       => (int)$r['candidate_id'],
            'candidate_name'     => $r['first_name'] . ' ' . $r['last_name'],
            'email'              => $r['email'] ?? '',
            'job_title'          => $r['job_title'] ?? 'N/A',
            'salary'             => (float)$r['salary'],
            'joining_date'       => $r['joining_date'],
            'designation_name'   => $r['designation_name'] ?? 'N/A',
            'dept_name'          => $r['dept_name'] ?? 'N/A',
            'probation_months'   => (int)$r['probation_months'],
            'notes'              => $r['notes'] ?? '',
            'status'             => $r['status'],
            'response_status'    => $r['response_status'] ?? 'Pending',
            'responded_at'       => $r['responded_at'],
            'response_notes'     => $r['response_notes'] ?? '',
            'sent_at'            => $r['sent_at'],
            'letter_id'          => $r['letter_id'] ? (int)$r['letter_id'] : null,
            'letter_status'      => $r['letter_status'] ?? null,
            'letter_generated_at'=> $r['letter_generated_at'],
            'letter_sent_at'     => $r['letter_sent_at'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $offers]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
