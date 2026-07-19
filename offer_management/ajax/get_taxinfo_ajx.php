<?php
header('Content-Type: application/json');

$grossSalary = (float)($_GET['gross_salary'] ?? 0);
$basicSalary = (float)($_GET['basic_salary'] ?? 0);
$gender      = strtolower(trim($_GET['gender'] ?? ''));

if ($grossSalary <= 0 || $basicSalary <= 0 || !in_array($gender, ['male', 'female'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$annualGross = $grossSalary * 12;
$annualBasic = $basicSalary * 12;

// Bangladesh tax slabs (FY 2024-25 simplified)
$taxFreeLimit = ($gender === 'female') ? 400000 : 350000;
$surcharge = 0;

$taxable = max(0, $annualGross - $taxFreeLimit);
$tax = 0;

if ($taxable > 0) {
    // First 100,000 @ 5%
    $slab = min(100000, $taxable);
    $tax += $slab * 0.05;
    $taxable -= $slab;
}
if ($taxable > 0) {
    // Next 300,000 @ 10%
    $slab = min(300000, $taxable);
    $tax += $slab * 0.10;
    $taxable -= $slab;
}
if ($taxable > 0) {
    // Next 400,000 @ 15%
    $slab = min(400000, $taxable);
    $tax += $slab * 0.15;
    $taxable -= $slab;
}
if ($taxable > 0) {
    // Next 500,000 @ 20%
    $slab = min(500000, $taxable);
    $tax += $slab * 0.20;
    $taxable -= $slab;
}
if ($taxable > 0) {
    // Rest @ 25%
    $tax += $taxable * 0.25;
}

// Minimum tax check
$minTax = 5000;
if ($tax > 0 && $tax < $minTax) {
    $tax = $minTax;
}

$monthlyTax = round($tax / 12, 2);

echo json_encode([
    'success' => true,
    'tax'     => $monthlyTax,
    'annual_tax' => round($tax, 2),
    'taxable_income' => max(0, ($annualGross - $taxFreeLimit)),
]);
