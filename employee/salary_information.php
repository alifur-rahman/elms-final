<?php
require_once __DIR__ . '/../includes/config.php';
$basePath    = '..';
$pageTitle   = 'Salary Information';
$activePage  = 'create_employee';
$breadcrumb  = 'Home / Employee / Salary Information';
$offerId     = (int)($_GET['offer_id'] ?? 0);

$pdo = getDB();
$emp = null;
$salary = null;
if ($offerId > 0) {
    $stmt = $pdo->prepare("SELECT p.PBI_ID, p.PBI_NAME, p.last_name, p.PBI_CODE, p.job_title, p.PBI_DESIGNATION, o.salary FROM personnel_basic_info p JOIN offers o ON o.id = p.offer_id WHERE p.offer_id = :oid");
    $stmt->execute([':oid' => $offerId]);
    $emp = $stmt->fetch();
    if ($emp) {
        $stmt2 = $pdo->prepare("SELECT * FROM salary_info WHERE offer_id = :oid");
        $stmt2->execute([':oid' => $offerId]);
        $salary = $stmt2->fetch();
    }
}

$pageStyles  = '
.section-box { background:#fff; border:1px solid var(--gray-200); border-radius:10px; padding:20px 24px; margin-bottom:20px }
.section-box-title { font-size:14px; font-weight:700; color:var(--gray-800); margin-bottom:16px; display:flex; align-items:center; gap:8px }
.section-box-title::before { content:""; display:inline-block; width:4px; height:18px; background:var(--primary); border-radius:2px }
.form-row { display:grid; gap:14px 16px; margin-bottom:0 }
.form-row-2 { grid-template-columns:1fr 1fr }
.form-row-3 { grid-template-columns:1fr 1fr 1fr }
.form-row-4 { grid-template-columns:1fr 1fr 1fr 1fr }
.form-group { margin-bottom:0 }
.form-group label { display:block; font-size:12px; font-weight:600; color:var(--gray-600); margin-bottom:4px; letter-spacing:.2px }
.form-group label .req { color:var(--danger) }
.form-control { width:100%; padding:9px 12px; border:1px solid var(--gray-300); border-radius:6px; font-size:13px; background:#fff; transition:all .2s; color:var(--gray-800) }
.form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,115,232,.1) }
.form-control.auto-filled { border-color:var(--success); background:#f0fdf4 }
select.form-control { appearance:auto; cursor:pointer }
.empty-state { text-align:center; padding:48px 24px; color:var(--gray-500) }
.empty-state .empty-icon { font-size:48px; margin-bottom:12px }
.empty-state p { font-size:14px }
.emp-info-bar { display:flex; gap:24px; padding:14px 20px; background:var(--gray-50); border:1px solid var(--gray-200); border-radius:8px; margin-bottom:20px; align-items:center }
.emp-info-bar .emp-label { font-size:11px; color:var(--gray-500); text-transform:uppercase; letter-spacing:.3px }
.emp-info-bar .emp-val { font-size:14px; font-weight:600; color:var(--gray-800) }
.bottom-bar { display:flex; justify-content:flex-end; align-items:center; padding:16px 0; border-top:1px solid var(--gray-200); margin-top:8px }
';
$pageScripts = <<<EOSCRIPT
var offerId = %d;
var empData = %s;
var salaryData = %s;

function setVal(id, val) {
    var el = document.getElementById(id);
    if (!el || val === null || val === undefined) return;
    if (val === "" || val === "0" || val === "0.00" || val === "0.0000") return;
    el.value = val;
}

function autofillFromSalary() {
    if (!salaryData) return;
    var keys = Object.keys(salaryData);
    for (var i = 0; i < keys.length; i++) {
        setVal(keys[i], salaryData[keys[i]]);
    }
}

function setDefaultSalary() {
    if (!empData) return;
    var gross = parseFloat(empData.salary) || 0;
    if (gross <= 0) return;
    var basic = Math.round(gross * 0.571425);
    var hr = Math.round(basic * 0.5);
    var med = Math.round(basic * 0.1);
    var conv = Math.round(basic * 0.15);
    setVal("basic_salary", basic);
    setVal("house_rent", hr);
    setVal("medical_allowance", med);
    setVal("convenience", conv);
    setVal("gross_salary", Math.round(gross));
    setVal("total_salary", Math.round(gross));
}

function submitSalary() {
    var form = document.getElementById("salaryForm");
    var data = {};
    var inputs = form.querySelectorAll("input, select, textarea");
    for (var i = 0; i < inputs.length; i++) {
        var el = inputs[i];
        if (el.name) data[el.name] = el.value;
    }
    data.offer_id = offerId;
    apiPost("ajax/save_salary.php", data, function(res) {
        if (res.success) {
            showAlert(res.message || "Salary info saved!", "success");
            setTimeout(function() { window.location.href = "view.php?offer_id=" + offerId; }, 1000);
        } else {
            showAlert(res.message || "Failed to save", "error");
        }
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save salary info", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    if (salaryData) { autofillFromSalary(); }
    else { setDefaultSalary(); }
});
EOSCRIPT;
$pageScripts = sprintf($pageScripts, $offerId, json_encode($emp), json_encode($salary));

if (!$emp) { $content = '<div class="empty-state"><div class="empty-icon">&#128100;</div><p>Employee not found. <a href="list.php">Go back</a></p></div>'; require_once __DIR__ . '/../includes/layout.php'; exit; }

$empName = htmlspecialchars(($emp['PBI_NAME'] ?? '') . ' ' . ($emp['last_name'] ?? ''));
$empCode = htmlspecialchars($emp['PBI_CODE'] ?? '');
$empJob = htmlspecialchars($emp['job_title'] ?? $emp['PBI_DESIGNATION'] ?? '');
$empSal = number_format((float)($emp['salary'] ?? 0), 2);
ob_start();
?>
<div class="card">
    <div class="card-header" style="border-bottom:none;padding-bottom:0">
        <h2>Salary Information</h2>
        <div style="display:flex;gap:8px">
            <a href="create.php?offer_id=<?= $offerId ?>" class="btn btn-sm btn-outline">&larr; Back</a>
        </div>
    </div>
    <div class="card-body" style="padding-top:0">
        <div class="emp-info-bar">
            <div><div class="emp-label">Employee</div><div class="emp-val"><?= $empName ?></div></div>
            <div><div class="emp-label">Code</div><div class="emp-val"><?= $empCode ?></div></div>
            <div><div class="emp-label">Job Title</div><div class="emp-val"><?= $empJob ?></div></div>
            <div><div class="emp-label">Offered Salary</div><div class="emp-val"><?= $empSal ?> BDT</div></div>
        </div>

        <form id="salaryForm" autocomplete="off">

        <!-- Salary Type & Base -->
        <div class="section-box">
            <div class="section-box-title">Salary Type & Base</div>
            <div class="form-row form-row-4">
                <div class="form-group"><label>Salary Type</label><input type="text" class="form-control" name="salary_type" id="salary_type" placeholder="e.g. Consolidated"></div>
                <div class="form-group"><label>Gross Salary</label><input type="number" class="form-control" name="gross_salary" id="gross_salary" step="1"></div>
                <div class="form-group"><label>Basic Salary</label><input type="number" class="form-control" name="basic_salary" id="basic_salary" step="0.01"></div>
                <div class="form-group"><label>Consolidated Salary</label><input type="number" class="form-control" name="consolidated_salary" id="consolidated_salary" step="0.01"></div>
            </div>
        </div>

        <!-- Allowances -->
        <div class="section-box">
            <div class="section-box-title">Allowances</div>
            <div class="form-row form-row-4">
                <div class="form-group"><label>House Rent</label><input type="number" class="form-control" name="house_rent" id="house_rent" step="0.01"></div>
                <div class="form-group"><label>Medical Allowance</label><input type="number" class="form-control" name="medical_allowance" id="medical_allowance" step="0.01"></div>
                <div class="form-group"><label>Convenience</label><input type="number" class="form-control" name="convenience" id="convenience" step="0.01"></div>
                <div class="form-group"><label>Transport Allowance</label><input type="number" class="form-control" name="transport_allowance" id="transport_allowance" step="0.01"></div>
                <div class="form-group"><label>Fuel Allowance</label><input type="number" class="form-control" name="fuel_allowance" id="fuel_allowance" step="0.01"></div>
                <div class="form-group"><label>Food Allowance</label><input type="number" class="form-control" name="food_allowance" id="food_allowance" step="0.01"></div>
                <div class="form-group"><label>Mobile Allowance</label><input type="number" class="form-control" name="mobile_allowance" id="mobile_allowance" step="0.01"></div>
                <div class="form-group"><label>Mobile Ceiling</label><input type="number" class="form-control" name="mobile_ceiling" id="mobile_ceiling" step="0.01"></div>
                <div class="form-group"><label>Special Allowance</label><input type="number" class="form-control" name="special_allowance" id="special_allowance" step="0.01"></div>
                <div class="form-group"><label>Utility Allowance</label><input type="number" class="form-control" name="utility_allowance" id="utility_allowance" step="0.01"></div>
                <div class="form-group"><label>Entertainment</label><input type="number" class="form-control" name="entertainment" id="entertainment" step="0.01"></div>
                <div class="form-group"><label>Contribution</label><input type="number" class="form-control" name="contribution" id="contribution" step="0.01"></div>
                <div class="form-group"><label>Dearness Allowance</label><input type="number" class="form-control" name="dearness_allowance" id="dearness_allowance" step="0.01"></div>
                <div class="form-group"><label>Commission</label><input type="number" class="form-control" name="commission" id="commission" step="0.01"></div>
                <div class="form-group"><label>Children Edu Allowance</label><input type="number" class="form-control" name="children_edu_allowance" id="children_edu_allowance" step="0.01"></div>
                <div class="form-group"><label>Responsibility Allowance</label><input type="number" class="form-control" name="responsibility_allowance" id="responsibility_allowance" step="0.01"></div>
                <div class="form-group"><label>Superintendent Allowance</label><input type="number" class="form-control" name="superintendent_allowance" id="superintendent_allowance" step="0.01"></div>
                <div class="form-group"><label>Servant Allowance</label><input type="number" class="form-control" name="servant_allowance" id="servant_allowance" step="0.01"></div>
                <div class="form-group"><label>Comstry Allowance</label><input type="number" class="form-control" name="comstry_allowance" id="comstry_allowance" step="0.01"></div>
                <div class="form-group"><label>Marketing Allowance</label><input type="number" class="form-control" name="marketing_allowance" id="marketing_allowance" step="0.01"></div>
                <div class="form-group"><label>Realestate Allowance</label><input type="number" class="form-control" name="realestate_allowance" id="realestate_allowance" step="0.01"></div>
                <div class="form-group"><label>Car Driver Allowance</label><input type="number" class="form-control" name="car_driver_allowance" id="car_driver_allowance" step="0.01"></div>
                <div class="form-group"><label>TA</label><input type="number" class="form-control" name="ta" id="ta" step="0.01"></div>
                <div class="form-group"><label>DA</label><input type="number" class="form-control" name="da" id="da" step="0.01"></div>
                <div class="form-group"><label>Other Allowance</label><input type="number" class="form-control" name="other_allowance" id="other_allowance" step="0.01"></div>
                <div class="form-group"><label>Others</label><input type="number" class="form-control" name="others" id="others" step="0.01"></div>
            </div>
        </div>

        <!-- Deductions -->
        <div class="section-box">
            <div class="section-box-title">Deductions</div>
            <div class="form-row form-row-4">
                <div class="form-group"><label>PF (Employee)</label><input type="number" class="form-control" name="pf" id="pf" step="0.01"></div>
                <div class="form-group"><label>PF (Company)</label><input type="number" class="form-control" name="pf_company" id="pf_company" step="0.01"></div>
                <div class="form-group"><label>PF Applicable</label><select class="form-control" name="pf_applicable" id="pf_applicable"><option value="No">No</option><option value="Yes">Yes</option></select></div>
                <div class="form-group"><label>PF Deductible</label><select class="form-control" name="pf_deductible" id="pf_deductible"><option value="No">No</option><option value="Yes">Yes</option></select></div>
                <div class="form-group"><label>PF Status</label><input type="text" class="form-control" name="pf_status" id="pf_status"></div>
                <div class="form-group"><label>Income Tax</label><input type="number" class="form-control" name="income_tax" id="income_tax" step="0.01"></div>
                <div class="form-group"><label>Monthly Income Tax</label><input type="number" class="form-control" name="m_income_tex" id="m_income_tex"></div>
                <div class="form-group"><label>Tax Status</label><input type="text" class="form-control" name="tax_status" id="tax_status"></div>
                <div class="form-group"><label>Group Insurance</label><input type="number" class="form-control" name="group_insurance" id="group_insurance" step="0.01"></div>
                <div class="form-group"><label>C Fund</label><input type="number" class="form-control" name="cfund" id="cfund" step="0.01"></div>
                <div class="form-group"><label>SM Money</label><input type="number" class="form-control" name="sm_money" id="sm_money" step="0.01"></div>
                <div class="form-group"><label>Security Amount</label><input type="number" class="form-control" name="security_amount" id="security_amount" step="0.01"></div>
                <div class="form-group"><label>Cooperative Share</label><input type="number" class="form-control" name="cooperative_share" id="cooperative_share"></div>
                <div class="form-group"><label>Motorcycle Install</label><input type="number" class="form-control" name="motorcycle_install" id="motorcycle_install" step="0.01"></div>
                <div class="form-group"><label>Fixed TADA</label><input type="number" class="form-control" name="fixed_tada" id="fixed_tada" step="0.01"></div>
            </div>
        </div>

        <!-- Totals & Overtime -->
        <div class="section-box">
            <div class="section-box-title">Totals & Overtime</div>
            <div class="form-row form-row-4">
                <div class="form-group"><label>Total Salary</label><input type="number" class="form-control" name="total_salary" id="total_salary"></div>
                <div class="form-group"><label>Total Payable</label><input type="number" class="form-control" name="total_payable" id="total_payable" step="0.01"></div>
                <div class="form-group"><label>Bonus Applicable</label><select class="form-control" name="bonus_applicable" id="bonus_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                <div class="form-group"><label>Overtime Applicable</label><select class="form-control" name="overtime_applicable" id="overtime_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                <div class="form-group"><label>OT Weekend</label><select class="form-control" name="ot_weekend_applicable" id="ot_weekend_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                <div class="form-group"><label>OT Holiday</label><select class="form-control" name="ot_holiday_applicable" id="ot_holiday_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
            </div>
        </div>

        <!-- Bank & Payment -->
        <div class="section-box">
            <div class="section-box-title">Bank & Payment</div>
            <div class="form-row form-row-4">
                <div class="form-group"><label>Cash / Bank</label><select class="form-control" name="cash_bank" id="cash_bank"><option value="">--</option><option value="Cash">Cash</option><option value="Bank">Bank</option><option value="Both">Both</option></select></div>
                <div class="form-group"><label>Bank Name</label><input type="number" class="form-control" name="bank_name" id="bank_name"></div>
                <div class="form-group"><label>Branch Info</label><input type="text" class="form-control" name="branch_info" id="branch_info"></div>
                <div class="form-group"><label>Account No</label><input type="text" class="form-control" name="ac_no" id="ac_no"></div>
                <div class="form-group"><label>Cash Amount</label><input type="number" class="form-control" name="cash_amt" id="cash_amt" step="0.01"></div>
                <div class="form-group"><label>Bank Amount</label><input type="number" class="form-control" name="bank_amt" id="bank_amt" step="0.01"></div>
                <div class="form-group"><label>Cash</label><input type="number" class="form-control" name="cash" id="cash"></div>
            </div>
        </div>

        </form>

        <div class="bottom-bar">
            <button type="button" class="btn btn-primary" onclick="submitSalary()" style="padding:10px 32px;font-size:14px">Save Salary Information</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
