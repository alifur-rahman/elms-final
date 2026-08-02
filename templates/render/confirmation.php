<?php
function getConfirmationTagMap(PDO $pdo, int $recordId): array {
    $stmt = $pdo->prepare("
        SELECT pbi.*,
               d.name AS dept_name,
               des.name AS designation_name,
               o.salary, o.joining_date, o.probation_months,
               c.first_name AS c_first_name, c.last_name AS c_last_name, c.email AS c_email, c.phone AS c_phone
        FROM elms_personnel_basic_info pbi
        LEFT JOIN elms_departments d ON d.id = pbi.dept_id
        LEFT JOIN elms_designations des ON des.id = pbi.DESG_ID
        LEFT JOIN elms_offers o ON o.id = pbi.offer_id
        LEFT JOIN elms_candidates c ON c.id = pbi.candidate_id
        WHERE pbi.PBI_ID = :id
    ");
    $stmt->execute([':id' => $recordId]);
    $emp = $stmt->fetch();
    if (!$emp) return [];

    $now = new DateTime();
    $empName = trim(($emp['PBI_NAME'] ?? '') . ' ' . ($emp['last_name'] ?? ''));

    $joiningDate = null;
    if ($emp['joining_date']) {
        $joiningDate = new DateTime($emp['joining_date']);
    } elseif ($emp['PBI_DOJ']) {
        $joiningDate = new DateTime($emp['PBI_DOJ']);
    }

    $confirmDate = (clone $now)->modify('+1 day');
    if ($joiningDate && $emp['probation_months']) {
        $confirmDate = (clone $joiningDate)->modify('+' . ((int)$emp['probation_months']) . ' months');
    }

    $grossSalary = $emp['salary'] ?? 0;
    $salaryStmt = $pdo->prepare("SELECT gross_salary, total_payable, basic_salary FROM elms_salary_info WHERE offer_id = :oid LIMIT 1");
    $salaryStmt->execute([':oid' => $emp['offer_id'] ?? 0]);
    $salRow = $salaryStmt->fetch();
    if ($salRow) {
        if ($salRow['gross_salary']) $grossSalary = $salRow['gross_salary'];
    }

    $salaryBreakdownHtml = '';
    if ($salRow && $salRow['gross_salary']) {
        $fmt = function($v) { return $v ? number_format((float)$v, 2) : '0.00'; };
        $salaryBreakdownHtml = '
<table style="width:100%;border-collapse:collapse;margin:10px 0;font-size:13px">
<thead>
<tr style="background:#f5f5f5">
<th style="border:1px solid #ddd;padding:6px 10px;text-align:left">Component</th>
<th style="border:1px solid #ddd;padding:6px 10px;text-align:right">Amount (BDT)</th>
</tr>
</thead>
<tbody>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Gross Salary</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($salRow['gross_salary'] ?? 0) . '</td></tr>
<tr><td style="border:1px solid #ddd;padding:6px 10px">Basic Salary</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($salRow['basic_salary'] ?? 0) . '</td></tr>
<tr style="font-weight:600;background:#f0f7ff"><td style="border:1px solid #ddd;padding:6px 10px">Total Payable</td><td style="border:1px solid #ddd;padding:6px 10px;text-align:right">' . $fmt($salRow['total_payable'] ?? 0) . '</td></tr>
</tbody>
</table>';
    }

    return [
        'EMPLOYEE_NAME'      => $empName,
        'EMPLOYEE_CODE'      => $emp['PBI_CODE'] ?? 'N/A',
        'DESIGNATION'        => $emp['designation_name'] ?? $emp['PBI_DESIGNATION'] ?? 'N/A',
        'DEPARTMENT'         => $emp['dept_name'] ?? $emp['PBI_DEPARTMENT'] ?? 'N/A',
        'SALARY'             => number_format((float)$grossSalary, 2) . ' BDT',
        'JOINING_DATE'       => $joiningDate ? $joiningDate->format('F j, Y') : 'N/A',
        'CONFIRMATION_DATE'  => $confirmDate->format('F j, Y'),
        'PROBATION_MONTHS'   => (string)($emp['probation_months'] ?? 6),
        'COMPANY_NAME'       => 'TechCorp International',
        'HR_NAME'            => 'HR Department',
        'DATE'               => $now->format('F j, Y'),
        'PHONE'              => $emp['PBI_MOBILE'] ?? $emp['c_phone'] ?? 'N/A',
        'EMAIL'              => $emp['PBI_EMAIL'] ?? $emp['c_email'] ?? 'N/A',
        'GENDER'             => $emp['PBI_SEX'] ?? 'N/A',
        'SALARY_BREAKDOWN'   => $salaryBreakdownHtml,
    ];
}
