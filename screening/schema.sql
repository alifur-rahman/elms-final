-- ============================================================
-- ERP HRIS - Screening Module Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS elms
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE elms;

-- -----------------------------------------------------------
-- Screenings
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS screenings (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    candidate_id    INT NOT NULL,
    screened_by     VARCHAR(255) DEFAULT NULL,
    date            DATE DEFAULT NULL,
    rating          DECIMAL(2,1) DEFAULT NULL,
    experience_match VARCHAR(20) DEFAULT NULL,
    skill_match     VARCHAR(20) DEFAULT NULL,
    education_match VARCHAR(20) DEFAULT NULL,
    communication_score DECIMAL(2,1) DEFAULT NULL,
    remarks         TEXT DEFAULT NULL,
    result          VARCHAR(20) DEFAULT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_candidate_id (candidate_id),
    INDEX idx_result (result),

    CONSTRAINT fk_screening_candidate FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
