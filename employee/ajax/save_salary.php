<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) $body = $_POST;

    $offerId = (int)($body['offer_id'] ?? 0);
    if ($offerId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id required']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT PBI_ID FROM elms_personnel_basic_info WHERE offer_id = :oid");
    $stmt->execute([':oid' => $offerId]);
    $emp = $stmt->fetch();
    if (!$emp) {
        echo json_encode(['success' => false, 'message' => 'Employee not found. Create employee first.']);
        exit;
    }

    $pbId = $emp['PBI_ID'];

    $fields = [
        'salary_type', 'basic_salary', 'commission', 'consolidated_salary',
        'house_rent', 'dearness_allowance', 'contribution', 'entertainment',
        'special_allowance', 'convenience', 'utility_allowance', 'children_edu_allowance',
        'responsibility_allowance', 'superintendent_allowance', 'servant_allowance',
        'transport_allowance', 'fuel_allowance', 'comstry_allowance', 'marketing_allowance',
        'realestate_allowance', 'car_driver_allowance', 'ta', 'da', 'others',
        'other_allowance', 'food_allowance', 'mobile_allowance', 'medical_allowance',
        'mobile_ceiling', 'bonus_applicable', 'overtime_applicable', 'ot_weekend_applicable',
        'ot_holiday_applicable', 'pf', 'sm_money', 'income_tax', 'group_insurance',
        'cfund', 'cash_bank', 'ac_no', 'cash_amt', 'bank_amt', 'branch_info',
        'security_amount', 'cooperative_share', 'motorcycle_install', 'fixed_tada',
        'gross_salary', 'total_salary', 'total_payable', 'm_income_tex', 'bank_name',
        'pf_company', 'tax_status', 'pf_status', 'cash', 'pf_applicable', 'pf_deductible',
    ];

    $exists = $pdo->prepare("SELECT id FROM elms_salary_info WHERE offer_id = :oid");
    $exists->execute([':oid' => $offerId]);
    $existing = $exists->fetch();

    if ($existing) {
        $setClauses = [];
        $params = [':oid' => $offerId];
        foreach ($fields as $f) {
            if (array_key_exists($f, $body)) {
                $setClauses[] = "$f = :$f";
                $params[":$f"] = $body[$f];
            }
        }
        $setClauses[] = "PBI_ID = :pbid";
        $params[':pbid'] = $pbId;
        $setClauses[] = "edit_by = :editby";
        $params[':editby'] = '1';
        $sql = "UPDATE elms_salary_info SET " . implode(', ', $setClauses) . " WHERE offer_id = :oid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $msg = "Salary information updated!";
    } else {
        $insertCols = ['PBI_ID', 'offer_id'];
        $placeholders = [':pbid', ':oid'];
        $params = [':pbid' => $pbId, ':oid' => $offerId];
        foreach ($fields as $f) {
            if (array_key_exists($f, $body)) {
                $insertCols[] = $f;
                $placeholders[] = ":$f";
                $params[":$f"] = $body[$f];
            }
        }
        $sql = "INSERT INTO elms_salary_info (" . implode(',', $insertCols) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $msg = "Salary information saved!";
    }

    echo json_encode(['success' => true, 'message' => $msg]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
