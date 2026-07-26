<?php
require_once __DIR__ . '/../includes/config.php';
$basePath    = '..';
$pageTitle   = 'Create Employee';
$activePage  = 'create_employee';
$breadcrumb  = 'Home / Employee / Create Employee';
$offerId     = (int)($_GET['offer_id'] ?? 0);
$pageCss = 'css/page_css/create.css';
$pageScripts = <<<'EOSCRIPT'
var offerData = null;
var offerId = __OFFER_ID__;
var currentStep = 0;
var totalSteps = 6;
var stepTitles = ["Personal Info", "Employment", "Address", "Family & Nominee", "Other Details", "Salary Setup"];
var stepSubs = ["Basic details & identity", "Job & schedule info", "Present & permanent address", "Spouse, children & nominee", "Location, working days & notes", "Salary breakdown & bank info"];
var autoFilledFields = [];

function loadCandidate() {
    if (!offerId) {
        document.getElementById("formContent").innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x26A0;</div><h3>No offer ID provided</h3><p><a href=\"list.php\">Go back to list</a></p></div>";
        return;
    }
    apiGet("ajax/get_candidate.php?offer_id=" + offerId, null, function(res) {
        if (!res.success || !res.data) {
            document.getElementById("formContent").innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x274C;</div><h3>Offer not found</h3><p><a href=\"list.php\">Go back to list</a></p></div>";
            return;
        }
        if (res.data.employee_exists) {
            window.location.href = "view.php?offer_id=" + offerId;
            return;
        }
        offerData = res.data.offer;
        autofillForm();
    });
}

function setVal(id, val) {
    var el = document.getElementById(id);
    if (!el || val === null || val === undefined || val === "") return false;
    el.value = val;
    el.classList.add("auto-filled");
    autoFilledFields.push(id);
    return true;
}

function autofillForm() {
    var o = offerData;
    setVal("PBI_NAME", o.first_name);
    setVal("last_name", o.last_name);
    setVal("PBI_EMAIL", o.email);
    setVal("PBI_MOBILE", o.phone);
    setVal("PBI_PHONE", o.phone);
    setVal("nid", o.nid);
    setVal("PBI_BLOOD_GROUP", o.blood_group);
    setVal("PBI_DOB", o.dob);
    if (o.gender) {
        var g = o.gender.charAt(0).toUpperCase() + o.gender.slice(1).toLowerCase();
        setVal("PBI_SEX", g);
    }
    setVal("PBI_PRESENT_ADD", o.present_address);
    setVal("PBI_PERMANENT_ADD", o.permanent_address);
    setVal("PBI_DOJ", o.joining_date);
    setVal("PBI_DUE_DOJ", o.joining_date);
    setVal("DEPT_ID", o.dept_id);
    setVal("DESG_ID", o.designation_id);
    setVal("PBI_DESIGNATION", o.designation_name);
    setVal("job_title", o.job_title);
    var salName = document.getElementById("sal_candidate_name");
    if (salName) salName.value = (o.first_name || "") + (o.last_name ? " " + o.last_name : "");
    var salTitle = document.getElementById("sal_job_title");
    if (salTitle) salTitle.value = o.job_title || "";
    var salOffered = document.getElementById("sal_offered_salary");
    if (salOffered) salOffered.value = o.salary ? parseFloat(o.salary).toLocaleString() : "";
    if (o.gender) {
        var gs = document.getElementById("gender");
        if (gs) gs.value = o.gender.toLowerCase();
    }
    if (autoFilledFields.length > 0) {
        showAlert("Auto-filled " + autoFilledFields.length + " fields from candidate data", "info");
    }
}

function safeParseFloat(v) { var n = parseFloat(v); return isNaN(n) ? 0 : n; }

function calculateSalary() {
    var gross = safeParseFloat(document.getElementById('gross_salary').value);
    var basic = Math.round(gross * 0.571425 * 100) / 100;
    var house = Math.round(basic * 0.5 * 100) / 100;
    var medical = Math.round(basic * 0.1 * 100) / 100;
    var conveyance = Math.round(basic * 0.15 * 100) / 100;

    document.getElementById('basic_salary').value = basic;
    document.getElementById('house_rent').value = house;
    document.getElementById('medical_allowance').value = medical;
    document.getElementById('convenience').value = conveyance;
    document.getElementById('total_salary').value = gross;

    calculateSalaryTax(gross, basic);
    calculateTotalPayable();
}

