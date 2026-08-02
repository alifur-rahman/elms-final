<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $pdo->exec("CREATE TABLE IF NOT EXISTS merge_tags (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL DEFAULT 'Offer',
        tag VARCHAR(100) NOT NULL,
        label VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uq_tag_type (tag, type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $cnt = $pdo->query("SELECT COUNT(*) FROM elms_merge_tags")->fetchColumn();
    if ((int)$cnt === 0) {
        $defaultTags = [
            ['Offer', 'CANDIDATE_NAME', 'Candidate Name', 'Full name of the candidate', 1],
            ['Offer', 'DESIGNATION', 'Designation', 'Offered designation/title', 2],
            ['Offer', 'JOB_TITLE', 'Job Title', 'Title of the position', 3],
            ['Offer', 'DEPARTMENT', 'Department', 'Department name', 4],
            ['Offer', 'SALARY', 'Salary', 'Offered salary amount', 5],
            ['Offer', 'JOINING_DATE', 'Joining Date', 'Expected date of joining', 6],
            ['Offer', 'PROBATION_MONTHS', 'Probation Months', 'Probation period in months', 7],
            ['Offer', 'COMPANY_NAME', 'Company Name', 'Name of the organization', 8],
            ['Offer', 'HR_NAME', 'HR Name', 'HR representative name', 9],
            ['Offer', 'DATE', 'Current Date', 'Today\'s date', 10],
            ['Offer', 'RESPONSE_DATE', 'Response Deadline', 'Offer response deadline date', 11],
            ['Offer', 'SALARY_BREAKDOWN', 'Salary Breakdown Table', 'Full salary breakdown rendered as an HTML table', 12],
            ['Offer', 'RESPONSE_LINK', 'Response Link', 'Unique link for the candidate to accept or decline the offer', 13],
            ['Confirmation', 'EMPLOYEE_NAME', 'Employee Name', 'Full name of the employee', 1],
            ['Confirmation', 'DESIGNATION', 'Designation', 'Current designation', 2],
            ['Confirmation', 'DEPARTMENT', 'Department', 'Department name', 3],
            ['Confirmation', 'JOINING_DATE', 'Joining Date', 'Date of joining the company', 4],
            ['Confirmation', 'CONFIRM_DATE', 'Confirmation Date', 'Date of confirmation', 5],
            ['Confirmation', 'COMPANY_NAME', 'Company Name', 'Name of the organization', 6],
            ['Confirmation', 'HR_NAME', 'HR Name', 'HR representative name', 7],
            ['Confirmation', 'DATE', 'Current Date', 'Today\'s date', 8],
            ['Probation', 'EMPLOYEE_NAME', 'Employee Name', 'Full name of the employee', 1],
            ['Probation', 'DESIGNATION', 'Designation', 'Current designation', 2],
            ['Probation', 'DEPARTMENT', 'Department', 'Department name', 3],
            ['Probation', 'REVIEW_DATE', 'Review Date', 'Probation review date', 4],
            ['Probation', 'SCORE', 'Overall Score', 'Probation overall score', 5],
            ['Probation', 'RECOMMENDATION', 'Recommendation', 'Probation recommendation', 6],
            ['Probation', 'COMPANY_NAME', 'Company Name', 'Name of the organization', 7],
            ['Probation', 'HR_NAME', 'HR Name', 'HR representative name', 8],
            ['Probation', 'DATE', 'Current Date', 'Today\'s date', 9],
            ['General', 'EMPLOYEE_NAME', 'Employee Name', 'Full name of the employee', 1],
            ['General', 'DESIGNATION', 'Designation', 'Current designation', 2],
            ['General', 'DEPARTMENT', 'Department', 'Department name', 3],
            ['General', 'COMPANY_NAME', 'Company Name', 'Name of the organization', 4],
            ['General', 'HR_NAME', 'HR Name', 'HR representative name', 5],
            ['General', 'DATE', 'Current Date', 'Today\'s date', 6],
        ];
        $stmt = $pdo->prepare("INSERT INTO elms_merge_tags (type, tag, label, description, sort_order) VALUES (?, ?, ?, ?, ?)");
        foreach ($defaultTags as $t) {
            $stmt->execute($t);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Merge tags table migrated successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
