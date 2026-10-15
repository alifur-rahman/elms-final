<?php

ob_start();

require_once "../../../controllers/routing/default_values.php";
require_once SERVER_CORE . "routing/layout.top.php";
require_once "../helpers/helper_functions.php";

ob_end_clean();

header('Content-Type: application/json');

$table_master = "elms_personnel_basic_info";
$unique       = "PBI_ID";

try {

    $crud = new crud($table_master);

    // -------------------------------------------------
    // Method guard
    // -------------------------------------------------

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // -------------------------------------------------
    // Input
    // -------------------------------------------------

    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) $body = $_POST;

    $offerId = (int)($body['offer_id'] ?? 0);

    if ($offerId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'offer_id required']);
        exit;
    }

    // -------------------------------------------------
    // Reject if employee already exists
    // -------------------------------------------------

    $existing = find_all_field(
        $table_master,
        'PBI_ID, offer_id',
        "offer_id = {$offerId}"
    );

    if ($existing && !empty($existing->PBI_ID)) {
        echo json_encode([
            'success' => false,
            'message' => 'Employee already exists for this offer'
        ]);
        exit;
    }

    // -------------------------------------------------
    // Load offer
    // -------------------------------------------------

    $offer = find_all_field(
        'elms_offers',
        'id, candidate_id',
        "id = {$offerId}"
    );

    if (!$offer || empty($offer->id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Offer not found'
        ]);
        exit;
    }

    // -------------------------------------------------
    // Generate next employee code
    // -------------------------------------------------

    $maxQry = db_query("SELECT MAX(PBI_CODE) AS mx FROM elms_personnel_basic_info");
    $maxRow = $maxQry ? mysqli_fetch_object($maxQry) : null;

    $nextNum = 1;

    if ($maxRow && !empty($maxRow->mx)) {
        preg_match('/(\d+)$/', $maxRow->mx, $m);
        $nextNum = ((int)($m[1] ?? 0)) + 1;
    }

    $empCode = 'EMP-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);

    // -------------------------------------------------
    // Field whitelist
    // -------------------------------------------------

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

    // -------------------------------------------------
    // Reset $_POST and populate
    // -------------------------------------------------

    $_POST = [];

    foreach ($fields as $f) {
        if (isset($body[$f]) && $body[$f] !== '') {
            $_POST[$f] = $body[$f];
        }
    }

    // Force system-managed columns (overwrite whatever client sent)
    $_POST['PBI_CODE']     = $empCode;
    $_POST['candidate_id'] = (int)$offer->candidate_id;
    $_POST['offer_id']     = $offerId;
    $_POST['entry_by']     = $_SESSION['user']['id'];
    $_POST['add_by']       = $_SESSION['user']['id'];
    $_POST['add_at']       = date('Y-m-d H:i:s');

    // -------------------------------------------------
    // Insert
    // -------------------------------------------------

    $newId = $crud->insert();

    // -------------------------------------------------
    // Store Candidate Log
    // -------------------------------------------------

    $candidateId = (int)$offer->candidate_id;

    if ($candidateId > 0) {
        addCandidateLog(
            $candidateId,
            'employee_created',
            null,
            null,
            'Employee created from offer #' . $offerId . ' with code ' . $empCode
        );
    }

    // -------------------------------------------------
    // Success Response
    // -------------------------------------------------

    echo json_encode([
        'success' => true,
        'message' => 'Employee created successfully! Code: ' . $empCode,
        'data'    => [
            'employee_id'   => $newId,
            'employee_code' => $empCode,
            'offer_id'      => $offerId,
        ]
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

exit;