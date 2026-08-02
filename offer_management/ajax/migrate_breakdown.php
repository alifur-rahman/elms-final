<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $cols = $pdo->query("SHOW COLUMNS FROM elms_salary_proposals LIKE 'salary_breakdown'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE elms_salary_proposals ADD COLUMN salary_breakdown TEXT DEFAULT NULL AFTER negotiation_notes");
        echo json_encode(['success' => true, 'message' => 'salary_breakdown column added.']);
    } else {
        echo json_encode(['success' => true, 'message' => 'salary_breakdown column already exists.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
