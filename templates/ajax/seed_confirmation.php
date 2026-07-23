<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    $cnt = $pdo->query("SELECT COUNT(*) FROM letter_templates WHERE type = 'Confirmation'")->fetchColumn();
    if ((int)$cnt === 0) {
        $default = <<<'HTML'
<div style="font-family:serif;line-height:1.8;max-width:700px;margin:0 auto;padding:24px">
    <div style="text-align:center;border-bottom:2px solid #000;padding-bottom:16px;margin-bottom:16px">
        <h2 style="margin:0">{{COMPANY_NAME}}</h2>
        <p style="color:#666;font-size:12px">Employee Lifecycle Management</p>
    </div>
    <p>Date: {{DATE}}</p>
    <p>Dear {{EMPLOYEE_NAME}},</p>
    <p><strong>Subject: Confirmation of Employment</strong></p>
    <p>We are pleased to inform you that, based on your performance during the probation period, your employment with {{COMPANY_NAME}} has been confirmed effective from <strong>{{CONFIRMATION_DATE}}</strong>.</p>
    <p>You are being confirmed as <strong>{{DESIGNATION}}</strong> in the <strong>{{DEPARTMENT}}</strong> department.</p>
    <table style="width:100%;border-collapse:collapse;margin:12px 0">
        <tr><td style="padding:4px 8px;font-weight:500;width:200px">Employee Code:</td><td>{{EMPLOYEE_CODE}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Designation:</td><td>{{DESIGNATION}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Department:</td><td>{{DEPARTMENT}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Joining Date:</td><td>{{JOINING_DATE}}</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Probation Period:</td><td>{{PROBATION_MONTHS}} months</td></tr>
        <tr><td style="padding:4px 8px;font-weight:500">Current Salary:</td><td>{{SALARY}}</td></tr>
    </table>
    {{SALARY_BREAKDOWN}}
    <p>We appreciate your contribution and look forward to your continued success with the organization.</p>
    <p>We wish you all the best in your future endeavors with us.</p>
    <br>
    <p>Best Regards,</p>
    <p style="margin-top:32px"><strong>{{HR_NAME}}</strong><br>Human Resources Department<br>{{COMPANY_NAME}}</p>
</div>
HTML;
        $stmt = $pdo->prepare("INSERT INTO letter_templates (name, type, content, status) VALUES (?, ?, ?, 'Active')");
        $stmt->execute(['Confirmation Letter Template', 'Confirmation', $default]);
        echo json_encode(['success' => true, 'message' => 'Confirmation template seeded.']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Confirmation template already exists.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