function calculateSalaryTax(gross, basic) {
    if (!gross) gross = safeParseFloat(document.getElementById('gross_salary').value);
    if (!basic) basic = safeParseFloat(document.getElementById('basic_salary').value);
    if (gross <= 0) { document.getElementById('m_income_tex').value = ''; return; }

    var genderSelect = document.getElementById('gender');
    var gender = genderSelect ? genderSelect.value : 'male';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../offer_management/ajax/get_taxinfo_ajx.php?gross_salary=' + gross + '&basic_salary=' + basic + '&gender=' + gender, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('m_income_tex').value = response.tax;
                        calculateTotalPayable();
                    }
                } catch (e) { console.error('Error parsing tax data:', e); }
            }
        }
    };
    xhr.send();
}

function calculateTotalPayable() {
    var totalSalary = safeParseFloat(document.getElementById('total_salary').value);
    var pf = safeParseFloat(document.getElementById('pf').value);
    var pfDeductible = safeParseFloat(document.getElementById('pf_deductible').value);
    var incomeTax = safeParseFloat(document.getElementById('m_income_tex').value);

    var totalPayable = totalSalary - pf - pfDeductible - incomeTax;
    document.getElementById('total_payable').value = Math.round(totalPayable * 100) / 100;
}

function updateStepBar() {
    var dots = document.querySelectorAll(".step-dot");
    var lines = document.querySelectorAll(".step-dot-line");
    dots.forEach(function(d, i) {
        d.classList.remove("active", "done");
        if (i < currentStep) d.classList.add("done");
        else if (i === currentStep) d.classList.add("active");
    });
    lines.forEach(function(l, i) {
        l.classList.toggle("done", i < currentStep);
    });
    var titleEl = document.getElementById("activeStepTitle");
    var subEl = document.getElementById("activeStepSub");
    var numEl = document.getElementById("activeStepNum");
    var prevBtn = document.getElementById("navPrev");
    var nextBtn = document.getElementById("navNext");
    if (titleEl) titleEl.textContent = stepTitles[currentStep] || "";
    if (subEl) subEl.textContent = stepSubs[currentStep] || "";
    if (numEl) numEl.textContent = currentStep + 1;
    if (prevBtn) prevBtn.disabled = currentStep === 0;
    if (nextBtn) {
        if (currentStep === totalSteps - 1) {
            nextBtn.innerHTML = "&#10003;";
            nextBtn.title = "Finish";
        } else {
            nextBtn.innerHTML = "&#8250;";
            nextBtn.title = "Next";
        }
    }
    document.querySelectorAll(".step-panel").forEach(function(p) { p.classList.remove("active"); });
    var panel = document.getElementById("step" + currentStep);
    if (panel) panel.classList.add("active");
    updateBottomNav();
    if (currentStep === 5) calculateSalary();
}

function updateBottomNav() {
    var bPrev = document.getElementById("bottomPrev");
    var bNext = document.getElementById("bottomNext");
    if (bPrev) {
        bPrev.style.visibility = currentStep === 0 ? "hidden" : "visible";
    }
    if (bNext) {
        if (currentStep === totalSteps - 1) {
            bNext.textContent = "Create Employee";
            bNext.setAttribute("onclick", "submitAll()");
        } else {
            bNext.innerHTML = "Next &rarr;";
            bNext.setAttribute("onclick", "nextStep()");
        }
    }
}

