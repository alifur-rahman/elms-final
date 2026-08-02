<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    // ========================================================================
    // 1. Create probation_appraisals table
    // ========================================================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS probation_appraisals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL,
        joining_date DATE DEFAULT NULL,
        probation_months INT NOT NULL DEFAULT 6,
        end_date DATE DEFAULT NULL,
        review_date DATE DEFAULT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Draft',
        overall_score DECIMAL(5,2) DEFAULT NULL,
        recommendation VARCHAR(30) DEFAULT NULL,
        remarks TEXT DEFAULT NULL,
        supervisor_comments TEXT DEFAULT NULL,
        dept_head_comments TEXT DEFAULT NULL,
        hr_review TEXT DEFAULT NULL,
        created_by VARCHAR(100) DEFAULT 'Admin User',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // 2. Create probation_categories table
    // ========================================================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS probation_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        weight DECIMAL(5,2) NOT NULL DEFAULT 0,
        sort_order INT NOT NULL DEFAULT 0,
        status VARCHAR(20) NOT NULL DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // 3. Create probation_scores table
    // ========================================================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS probation_scores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appraisal_id INT NOT NULL,
        category_id INT NOT NULL,
        score DECIMAL(5,2) NOT NULL DEFAULT 0,
        comments TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (appraisal_id) REFERENCES elms_probation_appraisals(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (category_id) REFERENCES elms_probation_categories(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // 4. Create probation_workflow table
    // ========================================================================
    $pdo->exec("CREATE TABLE IF NOT EXISTS probation_workflow (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appraisal_id INT NOT NULL,
        level VARCHAR(50) NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Pending',
        approved_by VARCHAR(100) DEFAULT NULL,
        comments TEXT DEFAULT NULL,
        action_at DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (appraisal_id) REFERENCES elms_probation_appraisals(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // 5. Seed default categories (if empty)
    // ========================================================================
    $cnt = $pdo->query("SELECT COUNT(*) FROM elms_probation_categories")->fetchColumn();
    if ((int)$cnt === 0) {
        $categories = [
            ['Attendance', 15, 1],
            ['Discipline', 10, 2],
            ['Job Knowledge', 15, 3],
            ['Productivity', 15, 4],
            ['Quality of Work', 15, 5],
            ['Communication', 10, 6],
            ['Leadership', 5, 7],
            ['Initiative', 5, 8],
            ['Behavior', 5, 9],
            ['Teamwork', 5, 10],
        ];
        $stmt = $pdo->prepare("INSERT INTO elms_probation_categories (name, weight, sort_order, status) VALUES (?, ?, ?, 'Active')");
        foreach ($categories as $c) {
            $stmt->execute($c);
        }
        // Verify sum is 100
        $totalWeight = $pdo->query("SELECT SUM(weight) FROM elms_probation_categories")->fetchColumn();
        if ((float)$totalWeight !== 100.0) {
            // Adjust last category to make sum exactly 100
            $diff = 100.0 - (float)$totalWeight;
            $lastId = $pdo->lastInsertId();
            $pdo->prepare("UPDATE elms_probation_categories SET weight = weight + ? WHERE id = ?")->execute([$diff, $lastId]);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Probation tables migrated successfully with seed data.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
