<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    // ========================================================================
    // 1. Create salary_proposals table
    // ========================================================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS salary_proposals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        candidate_id INT NOT NULL,
        proposed_salary DECIMAL(12,2) NOT NULL,
        offered_salary DECIMAL(12,2) DEFAULT NULL,
        negotiation_notes TEXT DEFAULT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Draft',
        version INT NOT NULL DEFAULT 1,
        created_by VARCHAR(100) DEFAULT 'Admin User',
        finalized_at DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // 2. Create offer_letters table
    // ========================================================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS offer_letters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        offer_id INT NOT NULL,
        letter_content LONGTEXT DEFAULT NULL,
        letter_format VARCHAR(10) DEFAULT 'html',
        generated_at DATETIME DEFAULT NULL,
        sent_at DATETIME DEFAULT NULL,
        sent_to_email VARCHAR(100) DEFAULT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (offer_id) REFERENCES offers(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // 3. Create offer_email_log table
    // ========================================================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS offer_email_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        offer_id INT NOT NULL,
        recipient VARCHAR(100) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        body LONGTEXT DEFAULT NULL,
        sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(20) NOT NULL DEFAULT 'Sent',
        error_message TEXT DEFAULT NULL,
        FOREIGN KEY (offer_id) REFERENCES offers(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // 4. Add response fields to offers table
    // ========================================================================
    $cols = $pdo->query("SHOW COLUMNS FROM offers LIKE 'response_status'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE offers
            ADD COLUMN response_status VARCHAR(20) DEFAULT 'Pending' AFTER status,
            ADD COLUMN responded_at DATETIME DEFAULT NULL AFTER response_status,
            ADD COLUMN response_notes TEXT DEFAULT NULL AFTER responded_at");
    }

    echo json_encode(['success' => true, 'message' => 'Offer Management tables migrated successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
