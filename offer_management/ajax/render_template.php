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

    // Build salary breakdown HTML
    $salaryBreakdownHtml = '';
    $sbStmt = $pdo->prepare("SELECT salary_breakdown FROM salary_proposals WHERE candidate_id = :cid AND salary_breakdown IS NOT NULL ORDER BY version DESC LIMIT 1");
    $sbStmt->execute([':cid' => $offer['candidate_id']]);
    $sbRow = $sbStmt->fetch();
    if ($sbRow && $sbRow['salary_breakdown']) {
        $bd = json_decode($sbRow['salary_breakdown'], true);
        if (is_array($bd) && !empty($bd['gross_salary'])) {
            $fmt = function($v) { return $v ? number_format((float)$v, 2) : '0.00'; };
            $salaryBreakdownHtml = '
<table style="width:100%;border-collapse:collapse;margin:10px 0;font-size:13px">
<thead>
<tr style="background:#f5f5f5">
<th style="border:1px solid #ddd;padding:6px 10px;text-align:left">Component</th>
<th style="border:1px solid #ddd;padding:6px 10px;text-align:right">Amount (BDT)</th>
</tr>
</thead>
<tbody>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Gross Salary</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($bd['gross_salary'] ?? 0) . '</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Basic</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($bd['basic'] ?? 0) . '</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">House Rent</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($bd['house_rent'] ?? 0) . '</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Medical Allowance</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($bd['medical_allowance'] ?? 0) . '</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Conveyance Allowance</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($bd['conveyance_allowance'] ?? 0) . '</td></tr>
<tr style="font-weight:600;background:#f9f9f9"><td style="border:1px solid #ddd;padding:6px 10px">Total Salary</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($bd['total_salary'] ?? 0) . '</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Personal PF</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">(' . $fmt($bd['personal_pf'] ?? 0) . ')</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">PF Deductible</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">(' . $fmt($bd['pf_deductible'] ?? 0) . ')</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Monthly Income Tax</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">(' . $fmt($bd['monthly_income_tax'] ?? 0) . ')</td></tr>
<tr style="font-weight:600;background:#f0f7ff"><td style="border:1px solid #ddd;padding:6px 10px">Total Payable</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($bd['total_payable'] ?? 0) . '</td></tr>
</tbody>
</table>';
        }
    }

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
        'RESPONSE_LINK'    => getOfferResponseLink($pdo, $offerId),
        'SALARY_BREAKDOWN' => $salaryBreakdownHtml,
    ];

    // Apply replacements - iterate over all pattern matches
    $rendered = preg_replace_callback('/\{\{([A-Z_][A-Z0-9_]*)\}\}/', function ($m) use ($tagMap) {
        $tag = $m[1];
        if (isset($tagMap[$tag])) {
            return $tagMap[$tag];
        }
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
