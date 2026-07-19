<?php
function getOfferTagMap(PDO $pdo, int $recordId): array {
    $stmt = $pdo->prepare("
        SELECT o.*,
               c.first_name, c.last_name, c.email,
               r.title AS job_title,
               d.name AS dept_name,
               des.name AS designation_name
        FROM offers o
        LEFT JOIN candidates c ON c.id = o.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        WHERE o.id = :id
    ");
    $stmt->execute([':id' => $recordId]);
    $offer = $stmt->fetch();
    if (!$offer) return [];

    $now = new DateTime();
    $respDate = (clone $now)->modify('+14 days');

    return [
        'CANDIDATE_NAME'   => trim(($offer['first_name'] ?? '') . ' ' . ($offer['last_name'] ?? '')),
        'DESIGNATION'      => $offer['designation_name'] ?? 'N/A',
        'JOB_TITLE'        => $offer['job_title'] ?? 'N/A',
        'DEPARTMENT'       => $offer['dept_name'] ?? 'N/A',
        'SALARY'           => number_format((float)($offer['salary'] ?? 0), 2) . ' BDT',
        'JOINING_DATE'     => $offer['joining_date'] ? (new DateTime($offer['joining_date']))->format('F j, Y') : 'N/A',
        'PROBATION_MONTHS' => (string)($offer['probation_months'] ?? 6),
        'COMPANY_NAME'     => 'TechCorp International',
        'HR_NAME'          => 'HR Department',
        'DATE'             => $now->format('F j, Y'),
        'RESPONSE_DATE'    => $respDate->format('F j, Y'),
    ];
}