function goToStep(idx) {
    if (idx < 0 || idx >= totalSteps) return;
    currentStep = idx;
    updateStepBar();
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function nextStep() {
    var panel = document.getElementById("step" + currentStep);
    var reqFields = panel.querySelectorAll("[required]");
    for (var i = 0; i < reqFields.length; i++) {
        if (!reqFields[i].value) {
            reqFields[i].focus();
            showAlert("Please fill in all required fields", "error");
            return;
        }
    }
    goToStep(currentStep + 1);
}

function prevStep() {
    goToStep(currentStep - 1);
}

function submitEmployee() {
    var form = document.getElementById("employeeForm");
    var data = {};
    var inputs = form.querySelectorAll("input, select, textarea");
    for (var i = 0; i < inputs.length; i++) {
        var el = inputs[i];
        if (el.name) data[el.name] = el.value;
    }
    data.offer_id = offerId;
    return new Promise(function(resolve, reject) {
        apiPost("ajax/save.php", data, function(res) {
            if (res.success) resolve(res);
            else reject(res);
        }, function(err) {
            reject(err);
        });
    });
}

function submitSalaryData() {
    var salaryFields = [
        "salary_type","basic_salary","commission","consolidated_salary",
        "house_rent","dearness_allowance","contribution","entertainment",
        "special_allowance","convenience","utility_allowance","children_edu_allowance",
        "responsibility_allowance","superintendent_allowance","servant_allowance",
        "transport_allowance","fuel_allowance","comstry_allowance","marketing_allowance",
        "realestate_allowance","car_driver_allowance","ta","da","others",
        "other_allowance","food_allowance","mobile_allowance","medical_allowance",
        "mobile_ceiling","bonus_applicable","overtime_applicable","ot_weekend_applicable",
        "ot_holiday_applicable","pf","sm_money","income_tax","group_insurance",
        "cfund","cash_bank","ac_no","cash_amt","bank_amt","branch_info",
        "security_amount","cooperative_share","motorcycle_install","fixed_tada",
        "gross_salary","total_salary","total_payable","m_income_tex","bank_name",
        "pf_company","tax_status","pf_status","cash","pf_applicable","pf_deductible"
    ];
    var data = { offer_id: offerId };
    salaryFields.forEach(function(f) {
        var el = document.getElementById(f);
        if (el) data[f] = el.value;
    });
    return new Promise(function(resolve, reject) {
        apiPost("ajax/save_salary.php", data, function(res) {
            if (res.success) resolve(res);
            else reject(res);
        }, function(err) {
            reject(err);
        });
    });
}

function submitAll() {
    submitEmployee().then(function() {
        return submitSalaryData();
    }).then(function() {
        showAlert("Employee & salary created! Redirecting...", "success");
        setTimeout(function() { window.location.href = "view.php?offer_id=" + offerId; }, 1200);
    }).catch(function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    updateStepBar();
    loadCandidate();
});
EOSCRIPT;
$pageScripts = str_replace('__OFFER_ID__', $offerId, $pageScripts);
ob_start();
?>
<div id="formContent">
    <div class="rp-top">
        <h1>Create Employee</h1>
        <div class="rp-actions">
            <a href="list.php" class="btn btn-outline btn-sm">&larr; Back to List</a>
        </div>
    </div>

    <div class="rp-card">
        <div class="step-top-bar">
            <div class="step-indicators">
                <div class="step-dot-wrap"><div class="step-dot active" id="dot0" onclick="goToStep(0)"><div class="dot-circle"></div>Personal</div></div>
                <div class="step-dot-line" id="line0"></div>
                <div class="step-dot-wrap"><div class="step-dot" id="dot1" onclick="goToStep(1)"><div class="dot-circle"></div>Employment</div></div>
                <div class="step-dot-line" id="line1"></div>
                <div class="step-dot-wrap"><div class="step-dot" id="dot2" onclick="goToStep(2)"><div class="dot-circle"></div>Address</div></div>
                <div class="step-dot-line" id="line2"></div>
                <div class="step-dot-wrap"><div class="step-dot" id="dot3" onclick="goToStep(3)"><div class="dot-circle"></div>Family</div></div>
                <div class="step-dot-line" id="line3"></div>
                <div class="step-dot-wrap"><div class="step-dot" id="dot4" onclick="goToStep(4)"><div class="dot-circle"></div>Other</div></div>
                <div class="step-dot-line" id="line4"></div>
                <div class="step-dot-wrap"><div class="step-dot" id="dot5" onclick="goToStep(5)"><div class="dot-circle"></div>Salary</div></div>
            </div>

            <div class="step-active-bar">
                <div class="step-active-nav">
                    <button type="button" class="nav-btn" id="navPrev" onclick="prevStep()" disabled>&lsaquo;</button>
                </div>
                <div class="step-active-num" id="activeStepNum">1</div>
                <div class="step-active-info">
                    <div class="step-title" id="activeStepTitle">Personal Info</div>
                    <div class="step-sub" id="activeStepSub">Basic details & identity</div>
                </div>
                <div class="step-active-nav">
                    <button type="button" class="nav-btn" id="navNext" onclick="nextStep()">&#8250;</button>
                </div>
            </div>
        </div>

        <form id="employeeForm" autocomplete="off">

        <!-- ===== STEP 1: Personal Info ===== -->
        <div class="step-panel active" id="step0">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon blue">&#x1F464;</span> Basic Information</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>First Name <span class="req">*</span></label><input type="text" class="form-control" name="PBI_NAME" id="PBI_NAME" required></div>
                    <div class="form-group"><label>Last Name</label><input type="text" class="form-control" name="last_name" id="last_name"></div>
                    <div class="form-group"><label>Father's Name</label><input type="text" class="form-control" name="PBI_FATHER_NAME" id="PBI_FATHER_NAME"></div>
                    <div class="form-group"><label>Mother's Name</label><input type="text" class="form-control" name="PBI_MOTHER_NAME" id="PBI_MOTHER_NAME"></div>
                    <div class="form-group"><label>Date of Birth</label><input type="date" class="form-control" name="PBI_DOB" id="PBI_DOB"></div>
                    <div class="form-group"><label>Gender</label><select class="form-control" name="PBI_SEX" id="PBI_SEX"><option value="">-- Select --</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option></select></div>
                    <div class="form-group"><label>Marital Status</label><select class="form-control" name="PBI_MARITAL_STA" id="PBI_MARITAL_STA"><option value="">-- Select --</option><option value="Single">Single</option><option value="Married">Married</option><option value="Divorced">Divorced</option><option value="Widowed">Widowed</option></select></div>
                    <div class="form-group"><label>Religion</label><select class="form-control" name="PBI_RELIGION" id="PBI_RELIGION"><option value="">-- Select --</option><option value="Islam">Islam</option><option value="Hindu">Hindu</option><option value="Buddhist">Buddhist</option><option value="Christian">Christian</option><option value="Other">Other</option></select></div>
                    <div class="form-group"><label>Nationality</label><input type="text" class="form-control" name="PBI_NATIONALITY" id="PBI_NATIONALITY" value="Bangladeshi"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F4CB;</span> Identity & Contact</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>NID Number</label><input type="text" class="form-control" name="nid" id="nid"></div>
                    <div class="form-group"><label>Blood Group</label><select class="form-control" name="PBI_BLOOD_GROUP" id="PBI_BLOOD_GROUP"><option value="">-- Select --</option><option value="A+">A+</option><option value="A-">A-</option><option value="B+">B+</option><option value="B-">B-</option><option value="O+">O+</option><option value="O-">O-</option><option value="AB+">AB+</option><option value="AB-">AB-</option></select></div>
                    <div class="form-group"><label>Mobile Number</label><input type="text" class="form-control" name="PBI_MOBILE" id="PBI_MOBILE"></div>
                    <div class="form-group"><label>Phone (Office)</label><input type="text" class="form-control" name="PBI_PHONE" id="PBI_PHONE"></div>
                    <div class="form-group"><label>Email</label><input type="email" class="form-control" name="PBI_EMAIL" id="PBI_EMAIL"></div>
                    <div class="form-group"><label>Alternate Email</label><input type="email" class="form-control" name="PBI_EMAIL_ALT" id="PBI_EMAIL_ALT"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon purple">&#x1F3CB;</span> Physical & Personal</div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>Height</label><input type="text" class="form-control" name="highte" id="highte" placeholder="e.g. 5'8&quot;"></div>
                    <div class="form-group"><label>Weight</label><input type="text" class="form-control" name="weight" id="weight" placeholder="e.g. 70 kg"></div>
                    <div class="form-group"><label>Birth Reg No</label><input type="text" class="form-control" name="birth_reg_no" id="birth_reg_no"></div>
                    <div class="form-group"><label>Hobbies</label><input type="text" class="form-control" name="hobbies" id="hobbies"></div>
                </div>
            </div>
        </div>

        <!-- ===== STEP 2: Employment ===== -->
        <div class="step-panel" id="step1">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon blue">&#x1F4BC;</span> Job Information</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Department <span class="req">*</span></label><select class="form-control" name="DEPT_ID" id="DEPT_ID" required><option value="">-- Select --</option><?php $pdo = getDB(); foreach ($pdo->query("SELECT id, name FROM departments ORDER BY name")->fetchAll() as $d) echo '<option value="'.$d['id'].'">'.htmlspecialchars($d['name']).'</option>'; ?></select></div>
                    <div class="form-group"><label>Designation <span class="req">*</span></label><select class="form-control" name="DESG_ID" id="DESG_ID"><option value="">-- Select --</option><?php foreach ($pdo->query("SELECT id, name FROM designations ORDER BY name")->fetchAll() as $ds) echo '<option value="'.$ds['id'].'">'.htmlspecialchars($ds['name']).'</option>'; ?></select></div>
                    <div class="form-group"><label>Job Title</label><input type="text" class="form-control" name="job_title" id="job_title"></div>
                    <div class="form-group"><label>Designation (Text)</label><input type="text" class="form-control" name="PBI_DESIGNATION" id="PBI_DESIGNATION"></div>
                    <div class="form-group"><label>Joining Designation</label><input type="text" class="form-control" name="joining_designation" id="joining_designation"></div>
                    <div class="form-group"><label>Payroll Designation</label><input type="text" class="form-control" name="payroll_designation" id="payroll_designation"></div>
                    <div class="form-group"><label>Joining Date <span class="req">*</span></label><input type="date" class="form-control" name="PBI_DOJ" id="PBI_DOJ" required></div>
                    <div class="form-group"><label>Due Date of Joining</label><input type="date" class="form-control" name="PBI_DUE_DOJ" id="PBI_DUE_DOJ"></div>
                    <div class="form-group"><label>Confirmation Date</label><input type="date" class="form-control" name="PBI_DOC" id="PBI_DOC"></div>
                    <div class="form-group"><label>Probation Duration (Months)</label><input type="number" class="form-control" name="PBI_DURATION" id="PBI_DURATION" value="6"></div>
                    <div class="form-group"><label>Service Length</label><input type="text" class="form-control" name="PBI_SERVICE_LENGTH" id="PBI_SERVICE_LENGTH"></div>
                    <div class="form-group"><label>Job Description</label><input type="text" class="form-control" name="job_description" id="job_description"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F4CA;</span> Classification & Grade</div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>Employee Type</label><select class="form-control" name="employee_type" id="employee_type"><option value="Non Roster">Non Roster</option><option value="Roster">Roster</option></select></div>
                    <div class="form-group"><label>Employment Type</label><select class="form-control" name="EMPLOYMENT_TYPE" id="EMPLOYMENT_TYPE"><option value="">-- Select --</option><option value="Casual">Casual</option><option value="Apprentice">Apprentice</option><option value="Probationer" selected>Probationer</option><option value="Temporary">Temporary</option><option value="Replace">Replace</option><option value="Permanent">Permanent</option><option value="Seasonal">Seasonal</option><option value="Contractual">Contractual</option><option value="Regular">Regular</option></select></div>
                    <div class="form-group"><label>Contract Type</label><input type="text" class="form-control" name="PBI_CON_TYPE" id="PBI_CON_TYPE"></div>
                    <div class="form-group"><label>Organization</label><input type="number" class="form-control" name="PBI_ORG" id="PBI_ORG" value="1"></div>
                    <div class="form-group"><label>Grade</label><input type="number" class="form-control" name="grade" id="grade"></div>
                    <div class="form-group"><label>Level</label><input type="text" class="form-control" name="level" id="level"></div>
                    <div class="form-group"><label>Class</label><input type="text" class="form-control" name="class" id="class"></div>
                    <div class="form-group"><label>Section</label><input type="text" class="form-control" name="section" id="section"></div>
                    <div class="form-group"><label>Cost Center</label><input type="text" class="form-control" name="cost_center" id="cost_center"></div>
                    <div class="form-group"><label>Cost Type</label><input type="text" class="form-control" name="cost_type" id="cost_type"></div>
                    <div class="form-group"><label>Salary Schedule</label><input type="number" class="form-control" name="salary_schedule" id="salary_schedule"></div>
                    <div class="form-group"><label>Leave Rule ID</label><input type="number" class="form-control" name="LEAVE_RULE_ID" id="LEAVE_RULE_ID"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x23F0;</span> Schedule & Attendance</div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>Schedule Type</label><select class="form-control" name="schedule_type" id="schedule_type"><option value="Regular">Regular</option><option value="Roster">Roster</option></select></div>
                    <div class="form-group"><label>Punch Type</label><select class="form-control" name="punch_type" id="punch_type"><option value="Regular">Regular</option><option value="Single">Single</option><option value="No Punch">No Punch</option></select></div>
                    <div class="form-group"><label>Define Schedule</label><input type="text" class="form-control" name="define_schedule" id="define_schedule" placeholder="e.g. 9:00 AM - 6:00 PM"></div>
                    <div class="form-group"><label>Machine / Card ID</label><input type="text" class="form-control" name="MACHINE_ID" id="MACHINE_ID"></div>
                </div>
            </div>
        </div>

        <!-- ===== STEP 3: Address ===== -->
        <div class="step-panel" id="step2">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon blue">&#x1F4CD;</span> Present Address</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>House No</label><input type="text" class="form-control" name="pre_house_no" id="pre_house_no"></div>
                    <div class="form-group"><label>Flat</label><input type="text" class="form-control" name="pre_flat" id="pre_flat"></div>
                    <div class="form-group"><label>Road No</label><input type="text" class="form-control" name="pre_road_no" id="pre_road_no"></div>
                    <div class="form-group"><label>Block No</label><input type="text" class="form-control" name="pre_block_no" id="pre_block_no"></div>
                    <div class="form-group"><label>Police Station</label><input type="text" class="form-control" name="pre_ps" id="pre_ps"></div>
                    <div class="form-group"><label>District</label><input type="text" class="form-control" name="pre_district" id="pre_district"></div>
                </div>
                <div class="form-group" style="margin-top:14px"><label>Present Address (Full)</label><textarea class="form-control" name="PBI_PRESENT_ADD" id="PBI_PRESENT_ADD" rows="2"></textarea></div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon purple">&#x1F3E0;</span> Permanent Address</div>
                <div class="form-group"><label>Permanent Address</label><textarea class="form-control" name="PBI_PERMANENT_ADD" id="PBI_PERMANENT_ADD" rows="2"></textarea></div>
            </div>
        </div>

        <!-- ===== STEP 4: Family & Nominee ===== -->
        <div class="step-panel" id="step3">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F468;</span> Spouse & Nominee</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Spouse Name</label><input type="text" class="form-control" name="spouse_name" id="spouse_name"></div>
                    <div class="form-group"><label>Nominee</label><input type="text" class="form-control" name="nominee" id="nominee"></div>
                    <div class="form-group"><label>Nominee Relation</label><input type="text" class="form-control" name="nominee_relation" id="nominee_relation"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x1F476;</span> Child 1</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Name</label><input type="text" class="form-control" name="child1_name" id="child1_name"></div>
                    <div class="form-group"><label>DOB</label><input type="text" class="form-control" name="child1_dob" id="child1_dob" placeholder="YYYY-MM-DD"></div>
                    <div class="form-group"><label>Details</label><input type="text" class="form-control" name="child1_details" id="child1_details"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x1F476;</span> Child 2</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Name</label><input type="text" class="form-control" name="child2_name" id="child2_name"></div>
                    <div class="form-group"><label>DOB</label><input type="text" class="form-control" name="child2_dob" id="child2_dob" placeholder="YYYY-MM-DD"></div>
                    <div class="form-group"><label>Details</label><input type="text" class="form-control" name="child2_details" id="child2_details"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x1F476;</span> Child 3</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Name</label><input type="text" class="form-control" name="child3_name" id="child3_name"></div>
                    <div class="form-group"><label>DOB</label><input type="text" class="form-control" name="child3_dob" id="child3_dob" placeholder="YYYY-MM-DD"></div>
                    <div class="form-group"><label>Details</label><input type="text" class="form-control" name="child3_details" id="child3_details"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x1F476;</span> Child 4</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Name</label><input type="text" class="form-control" name="child4_name" id="child4_name"></div>
                    <div class="form-group"><label>DOB</label><input type="text" class="form-control" name="child4_dob" id="child4_dob" placeholder="YYYY-MM-DD"></div>
                    <div class="form-group"><label>Details</label><input type="text" class="form-control" name="child4_details" id="child4_details"></div>
                </div>
            </div>
        </div>

        <!-- ===== STEP 5: Other Details ===== -->
        <div class="step-panel" id="step4">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon teal">&#x1F4CD;</span> Location & Workstation</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Zone</label><input type="text" class="form-control" name="PBI_ZONE" id="PBI_ZONE"></div>
                    <div class="form-group"><label>Area</label><input type="text" class="form-control" name="PBI_AREA" id="PBI_AREA"></div>
                    <div class="form-group"><label>Branch</label><input type="text" class="form-control" name="PBI_BRANCH" id="PBI_BRANCH"></div>
                    <div class="form-group"><label>Region</label><input type="number" class="form-control" name="PBI_REGION" id="PBI_REGION"></div>
                    <div class="form-group"><label>Work Station</label><input type="text" class="form-control" name="PBI_WORK_STATION" id="PBI_WORK_STATION"></div>
                    <div class="form-group"><label>Line</label><input type="text" class="form-control" name="line" id="line"></div>
                    <div class="form-group"><label>Territory</label><input type="number" class="form-control" name="territory" id="territory"></div>
                    <div class="form-group"><label>Function</label><input type="text" class="form-control" name="PBI_FUNCTION" id="PBI_FUNCTION"></div>
                    <div class="form-group"><label>Job Location ID</label><input type="number" class="form-control" name="JOB_LOC_ID" id="JOB_LOC_ID"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon blue">&#x1F465;</span> Reporting & Incharge</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Incharge ID</label><input type="number" class="form-control" name="incharge_id" id="incharge_id"></div>
                    <div class="form-group"><label>Incharge ID 2</label><input type="number" class="form-control" name="incharge_id_2" id="incharge_id_2"></div>
                    <div class="form-group"><label>Cell No Tracking</label><input type="text" class="form-control" name="cell_no_traking" id="cell_no_traking"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F4C5;</span> Working Days</div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>Saturday</label><input type="text" class="form-control" name="Saturday" id="Saturday" placeholder="e.g. 9AM-6PM"></div>
                    <div class="form-group"><label>Sunday</label><input type="text" class="form-control" name="Sunday" id="Sunday"></div>
                    <div class="form-group"><label>Monday</label><input type="text" class="form-control" name="Monday" id="Monday"></div>
                    <div class="form-group"><label>Tuesday</label><input type="text" class="form-control" name="Tuesday" id="Tuesday"></div>
                    <div class="form-group"><label>Wednesday</label><input type="text" class="form-control" name="Wednesday" id="Wednesday"></div>
                    <div class="form-group"><label>Thursday</label><input type="text" class="form-control" name="Thursday" id="Thursday"></div>
                    <div class="form-group"><label>Friday</label><input type="text" class="form-control" name="Friday" id="Friday" value="Off"></div>
                </div>
            </div>
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon purple">&#x1F4DD;</span> Notes</div>
                <div class="form-group"><label>Note</label><textarea class="form-control" name="note" id="note" rows="3" placeholder="Any additional notes about this employee..."></textarea></div>
            </div>
        </div>

        <!-- ===== STEP 6: Salary Setup ===== -->
        <div class="step-panel" id="step5">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon blue">&#x1F464;</span> Candidate Information</div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>Candidate</label><input type="text" class="form-control" id="sal_candidate_name" disabled></div>
                    <div class="form-group"><label>Job Title</label><input type="text" class="form-control" id="sal_job_title" disabled></div>
                    <div class="form-group"><label>Offered Salary</label><input type="text" class="form-control" id="sal_offered_salary" disabled></div>
                    <div class="form-group"><label>Gender</label><select class="form-control" id="gender" onchange="calculateSalary()"><option value="male">Male</option><option value="female">Female</option></select></div>
                </div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F4B0;</span> Salary Breakdown</div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Gross Salary <span style="color:var(--danger)">*</span></label><input type="number" class="form-control" name="gross_salary" id="gross_salary" placeholder="Enter gross salary" oninput="calculateSalary()" style="font-weight:600;font-size:15px"></div>
                    <div class="form-group"><label>Basic Salary</label><input type="number" class="form-control" name="basic_salary" id="basic_salary" placeholder="Auto-calculated" readonly></div>
                    <div class="form-group"><label>House Rent</label><input type="number" class="form-control" name="house_rent" id="house_rent" placeholder="Auto-calculated" readonly></div>
                    <div class="form-group"><label>Medical Allowance</label><input type="number" class="form-control" name="medical_allowance" id="medical_allowance" placeholder="Auto-calculated" readonly></div>
                    <div class="form-group"><label>Conveyance Allowance</label><input type="number" class="form-control" name="convenience" id="convenience" placeholder="Auto-calculated" readonly></div>
                    <div class="form-group"><label>Total Salary</label><input type="number" class="form-control" name="total_salary" id="total_salary" placeholder="Auto-calculated" readonly></div>
                </div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon red">&#x1F4C9;</span> Deductions & Overtime</div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>Personal PF</label><input type="number" class="form-control" name="pf" id="pf" placeholder="0" oninput="calculateTotalPayable()"></div>
                    <div class="form-group"><label>Company PF</label><input type="number" class="form-control" name="pf_company" id="pf_company" placeholder="0"></div>
                    <div class="form-group"><label>PF Deductible</label><input type="number" class="form-control" name="pf_deductible" id="pf_deductible" placeholder="0" oninput="calculateTotalPayable()"></div>
                    <div class="form-group"><label>Monthly Income Tax</label><input type="number" class="form-control" name="m_income_tex" id="m_income_tex" placeholder="Auto-calculated" readonly></div>
                    <div class="form-group"><label>PF Applicable</label><select class="form-control" name="pf_applicable" id="pf_applicable"><option value="No">No</option><option value="Yes">Yes</option></select></div>
                    <div class="form-group"><label>PF Status</label><input type="text" class="form-control" name="pf_status" id="pf_status"></div>
                    <div class="form-group"><label>Tax Status</label><input type="text" class="form-control" name="tax_status" id="tax_status"></div>
                    <div class="form-group"><label>Income Tax</label><input type="number" class="form-control" name="income_tax" id="income_tax" step="0.01"></div>
                    <div class="form-group"><label>Group Insurance</label><input type="number" class="form-control" name="group_insurance" id="group_insurance" step="0.01"></div>
                    <div class="form-group"><label>C Fund</label><input type="number" class="form-control" name="cfund" id="cfund" step="0.01"></div>
                    <div class="form-group"><label>SM Money</label><input type="number" class="form-control" name="sm_money" id="sm_money" step="0.01"></div>
                    <div class="form-group"><label>Security Amount</label><input type="number" class="form-control" name="security_amount" id="security_amount" step="0.01"></div>
                    <div class="form-group"><label>Cooperative Share</label><input type="number" class="form-control" name="cooperative_share" id="cooperative_share"></div>
                    <div class="form-group"><label>Motorcycle Install</label><input type="number" class="form-control" name="motorcycle_install" id="motorcycle_install" step="0.01"></div>
                    <div class="form-group"><label>Fixed TADA</label><input type="number" class="form-control" name="fixed_tada" id="fixed_tada" step="0.01"></div>
                </div>
                <div class="form-row form-row-3" style="margin-top:14px">
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:400;cursor:pointer"><input type="checkbox" id="ot_out"> OT (Out)</label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:400;cursor:pointer"><input type="checkbox" id="ot_holiday"> OT Holiday</label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:400;cursor:pointer"><input type="checkbox" id="ot_weekend"> OT Weekend</label>
                </div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon teal">&#x1F4CA;</span> Summary</div>
                <div class="form-row form-row-2">
                    <div class="form-group"><label>Total Payable</label><input type="number" class="form-control" name="total_payable" id="total_payable" placeholder="0" readonly></div>
                    <div class="form-group"><label>Salary Type</label><input type="text" class="form-control" name="salary_type" id="salary_type" placeholder="e.g. Consolidated"></div>
                    <div class="form-group"><label>Consolidated Salary</label><input type="number" class="form-control" name="consolidated_salary" id="consolidated_salary" step="0.01"></div>
                </div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon purple">&#x1F4B5;</span> Other Allowances</div>
                <div class="form-row form-row-4">
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

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x2699;</span> Totals & Overtime</div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>Bonus Applicable</label><select class="form-control" name="bonus_applicable" id="bonus_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                    <div class="form-group"><label>Overtime Applicable</label><select class="form-control" name="overtime_applicable" id="overtime_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                    <div class="form-group"><label>OT Weekend</label><select class="form-control" name="ot_weekend_applicable" id="ot_weekend_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                    <div class="form-group"><label>OT Holiday</label><select class="form-control" name="ot_holiday_applicable" id="ot_holiday_applicable"><option value="">--</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                </div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F3E6;</span> Bank & Payment</div>
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
        </div>

        </form>

        <div class="step-nav">
            <button type="button" class="btn btn-outline" id="bottomPrev" onclick="prevStep()">&larr; Previous</button>
            <button type="button" class="btn btn-primary" id="bottomNext" onclick="nextStep()">Next &rarr;</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
