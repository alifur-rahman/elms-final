<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $pdo->exec("CREATE TABLE IF NOT EXISTS confirmation_letters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL,
        offer_id INT DEFAULT NULL,
        appraisal_id INT DEFAULT NULL,
        letter_content LONGTEXT DEFAULT NULL,
        letter_format VARCHAR(10) DEFAULT 'html',
        employment_type VARCHAR(50) DEFAULT 'Probationer',
        status VARCHAR(20) NOT NULL DEFAULT 'Draft',
        generated_at DATETIME DEFAULT NULL,
        approved_at DATETIME DEFAULT NULL,
        approved_by VARCHAR(100) DEFAULT NULL,
        sent_at DATETIME DEFAULT NULL,
        sent_to_email VARCHAR(100) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    echo json_encode(['success' => true, 'message' => 'Confirmation tables migrated successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
