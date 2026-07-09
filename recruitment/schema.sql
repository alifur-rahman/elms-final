-- ============================================================
-- ERP HRIS - Recruitment Module Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS elms
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE elms;

-- -----------------------------------------------------------
-- Departments
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO departments (id, name, code) VALUES
    (1, 'Engineering',       'ENG'),
    (2, 'Marketing',         'MKT'),
    (3, 'Finance',           'FIN'),
    (4, 'Human Resources',   'HR'),
    (5, 'Design',            'DES'),
    (6, 'Operations',        'OPS'),
    (7, 'Sales',             'SAL'),
    (8, 'IT',                'IT')
ON DUPLICATE KEY UPDATE name = VALUES(name), code = VALUES(code);

-- -----------------------------------------------------------
-- Requisitions (Job Openings)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS requisitions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(255) NOT NULL,
    dept_id         INT NOT NULL,
    location        VARCHAR(255) DEFAULT NULL,
    type            VARCHAR(100) NOT NULL DEFAULT 'Full Time',
    vacancies       INT NOT NULL DEFAULT 1,
    deadline        DATE DEFAULT NULL,
    education       VARCHAR(100) DEFAULT NULL,
    experience      DECIMAL(4,1) DEFAULT 0,
    salary_min      DECIMAL(12,2) DEFAULT 0,
    salary_max      DECIMAL(12,2) DEFAULT 0,
    skills          TEXT DEFAULT NULL,
    description     TEXT DEFAULT NULL,
    responsibilities TEXT DEFAULT NULL,
    benefits        TEXT DEFAULT NULL,
    priority        VARCHAR(20) NOT NULL DEFAULT 'Medium',
    status          VARCHAR(20) NOT NULL DEFAULT 'Active',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_dept_id (dept_id),
    INDEX idx_status (status),
    INDEX idx_priority (priority)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
