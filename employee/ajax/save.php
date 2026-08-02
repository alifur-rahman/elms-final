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
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Employee already exists for this offer']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT o.*, c.first_name, c.last_name, c.email FROM elms_offers o LEFT JOIN elms_candidates c ON c.id = o.candidate_id WHERE o.id = :id");
    $stmt->execute([':id' => $offerId]);
    $offer = $stmt->fetch();
    if (!$offer) {
        echo json_encode(['success' => false, 'message' => 'Offer not found']);
        exit;
    }

    $maxCode = $pdo->query("SELECT MAX(PBI_CODE) AS mx FROM elms_personnel_basic_info")->fetch();
    $nextNum = 1;
    if ($maxCode && $maxCode['mx']) {
        preg_match('/(\d+)$/', $maxCode['mx'], $m);
        $nextNum = ($m[1] ?? 0) + 1;
    }
    $empCode = 'EMP-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

    $fields = [
        'PBI_CODE', 'MACHINE_ID', 'PBI_DUE_DOJ', 'PBI_NAME', 'PBI_FATHER_NAME', 'PBI_MOTHER_NAME',
        'DEPT_ID', 'PBI_DEPARTMENT', 'LEAVE_RULE_ID', 'nid', 'employee_type', 'DESG_ID', 'PBI_DESIGNATION',
        'PBI_ZONE', 'PBI_AREA', 'PBI_BRANCH', 'PBI_REGION', 'PBI_DOJ', 'PBI_SERVICE_LENGTH', 'PBI_DOC',
        'PBI_DOB', 'PBI_SEX', 'PBI_MARITAL_STA', 'PBI_RELIGION', 'PBI_NATIONALITY',
        'PBI_PERMANENT_ADD', 'PBI_PRESENT_ADD', 'PBI_PHONE', 'PBI_MOBILE', 'PBI_EMAIL',
        'PBI_JOB_STATUS', 'PBI_BLOOD_GROUP', 'PBI_CON_TYPE', 'PBI_ORG', 'PBI_EMAIL_ALT',
        'EMPLOYMENT_TYPE', 'define_schedule', 'note', 'JOB_LOC_ID', 'last_name',
        'section', 'cost_center', 'PBI_FUNCTION', 'job_title', 'grade', 'job_description',
        'class', 'level', 'cost_type', 'PBI_WORK_STATION', 'joining_designation',
        'Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday',
        'payroll_designation', 'spouse_name', 'highte', 'weight', 'birth_reg_no',
        'nominee', 'nominee_relation', 'child1_name', 'child1_dob', 'child1_details',
        'child2_name', 'child2_dob', 'child2_details', 'child3_name', 'child3_dob', 'child3_details',
        'child4_name', 'child4_dob', 'child4_details', 'salary_schedule', 'line',
        'schedule_type', 'punch_type', 'pre_block_no', 'pre_ps', 'pre_house_no', 'pre_flat',
        'pre_road_no', 'pre_district', 'hobbies',
    ];

    $insertCols = ['PBI_CODE', 'candidate_id', 'offer_id', 'entry_by'];
    $insertVals = [':PBI_CODE', ':candidate_id', ':offer_id', ':entry_by'];
    $params = [
        ':PBI_CODE'     => $empCode,
        ':candidate_id' => $offer['candidate_id'],
        ':offer_id'     => $offerId,
        ':entry_by'     => 1,
    ];

    foreach ($fields as $f) {
        if (isset($body[$f]) && $body[$f] !== '') {
            $insertCols[] = $f;
            $insertVals[] = ':' . $f;
            $params[':' . $f] = $body[$f];
        }
    }

    $sql = "INSERT INTO elms_personnel_basic_info (" . implode(',', $insertCols) . ") VALUES (" . implode(',', $insertVals) . ")";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $newId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Employee created successfully! Code: ' . $empCode,
        'data' => [
            'employee_id' => $newId,
            'employee_code' => $empCode,
            'offer_id' => $offerId,
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
