<?php
function getGeneralTagMap(PDO $pdo, int $recordId): array {
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = :id");
    $stmt->execute([':id' => $recordId]);
    $emp = $stmt->fetch();
    if (!$emp) return [];

    $now = new DateTime();

    return [
        'EMPLOYEE_NAME' => trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')),
        'DESIGNATION'   => $emp['designation_name'] ?? 'N/A',
        'DEPARTMENT'    => $emp['dept_name'] ?? 'N/A',
        'COMPANY_NAME'  => 'TechCorp International',
        'HR_NAME'       => 'HR Department',
        'DATE'          => $now->format('F j, Y'),
    ];
}
