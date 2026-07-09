<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    // ========================================================================
    // Drop everything and recreate from scratch
    // ========================================================================
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    $tables = ['offer_approvals', 'offers', 'interviews', 'screenings', 'candidate_logs', 'candidates', 'designations', 'candidate_statuses'];
    foreach ($tables as $t) {
        $pdo->exec("DROP TABLE IF EXISTS $t");
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // ========================================================================
    // candidate_statuses (no FK)
    // ========================================================================
    $pdo->exec("CREATE TABLE candidate_statuses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(30) NOT NULL UNIQUE,
        name VARCHAR(50) NOT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        description TEXT DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("INSERT INTO candidate_statuses (code, name, sort_order, description) VALUES
        ('Applied', 'Applied', 1, 'Initial application submitted'),
        ('Shortlisted', 'Shortlisted', 2, 'Shortlisted after screening'),
        ('Interview', 'Interview', 3, 'Interview in progress'),
        ('Selected', 'Selected', 4, 'Passed all interviews'),
        ('Offered', 'Offered', 5, 'Offer sent to candidate'),
        ('Joined', 'Joined', 6, 'Candidate joined the organization'),
        ('Rejected', 'Rejected', 99, 'Candidate rejected'),
        ('Hold', 'Hold', 98, 'Candidate on hold')
    ");

    // ========================================================================
    // designations (FK → departments)
    // ========================================================================
    $pdo->exec("CREATE TABLE designations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        code VARCHAR(20) DEFAULT NULL,
        dept_id INT NOT NULL,
        FOREIGN KEY (dept_id) REFERENCES departments(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("INSERT INTO designations (name, code, dept_id) VALUES
        ('Software Engineer', 'SE', 1), ('Senior Developer', 'SD', 1),
        ('Team Lead', 'TL', 1), ('Marketing Specialist', 'MS', 2),
        ('Financial Analyst', 'FA', 3), ('HR Executive', 'HRE', 4),
        ('UX Designer', 'UXD', 5), ('Operations Manager', 'OM', 6),
        ('Sales Representative', 'SR', 7), ('IT Support', 'ITS', 8)
    ");

    // ========================================================================
    // candidates (FK → candidate_statuses)
    // ========================================================================
    $pdo->exec("CREATE TABLE candidates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT NOT NULL,
        code VARCHAR(30) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(30) DEFAULT NULL,
        nid VARCHAR(50) DEFAULT NULL,
        blood_group VARCHAR(10) DEFAULT NULL,
        dob DATE DEFAULT NULL,
        gender VARCHAR(10) DEFAULT NULL,
        present_address TEXT DEFAULT NULL,
        permanent_address TEXT DEFAULT NULL,
        current_salary DECIMAL(12,2) DEFAULT 0,
        expected_salary DECIMAL(12,2) DEFAULT 0,
        status_id INT NOT NULL DEFAULT 1,
        applied_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (job_id) REFERENCES requisitions(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (status_id) REFERENCES candidate_statuses(id) ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // candidate_logs (FK → candidates)
    // ========================================================================
    $pdo->exec("CREATE TABLE candidate_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        candidate_id INT NOT NULL,
        action VARCHAR(50) NOT NULL,
        from_status VARCHAR(30) DEFAULT NULL,
        to_status VARCHAR(30) DEFAULT NULL,
        notes TEXT DEFAULT NULL,
        created_by VARCHAR(100) DEFAULT 'Admin User',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // screenings (FK → candidates)
    // ========================================================================
    $pdo->exec("CREATE TABLE screenings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        candidate_id INT NOT NULL,
        screened_by VARCHAR(100) DEFAULT 'Admin User',
        date DATE DEFAULT NULL,
        rating DECIMAL(3,1) DEFAULT NULL,
        communication_score DECIMAL(3,1) DEFAULT NULL,
        experience_match VARCHAR(20) DEFAULT NULL,
        skill_match VARCHAR(20) DEFAULT NULL,
        education_match VARCHAR(20) DEFAULT NULL,
        remarks TEXT DEFAULT NULL,
        result VARCHAR(30) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // interviews (FK → candidates)
    // ========================================================================
    $pdo->exec("CREATE TABLE interviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        candidate_id INT NOT NULL,
        date DATETIME NOT NULL,
        type VARCHAR(50) DEFAULT NULL,
        interviewer VARCHAR(100) DEFAULT NULL,
        round INT DEFAULT 1,
        round_name VARCHAR(100) DEFAULT NULL,
        rating DECIMAL(3,1) DEFAULT NULL,
        feedback TEXT DEFAULT NULL,
        decision VARCHAR(30) DEFAULT NULL,
        status VARCHAR(30) DEFAULT 'Scheduled',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // offers (FK → candidates, designations, departments)
    // ========================================================================
    $pdo->exec("CREATE TABLE offers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        candidate_id INT NOT NULL,
        salary DECIMAL(12,2) NOT NULL,
        joining_date DATE NOT NULL,
        designation_id INT DEFAULT NULL,
        dept_id INT DEFAULT NULL,
        probation_months INT DEFAULT 6,
        notes TEXT DEFAULT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Pending',
        sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        accepted_at DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (designation_id) REFERENCES designations(id) ON DELETE SET NULL ON UPDATE CASCADE,
        FOREIGN KEY (dept_id) REFERENCES departments(id) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ========================================================================
    // offer_approvals (FK → offers)
    // ========================================================================
    $pdo->exec("CREATE TABLE offer_approvals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        offer_id INT NOT NULL,
        level INT NOT NULL DEFAULT 1,
        approver VARCHAR(100) DEFAULT 'Admin User',
        status VARCHAR(20) NOT NULL DEFAULT 'Pending',
        comment TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (offer_id) REFERENCES offers(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    echo json_encode(['success' => true, 'message' => 'All tables recreated from scratch with status_id FK.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
