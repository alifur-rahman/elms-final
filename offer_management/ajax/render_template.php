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

    $offerId = (int)($body['offer_id'] ?? 0);
    $content = trim($body['content'] ?? '');

    if ($offerId <= 0 || empty($content)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id and content are required']);
        exit;
    }

    // Load offer with all joins
    $stmt = $pdo->prepare("
        SELECT o.*,
               c.first_name, c.last_name, c.email,
               r.title AS job_title,
               d.name AS dept_name,
               des.name AS designation_name
        FROM offers o
        LEFT JOIN candidates c ON c.id = o.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        WHERE o.id = :id
    ");
    $stmt->execute([':id' => $offerId]);
    $offer = $stmt->fetch();

    if (!$offer) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Offer not found']);
        exit;
    }

    // Date helpers
    $now = new DateTime();
    $respDate = (clone $now)->modify('+14 days');

    // Build tag -> value map
    $tagMap = [
        'CANDIDATE_NAME'   => trim(($offer['first_name'] ?? '') . ' ' . ($offer['last_name'] ?? '')),
        'DESIGNATION'      => $offer['designation_name'] ?? 'N/A',
        'JOB_TITLE'        => $offer['job_title'] ?? 'N/A',
        'DEPARTMENT'       => $offer['dept_name'] ?? 'N/A',
        'SALARY'           => number_format((float)($offer['salary'] ?? 0), 2) . ' BDT',
        'JOINING_DATE'     => $offer['joining_date'] ? (new DateTime($offer['joining_date']))->format('F j, Y') : 'N/A',
        'PROBATION_MONTHS' => (string)($offer['probation_months'] ?? 6),
        'COMPANY_NAME'     => 'TechCorp International',
        'HR_NAME'          => 'HR Department',
        'DATE'             => $now->format('F j, Y'),
        'RESPONSE_DATE'    => $respDate->format('F j, Y'),
    ];

    // Also load dynamic merge tags from DB for extensibility
    $mtStmt = $pdo->query("SELECT tag, source FROM merge_tags WHERE type = 'Offer'");
    $dbTags = $mtStmt->fetchAll();

    // Apply replacements - iterate over all pattern matches
    $rendered = preg_replace_callback('/\{\{([A-Z_][A-Z0-9_]*)\}\}/', function ($m) use ($tagMap, $offer) {
        $tag = $m[1];
        if (isset($tagMap[$tag])) {
            return $tagMap[$tag];
        }
        // Tag not in map, leave as-is (raw)
        return $m[0];
    }, $content);

    echo json_encode([
        'success' => true,
        'data' => [
            'rendered_content' => $rendered,
            'resolved_tags'    => $tagMap,
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
