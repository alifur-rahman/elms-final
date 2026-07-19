<?php
$basePath    = '..';
$pageTitle   = 'Salary Breakdown';
$activePage  = 'offer_proposal';
$breadcrumb  = 'Home / Recruitment / Offer Management / Salary Proposal / Salary Breakdown';

require_once __DIR__ . '/../includes/config.php';

$pdo = getDB();
$candidateId = (int)($_GET['candidate_id'] ?? 0);
$proposalId  = (int)($_GET['proposal_id'] ?? 0);

$candidate = null;
$proposal  = null;

if ($candidateId > 0) {
    $stmt = $pdo->prepare("SELECT c.*, r.title AS job_title, cs.name AS status_name FROM candidates c LEFT JOIN requisitions r ON r.id = c.job_id LEFT JOIN candidate_statuses cs ON cs.id = c.status_id WHERE c.id = :id");
    $stmt->execute([':id' => $candidateId]);
    $candidate = $stmt->fetch();
}

if ($proposalId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM salary_proposals WHERE id = :id");
    $stmt->execute([':id' => $proposalId]);
    $proposal = $stmt->fetch();
} elseif ($candidateId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM salary_proposals WHERE candidate_id = :cid ORDER BY version DESC LIMIT 1");
    $stmt->execute([':cid' => $candidateId]);
    $proposal = $stmt->fetch();
}

$isRevise = $proposal && in_array($proposal['status'], ['Proposed', 'Draft']);
$breakdown = [];
if ($proposal && $proposal['salary_breakdown']) {
    $bd = json_decode($proposal['salary_breakdown'], true);
    if (is_array($bd)) $breakdown = $bd;
}

$pageStyles  = '';
$candidateIdJson = json_encode($candidateId);
$breakdownJson   = json_encode($breakdown);
$pageScripts = <<<EOSCRIPT
var currentCandidateId = $candidateIdJson;
var existingBreakdown = $breakdownJson;

function calculateSalary() {
    var grossSalary = parseFloat(document.getElementById("gross-salary").value) || 0;
    var basicSalary = grossSalary * 0.571425;
    var houseRent = basicSalary * 0.5;
    var medicalAllowance = basicSalary * 0.1;
    var convenience = basicSalary * 0.15;

    document.getElementById("house_rent").value = Math.round(houseRent);
    document.getElementById("medical_allowance").value = Math.round(medicalAllowance);
    document.getElementById("convenience").value = Math.round(convenience);
    document.getElementById("basic-salary").value = Math.round(basicSalary);
    document.getElementById("total_salary").value = Math.round(grossSalary);

    calculateSalaryTax();
    calculateTotalPayable();
}

function calculateSalaryTax() {
    var grossSalary = parseFloat(document.getElementById("gross-salary").value) || 0;
    var basicSalary = parseFloat(document.getElementById("basic-salary").value) || 0;
    var genderEl = document.getElementById("gender");
    var gender = genderEl ? genderEl.value : null;

    if (grossSalary > 0 && basicSalary > 0 && gender) {
        fetch("ajax/get_taxinfo_ajx.php?gross_salary=" + grossSalary + "&basic_salary=" + basicSalary + "&gender=" + gender)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    document.getElementById("monthly_tax").value = parseFloat(data.tax).toFixed(2);
                } else {
                    document.getElementById("monthly_tax").value = "";
                }
                calculateTotalPayable();
            })
            .catch(function() {
                document.getElementById("monthly_tax").value = "";
                calculateTotalPayable();
            });
    } else {
        document.getElementById("monthly_tax").value = "";
        calculateTotalPayable();
    }
}

function calculateTotalPayable() {
    var totalSalary = parseFloat(document.getElementById("total_salary").value) || 0;
    var personalPF = parseFloat(document.getElementById("personal_pf").value) || 0;
    var pfDeductible = parseFloat(document.getElementById("pf_deductible").value) || 0;
    var incomeTax = parseFloat(document.getElementById("monthly_tax").value) || 0;
    var totalPayable = totalSalary - personalPF - pfDeductible - incomeTax;
    document.getElementById("total_payable").value = totalPayable >= 0 ? totalPayable.toFixed(2) : "0.00";
}

function setVal(id, val) {
    var el = document.getElementById(id);
    if (el) el.value = val !== null && val !== undefined ? val : "";
}

