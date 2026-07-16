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

    $employeeId     = (int)($body['employee_id'] ?? 0);
    $joiningDate    = trim($body['joining_date'] ?? '');
    $probationMonths = (int)($body['probation_months'] ?? 6);
    $reviewDate     = trim($body['review_date'] ?? '');

    if ($employeeId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Employee is required.']);
        exit;
    }

    // Prevent duplicate appraisal for same employee
    $dupCheck = $pdo->prepare("SELECT COUNT(*) FROM probation_appraisals WHERE employee_id = :eid");
    $dupCheck->execute([':eid' => $employeeId]);
    if ((int)$dupCheck->fetchColumn() > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'This employee already has a probation appraisal. Only one appraisal per employee is allowed.']);
        exit;
    }

    // Calculate end_date
    $endDate = null;
    if ($joiningDate) {
        $dt = new DateTime($joiningDate);
        $dt->modify('+' . $probationMonths . ' months');
        $endDate = $dt->format('Y-m-d');
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO probation_appraisals (employee_id, joining_date, probation_months, end_date, review_date, status, created_by) VALUES (:emp, :join, :months, :end, :review, 'Draft', 'Admin User')");
    $stmt->execute([
        ':emp'    => $employeeId,
        ':join'   => $joiningDate ?: null,
        ':months' => $probationMonths,
        ':end'    => $endDate,
        ':review' => $reviewDate ?: null,
    ]);
    $appraisalId = (int)$pdo->lastInsertId();

    // Create empty workflow entries
    $levels = ['Self Assessment', 'Supervisor', 'Dept Head', 'HR Review', 'GM', 'Managing Director'];
    $wfStmt = $pdo->prepare("INSERT INTO probation_workflow (appraisal_id, level, status) VALUES (:aid, :level, 'Pending')");
    foreach ($levels as $level) {
        $wfStmt->execute([':aid' => $appraisalId, ':level' => $level]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Appraisal created.', 'id' => $appraisalId]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
