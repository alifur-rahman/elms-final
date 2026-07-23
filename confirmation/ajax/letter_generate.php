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

    $employeeId  = (int)($body['employee_id'] ?? 0);
    $content     = trim($body['letter_content'] ?? '');
    $empType     = trim($body['employment_type'] ?? 'Probationer');

    if ($employeeId <= 0 || empty($content)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'employee_id and letter_content are required']);
        exit;
    }

    $chk = $pdo->prepare("SELECT id FROM confirmation_letters WHERE employee_id = :eid LIMIT 1");
    $chk->execute([':eid' => $employeeId]);
    $existing = $chk->fetch();

    if ($existing) {
        $uStmt = $pdo->prepare("UPDATE confirmation_letters SET letter_content = :content, status = 'Generated', employment_type = :emp_type, generated_at = NOW() WHERE id = :id");
        $uStmt->execute([':content' => $content, ':emp_type' => $empType, ':id' => $existing['id']]);
        $letterId = $existing['id'];
    } else {
        $appraisalId = (int)($body['appraisal_id'] ?? 0);
        $offerId = (int)($body['offer_id'] ?? 0);
        $iStmt = $pdo->prepare("INSERT INTO confirmation_letters (employee_id, offer_id, appraisal_id, letter_content, letter_format, employment_type, status, generated_at) VALUES (:eid, :oid, :apid, :content, 'html', :emp_type, 'Generated', NOW())");
        $iStmt->execute([':eid' => $employeeId, ':oid' => $offerId ?: null, ':apid' => $appraisalId ?: null, ':content' => $content, ':emp_type' => $empType]);
        $letterId = $pdo->lastInsertId();
    }

    echo json_encode(['success' => true, 'message' => 'Confirmation letter generated.', 'letter_id' => $letterId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