function saveProposal() {
    function v(id) { return parseFloat(document.getElementById(id).value) || 0; }
    function t(id) { return document.getElementById(id).value.trim(); }

    var grossSalary = v("gross-salary");
    if (!grossSalary) { showAlert("Gross Salary is required", "error"); return; }

    var breakdown = {
        gross_salary:         grossSalary,
        basic:                v("basic-salary"),
        house_rent:           v("house_rent"),
        medical_allowance:    v("medical_allowance"),
        conveyance_allowance: v("convenience"),
        food_allowance:       0,
        special_allowance:    0,
        mobile_allowance:     0,
        personal_pf:          v("personal_pf"),
        company_pf:           v("company_pf"),
        pf_deductible:        v("pf_deductible"),
        monthly_income_tax:   v("monthly_tax"),
        cash_paid:            v("cash_paid"),
        bank_paid:            v("bank_paid"),
        total_salary:         v("total_salary"),
        total_payable:        v("total_payable"),
        gender:               document.getElementById("gender").value,
        pf_applicable:        document.getElementById("pf_applicable").checked,
        ot_applicable_out:    document.getElementById("ot_out").checked,
        ot_holiday:           document.getElementById("ot_holiday").checked,
        ot_weekend:           document.getElementById("ot_weekend").checked,
        bank_name:            t("bank_name"),
        account_no:           t("account_no"),
        receiving_bank_code:  t("bank_code"),
        receiving_bank_name:  t("receiving_bank"),
        salary_given_by:      t("salary_given_by"),
        negotiation_notes:    t("negotiation_notes")
    };

    apiPost("ajax/proposal_create.php", {
        candidate_id: currentCandidateId,
        proposed_salary: grossSalary,
        negotiation_notes: breakdown.negotiation_notes || "",
        salary_breakdown: breakdown
    }, function(res) {
        showAlert(res.message || "Proposal saved!", "success");
        setTimeout(function() { window.location.href = "proposal.php"; }, 800);
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save proposal", "error");
    });
}

function loadExistingData() {
    var bd = existingBreakdown;
    if (!bd || !bd.gross_salary) return;
    setVal("gross-salary", bd.gross_salary);
    setVal("basic-salary", bd.basic);
    setVal("house_rent", bd.house_rent);
    setVal("medical_allowance", bd.medical_allowance);
    setVal("convenience", bd.conveyance_allowance);
    setVal("total_salary", bd.total_salary);
    setVal("monthly_tax", bd.monthly_income_tax);
    setVal("total_payable", bd.total_payable);
    setVal("personal_pf", bd.personal_pf);
    setVal("company_pf", bd.company_pf);
    setVal("pf_deductible", bd.pf_deductible);
    setVal("cash_paid", bd.cash_paid);
    setVal("bank_paid", bd.bank_paid);
    document.getElementById("pf_applicable").checked = bd.pf_applicable === true;
    document.getElementById("ot_out").checked = bd.ot_applicable_out === true;
    document.getElementById("ot_holiday").checked = bd.ot_holiday === true;
    document.getElementById("ot_weekend").checked = bd.ot_weekend === true;
    setVal("bank_name", bd.bank_name);
    setVal("account_no", bd.account_no);
    setVal("bank_code", bd.receiving_bank_code);
    setVal("receiving_bank", bd.receiving_bank_name);
    setVal("salary_given_by", bd.salary_given_by);
    setVal("negotiation_notes", bd.negotiation_notes);
    if (bd.gender) document.getElementById("gender").value = bd.gender;

    if (bd.gross_salary) calculateSalary();
}

