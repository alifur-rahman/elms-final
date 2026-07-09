-- ============================================================
-- ERP HRIS - Candidates Module Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS elms
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE elms;

-- -----------------------------------------------------------
-- Candidates (Job Applicants)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS candidates (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    job_id          INT NOT NULL,
    code            VARCHAR(50) DEFAULT NULL,
    first_name      VARCHAR(255) NOT NULL,
    last_name       VARCHAR(255) NOT NULL,
    email           VARCHAR(255) NOT NULL,
    phone           VARCHAR(50) DEFAULT NULL,
    nid             VARCHAR(100) DEFAULT NULL,
    blood_group     VARCHAR(10) DEFAULT NULL,
    dob             DATE DEFAULT NULL,
    gender          VARCHAR(20) DEFAULT NULL,
    present_address TEXT DEFAULT NULL,
    permanent_address TEXT DEFAULT NULL,
    current_salary  DECIMAL(12,2) DEFAULT 0,
    expected_salary DECIMAL(12,2) DEFAULT 0,
    status          VARCHAR(50) NOT NULL DEFAULT 'Applied',
    applied_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_job_id (job_id),
    INDEX idx_status (status),
    CONSTRAINT fk_candidate_job FOREIGN KEY (job_id) REFERENCES requisitions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
