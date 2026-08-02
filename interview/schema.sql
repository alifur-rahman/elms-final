-- ============================================================
-- ERP HRIS - Interview Module Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS elms
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE elms;

-- -----------------------------------------------------------
-- Interviews
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS elms_interviews (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    candidate_id    INT NOT NULL,
    date            DATETIME DEFAULT NULL,
    type            VARCHAR(50) DEFAULT NULL,
    interviewer     VARCHAR(255) DEFAULT NULL,
    round           INT DEFAULT 1,
    round_name      VARCHAR(255) DEFAULT NULL,
    rating          DECIMAL(2,1) DEFAULT NULL,
    feedback        TEXT DEFAULT NULL,
    decision        VARCHAR(20) DEFAULT NULL,
    status          VARCHAR(20) NOT NULL DEFAULT 'Scheduled',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_candidate_id (candidate_id),
    INDEX idx_status (status),

    CONSTRAINT fk_interview_candidate FOREIGN KEY (candidate_id) REFERENCES elms_candidates(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
