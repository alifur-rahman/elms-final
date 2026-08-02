<?php
function getProbationTagMap(PDO $pdo, int $recordId): array {
    $stmt = $pdo->prepare("
        SELECT pa.*,
               e.first_name, e.last_name, e.joining_date,
               d.name AS dept_name, des.name AS designation_name
        FROM elms_probation_appraisals pa
        LEFT JOIN employees e ON e.id = pa.employee_id
        LEFT JOIN elms_departments d ON d.id = e.dept_id
        LEFT JOIN elms_designations des ON des.id = e.designation_id
        WHERE pa.id = :id
    ");
    $stmt->execute([':id' => $recordId]);
    $appraisal = $stmt->fetch();
    if (!$appraisal) return [];

    $now = new DateTime();

    return [
        'EMPLOYEE_NAME'  => trim(($appraisal['first_name'] ?? '') . ' ' . ($appraisal['last_name'] ?? '')),
        'DESIGNATION'    => $appraisal['designation_name'] ?? 'N/A',
        'DEPARTMENT'     => $appraisal['dept_name'] ?? 'N/A',
        'REVIEW_DATE'    => !empty($appraisal['review_date']) ? (new DateTime($appraisal['review_date']))->format('F j, Y') : 'N/A',
        'SCORE'          => (string)($appraisal['overall_score'] ?? 'N/A'),
        'RECOMMENDATION' => $appraisal['recommendation'] ?? 'N/A',
        'COMPANY_NAME'   => 'TechCorp International',
        'HR_NAME'        => 'HR Department',
        'DATE'           => $now->format('F j, Y'),
    ];
}
