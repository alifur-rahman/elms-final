<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $pdo->exec("CREATE TABLE IF NOT EXISTS template_modules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        module_name VARCHAR(50) NOT NULL UNIQUE,
        label VARCHAR(100) NOT NULL,
        render_file VARCHAR(255) NOT NULL DEFAULT '',
        description TEXT DEFAULT NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $cnt = $pdo->query("SELECT COUNT(*) FROM elms_template_modules")->fetchColumn();
    if ((int)$cnt === 0) {
        $stmt = $pdo->prepare("INSERT INTO elms_template_modules (module_name, label, render_file, description) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Offer',        'Offer / Recruitment', 'templates/render/offer.php',        'Merge tags for offer letters and recruitment']);
        $stmt->execute(['Confirmation', 'Confirmation',        'templates/render/confirmation.php', 'Merge tags for confirmation letters']);
        $stmt->execute(['Probation',    'Probation',           'templates/render/probation.php',    'Merge tags for probation appraisal letters']);
        $stmt->execute(['General',      'General Letter',      'templates/render/general.php',      'General purpose merge tags']);
        echo json_encode(['success' => true, 'message' => 'Template modules table migrated and seeded.']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Template modules already exist.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