document.addEventListener("DOMContentLoaded", loadExistingData);
EOSCRIPT;
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2><?= $isRevise ? 'Revise' : 'Create' ?> Salary Proposal</h2>
        <a href="proposal.php" class="btn btn-sm btn-outline">&larr; Back to List</a>
    </div>
    <div class="card-body">
        <?php if (!$candidate && !$proposal): ?>
            <div class="empty-state">
                <div class="empty-icon">&#9888;&#65039;</div>
                <p>No candidate selected. <a href="proposal.php">Go back to proposal list</a>.</p>
            </div>
        <?php else: ?>
        <form id="proposalForm" autocomplete="off">

            <div class="form-section">
                <div class="form-section-title">
                    <span class="section-icon">&#128100;</span> Candidate Information
                </div>
                <div class="form-row-4">
                    <div class="form-group">
                        <label>Candidate</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars(($candidate['first_name'] ?? '') . ' ' . ($candidate['last_name'] ?? '')) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Job Title</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidate['job_title'] ?? 'N/A') ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Expected Salary</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidate['expected_salary'] ?? '') ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" id="gender" onchange="calculateSalary()">
                            <option value="male" <?= ($breakdown['gender'] ?? 'male') === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= ($breakdown['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <span class="section-icon">&#128176;</span> Salary Breakdown
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Gross Salary <span class="required">*</span></label>
                        <input type="number" class="form-control" id="gross-salary" placeholder="Enter gross salary" oninput="calculateSalary()" style="font-weight:600;font-size:15px" value="<?= htmlspecialchars($breakdown['gross_salary'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Basic Salary</label>
                        <input type="number" class="form-control" id="basic-salary" placeholder="Auto-calculated" readonly>
                    </div>
                    <div class="form-group">
                        <label>House Rent</label>
                        <input type="number" class="form-control" id="house_rent" placeholder="Auto-calculated" readonly>
                    </div>
                    <div class="form-group">
                        <label>Medical Allowance</label>
                        <input type="number" class="form-control" id="medical_allowance" placeholder="Auto-calculated" readonly>
                    </div>
                    <div class="form-group">
                        <label>Conveyance Allowance</label>
                        <input type="number" class="form-control" id="convenience" placeholder="Auto-calculated" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total Salary</label>
                        <input type="number" class="form-control" id="total_salary" placeholder="Auto-calculated" readonly>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <span class="section-icon">&#128200;</span> Deductions &amp; Overtime
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Personal PF</label>
                        <input type="number" class="form-control" id="personal_pf" placeholder="0" oninput="calculateTotalPayable()" value="<?= htmlspecialchars($breakdown['personal_pf'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Company PF</label>
                        <input type="number" class="form-control" id="company_pf" placeholder="0" value="<?= htmlspecialchars($breakdown['company_pf'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>PF Deductible</label>
                        <input type="number" class="form-control" id="pf_deductible" placeholder="0" oninput="calculateTotalPayable()" value="<?= htmlspecialchars($breakdown['pf_deductible'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Monthly Income Tax</label>
                        <input type="number" class="form-control" id="monthly_tax" placeholder="Auto-calculated" readonly>
                    </div>
                </div>
                <div style="display:flex;gap:20px;flex-wrap:wrap;padding-top:4px">
                    <label class="flex" style="align-items:center;gap:6px;font-size:13px;font-weight:400;cursor:pointer">
                        <input type="checkbox" id="pf_applicable" <?= !empty($breakdown['pf_applicable']) ? 'checked' : '' ?>> PF Applicable
                    </label>
                    <label class="flex" style="align-items:center;gap:6px;font-size:13px;font-weight:400;cursor:pointer">
                        <input type="checkbox" id="ot_out" <?= !empty($breakdown['ot_applicable_out']) ? 'checked' : '' ?>> OT (Out)
                    </label>
                    <label class="flex" style="align-items:center;gap:6px;font-size:13px;font-weight:400;cursor:pointer">
                        <input type="checkbox" id="ot_holiday" <?= !empty($breakdown['ot_holiday']) ? 'checked' : '' ?>> OT Holiday
                    </label>
                    <label class="flex" style="align-items:center;gap:6px;font-size:13px;font-weight:400;cursor:pointer">
                        <input type="checkbox" id="ot_weekend" <?= !empty($breakdown['ot_weekend']) ? 'checked' : '' ?>> OT Weekend
                    </label>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <span class="section-icon">&#127974;</span> Bank &amp; Payment Details
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" class="form-control" id="bank_name" placeholder="Bank name" value="<?= htmlspecialchars($breakdown['bank_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Account No</label>
                        <input type="text" class="form-control" id="account_no" placeholder="Account number" value="<?= htmlspecialchars($breakdown['account_no'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Receiving Bank Code</label>
                        <input type="text" class="form-control" id="bank_code" placeholder="Routing / sort code" value="<?= htmlspecialchars($breakdown['receiving_bank_code'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Receiving Bank Name</label>
                        <input type="text" class="form-control" id="receiving_bank" placeholder="Receiving bank name" value="<?= htmlspecialchars($breakdown['receiving_bank_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Salary Given By</label>
                        <input type="text" class="form-control" id="salary_given_by" placeholder="e.g. Cash / Bank" value="<?= htmlspecialchars($breakdown['salary_given_by'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Cash Paid</label>
                        <input type="number" class="form-control" id="cash_paid" placeholder="0" value="<?= htmlspecialchars($breakdown['cash_paid'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Bank Paid</label>
                        <input type="number" class="form-control" id="bank_paid" placeholder="0" value="<?= htmlspecialchars($breakdown['bank_paid'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <span class="section-icon">&#9989;</span> Summary
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Total Payable</label>
                        <input type="number" class="form-control" id="total_payable" placeholder="0" readonly>
                    </div>
                    <div class="form-group">
                        <label>Negotiation Notes</label>
                        <textarea class="form-control" id="negotiation_notes" rows="3" placeholder="Any notes on negotiation..."><?= htmlspecialchars($breakdown['negotiation_notes'] ?? $proposal['negotiation_notes'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="btn-group" style="padding-top:20px;border-top:1px solid var(--gray-200)">
                <a href="proposal.php" class="btn btn-outline">Cancel</a>
                <button type="button" class="btn btn-primary" onclick="saveProposal()">Save Proposal</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
