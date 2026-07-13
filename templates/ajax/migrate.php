<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $pdo->exec("CREATE TABLE IF NOT EXISTS letter_templates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type VARCHAR(50) NOT NULL DEFAULT 'Offer',
        content LONGTEXT NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Seed default template if table is empty
    $cnt = $pdo->query("SELECT COUNT(*) FROM letter_templates")->fetchColumn();
    if ((int)$cnt === 0) {
        $default = <<<'HTML'
<div style="font-family:serif;line-height:1.8;max-width:700px;margin:0 auto;padding:24px">
    <div style="text-align:center;border-bottom:2px solid #000;padding-bottom:16px;margin-bottom:16px">
        <h2 style="margin:0">{{COMPANY_NAME}}</h2>
        <p style="color:#666;font-size:12px">Employee Lifecycle Management</p>
    </div>
    <p>Date: {{DATE}}</p>
    <p>Dear {{CANDIDATE_NAME}},</p>
    <p><strong>Sub: Offer of Employment</strong></p>
    <p>We are pleased to offer you the position of <strong>{{DESIGNATION}}</strong> with {{COMPANY_NAME}}. After reviewing your qualifications and experience, we are confident that you will be a valuable asset to our organization.</p>
    <p>Your offer details are as follows:</p>
    <table style="width:100%;border-collapse:collapse;margin:12px 0">
        <tr><td style="padding:4px 8px;font-weight:500;width:200px">Position:</td><td>{{DESIGNATION}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Department:</td><td>{{DEPARTMENT}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Offered Salary:</td><td>{{SALARY}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Joining Date:</td><td>{{JOINING_DATE}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Probation Period:</td><td>{{PROBATION_MONTHS}} months</td></tr>
    </table>
    <p>Please confirm your acceptance of this offer by {{RESPONSE_DATE}}. If you have any questions, please feel free to contact us.</p>
    <br>
    <p>Best Regards,</p>
    <p style="margin-top:32px"><strong>{{HR_NAME}}</strong><br>Human Resources Manager<br>{{COMPANY_NAME}}</p>
</div>
HTML;
        $stmt = $pdo->prepare("INSERT INTO letter_templates (name, type, content, status) VALUES (?, ?, ?, 'Active')");
        $stmt->execute(['Standard Offer Letter', 'Offer', $default]);
    }

    echo json_encode(['success' => true, 'message' => 'Templates table migrated successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
