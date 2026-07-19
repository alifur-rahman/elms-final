<?php
function getConfirmationTagMap(PDO $pdo, int $recordId): array {
    $stmt = $pdo->prepare("
        SELECT e.*, d.name AS dept_name, des.name AS designation_name
        FROM employees e
        LEFT JOIN departments d ON d.id = e.dept_id
        LEFT JOIN designations des ON des.id = e.designation_id
        WHERE e.id = :id
    ");
    $stmt->execute([':id' => $recordId]);
    $emp = $stmt->fetch();
    if (!$emp) return [];

    $now = new DateTime();

    return [
        'EMPLOYEE_NAME'   => trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')),
        'DESIGNATION'     => $emp['designation_name'] ?? 'N/A',
        'DEPARTMENT'      => $emp['dept_name'] ?? 'N/A',
        'JOINING_DATE'    => !empty($emp['joining_date']) ? (new DateTime($emp['joining_date']))->format('F j, Y') : 'N/A',
        'CONFIRM_DATE'    => $now->format('F j, Y'),
        'COMPANY_NAME'    => 'TechCorp International',
        'HR_NAME'         => 'HR Department',
        'DATE'            => $now->format('F j, Y'),
    ];
}
