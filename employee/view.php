<?php
$basePath    = '..';
$pageTitle   = 'View Employee';
$activePage  = 'create_employee';
$breadcrumb  = 'Home / Employee / View Employee';
$offerId     = (int)($_GET['offer_id'] ?? 0);
$pageCss = 'css/page_css/view.css';
$pageScripts = <<<EOSCRIPT
var offerId = {$offerId};
var empData = null;

function loadEmployee() {
    if (!offerId) {
        document.getElementById("viewContent").innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x26A0;</div><h3>No offer ID provided</h3><p><a href=\"list.php\">Go back to list</a></p></div>";
        return;
    }
    apiGet("ajax/get_candidate.php?offer_id=" + offerId, null, function(res) {
        if (!res.success || !res.data) {
            document.getElementById("viewContent").innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x274C;</div><h3>Data not found</h3><p><a href=\"list.php\">Go back to list</a></p></div>";
            return;
        }
        if (!res.data.employee_exists) {
            window.location.href = "create.php?offer_id=" + offerId;
            return;
        }
        empData = res.data;
        renderView();
    });
}

function v(val) { return (val !== null && val !== undefined && val !== "0" && val !== "0.00" && val !== "") ? val : "\u2014"; }
function t(val) { return v(val) !== "\u2014" ? val : '<span style="color:var(--gray-400)">\u2014</span>'; }

function switchViewTab(tab) {
    document.querySelectorAll(".scr-tab").forEach(function(t) { t.classList.remove("active"); });
    document.querySelectorAll("#viewContent .tab-panel").forEach(function(p) { p.classList.remove("active"); });
    document.getElementById("vtab" + tab).classList.add("active");
    document.getElementById("vpanel" + tab).classList.add("active");
}

function renderView() {
    var o = empData.offer;
    var emp = empData.employee;

    var html = '<div class="rp-top">';
    html += '<h1>' + v(o.first_name) + " " + v(o.last_name) + ' <small>Employee Code: <strong>' + v(emp.PBI_CODE) + '</strong></small></h1>';
    html += '<div class="rp-actions"><a href="list.php" class="btn btn-outline btn-sm">&larr; Back to List</a></div>';
    html += '</div>';

    html += '<div class="rp-card">';

    html += '<div class="scr-tabs">';
    html += '<button class="scr-tab active" id="vtabPersonal" onclick="switchViewTab(\'Personal\')">Personal Info</button>';
    html += '<button class="scr-tab" id="vtabEmployment" onclick="switchViewTab(\'Employment\')">Employment</button>';
    html += '<button class="scr-tab" id="vtabAddress" onclick="switchViewTab(\'Address\')">Address</button>';
    html += '<button class="scr-tab" id="vtabFamily" onclick="switchViewTab(\'Family\')">Family & Nominee</button>';
    html += '<button class="scr-tab" id="vtabOther" onclick="switchViewTab(\'Other\')">Other Details</button>';
    html += '<button class="scr-tab" id="vtabSalary" onclick="switchViewTab(\'Salary\')">Salary</button>';
    html += '</div>';

    // Personal
    html += '<div class="tab-panel active" id="vpanelPersonal">';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon blue">&#x1F464;</span> Basic Information</div>';
    html += '<div class="vc-grid">';
    html += infoItem("First Name", t(emp.PBI_NAME));
    html += infoItem("Last Name", t(emp.last_name));
    html += infoItem("Father's Name", t(emp.PBI_FATHER_NAME));
    html += infoItem("Mother's Name", t(emp.PBI_MOTHER_NAME));
    html += infoItem("Date of Birth", t(emp.PBI_DOB));
    html += infoItem("Gender", t(emp.PBI_SEX));
    html += infoItem("Marital Status", t(emp.PBI_MARITAL_STA));
    html += infoItem("Religion", t(emp.PBI_RELIGION));
    html += infoItem("Nationality", t(emp.PBI_NATIONALITY));
    html += '</div></div>';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon green">&#x1F4CB;</span> Identity & Contact</div>';
    html += '<div class="vc-grid">';
    html += infoItem("NID", t(emp.nid));
    html += infoItem("Blood Group", t(emp.PBI_BLOOD_GROUP));
    html += infoItem("Mobile", t(emp.PBI_MOBILE));
    html += infoItem("Phone", t(emp.PBI_PHONE));
    html += infoItem("Email", t(emp.PBI_EMAIL), 'span-2');
    html += infoItem("Alt Email", t(emp.PBI_EMAIL_ALT), 'span-2');
    html += '</div></div></div>';

    // Employment
    html += '<div class="tab-panel" id="vpanelEmployment">';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon blue">&#x1F4BC;</span> Job Information</div>';
    html += '<div class="vc-grid">';
    html += infoItem("Department", t(emp.PBI_DEPARTMENT ? emp.PBI_DEPARTMENT : o.dept_name));
    html += infoItem("Designation", t(emp.PBI_DESIGNATION ? emp.PBI_DESIGNATION : o.designation_name));
    html += infoItem("Job Title", t(emp.job_title));
    html += infoItem("Joining Date", t(emp.PBI_DOJ));
    html += infoItem("Due DOJ", t(emp.PBI_DUE_DOJ));
    html += infoItem("Employee Type", t(emp.employee_type));
    html += infoItem("Employment Type", t(emp.EMPLOYMENT_TYPE));
    html += infoItem("Duration", t(emp.PBI_DURATION));
    html += infoItem("Grade", t(emp.grade));
    html += infoItem("Level", t(emp.level));
    html += infoItem("Class", t(emp.class));
    html += infoItem("Section", t(emp.section));
    html += infoItem("Cost Center", t(emp.cost_center));
    html += infoItem("Payroll Designation", t(emp.payroll_designation));
    html += '</div></div>';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon orange">&#x23F0;</span> Schedule</div>';
    html += '<div class="vc-grid">';
    html += infoItem("Schedule Type", t(emp.schedule_type));
    html += infoItem("Punch Type", t(emp.punch_type));
    html += infoItem("Define Schedule", t(emp.define_schedule));
    html += '</div></div></div>';

    // Address
    html += '<div class="tab-panel" id="vpanelAddress">';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon blue">&#x1F4CD;</span> Present Address</div>';
    html += '<div class="vc-grid">';
    html += infoItem("House No", t(emp.pre_house_no));
    html += infoItem("Flat", t(emp.pre_flat));
    html += infoItem("Road No", t(emp.pre_road_no));
    html += infoItem("Block No", t(emp.pre_block_no));
    html += infoItem("Police Station", t(emp.pre_ps));
    html += infoItem("District", t(emp.pre_district));
    html += '</div>';
    html += infoItem("Full Present Address", t(emp.PBI_PRESENT_ADD), 'span-3');
    html += infoItem("Permanent Address", t(emp.PBI_PERMANENT_ADD), 'span-3');
    html += '</div></div>';

    // Family
    html += '<div class="tab-panel" id="vpanelFamily">';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon green">&#x1F468;</span> Spouse & Nominee</div>';
    html += '<div class="vc-grid">';
    html += infoItem("Spouse Name", t(emp.spouse_name));
    html += infoItem("Nominee", t(emp.nominee));
    html += infoItem("Nominee Relation", t(emp.nominee_relation));
    html += '</div></div>';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon orange">&#x1F476;</span> Children</div>';
    html += '<div class="vc-grid">';
    for (var i = 1; i <= 4; i++) {
        html += infoItem("Child " + i + " Name", t(emp["child" + i + "_name"]));
        html += infoItem("Child " + i + " DOB", t(emp["child" + i + "_dob"]));
        html += infoItem("Child " + i + " Details", t(emp["child" + i + "_details"]));
    }
    html += '</div></div></div>';

    // Other
    html += '<div class="tab-panel" id="vpanelOther">';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon teal">&#x1F4CD;</span> Location & Workstation</div>';
    html += '<div class="vc-grid">';
    html += infoItem("Zone", t(emp.PBI_ZONE));
    html += infoItem("Area", t(emp.PBI_AREA));
    html += infoItem("Branch", t(emp.PBI_BRANCH));
    html += infoItem("Region", t(emp.PBI_REGION));
    html += infoItem("Work Station", t(emp.PBI_WORK_STATION));
    html += infoItem("Line", t(emp.line));
    html += infoItem("Territory", t(emp.territory));
    html += infoItem("Function", t(emp.PBI_FUNCTION));
    html += '</div></div>';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon purple">&#x1F3CB;</span> Other Info</div>';
    html += '<div class="vc-grid">';
    html += infoItem("Height", t(emp.highte));
    html += infoItem("Weight", t(emp.weight));
    html += infoItem("Hobbies", t(emp.hobbies));
    html += infoItem("Birth Reg No", t(emp.birth_reg_no));
    html += infoItem("Job Description", t(emp.job_description));
    html += infoItem("Cost Type", t(emp.cost_type));
    html += '</div></div>';
    html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon green">&#x1F4C5;</span> Working Days</div>';
    html += '<div class="vc-grid">';
    var days = ["Friday","Saturday","Sunday","Monday","Tuesday","Wednesday","Thursday"];
    for (var d = 0; d < days.length; d++) {
        html += infoItem(days[d], t(emp[days[d]]));
    }
    html += '</div>';
    html += infoItem("Note", t(emp.note), 'span-3');
    html += '</div></div>';

    // Salary
    var sal = empData.salary;
    if (sal) {
        html += '<div class="tab-panel" id="vpanelSalary">';
        html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon blue">&#x1F464;</span> Salary Type & Base</div>';
        html += '<div class="vc-grid">';
        html += infoItem("Salary Type", t(sal.salary_type));
        html += infoItem("Gross Salary", t(sal.gross_salary));
        html += infoItem("Basic Salary", t(sal.basic_salary));
        html += infoItem("Consolidated Salary", t(sal.consolidated_salary));
        html += '</div></div>';
        html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon green">&#x1F4B0;</span> Allowances</div>';
        html += '<div class="vc-grid">';
        html += infoItem("House Rent", t(sal.house_rent));
        html += infoItem("Medical Allowance", t(sal.medical_allowance));
        html += infoItem("Convenience", t(sal.convenience));
        html += infoItem("Transport Allowance", t(sal.transport_allowance));
        html += infoItem("Fuel Allowance", t(sal.fuel_allowance));
        html += infoItem("Food Allowance", t(sal.food_allowance));
        html += infoItem("Mobile Allowance", t(sal.mobile_allowance));
        html += infoItem("Mobile Ceiling", t(sal.mobile_ceiling));
        html += infoItem("Special Allowance", t(sal.special_allowance));
        html += infoItem("Utility Allowance", t(sal.utility_allowance));
        html += infoItem("Entertainment", t(sal.entertainment));
        html += infoItem("Contribution", t(sal.contribution));
        html += infoItem("Dearness Allowance", t(sal.dearness_allowance));
        html += infoItem("Commission", t(sal.commission));
        html += infoItem("Children Edu Allowance", t(sal.children_edu_allowance));
        html += infoItem("Responsibility Allowance", t(sal.responsibility_allowance));
        html += infoItem("Superintendent Allowance", t(sal.superintendent_allowance));
        html += infoItem("Servant Allowance", t(sal.servant_allowance));
        html += infoItem("Comstry Allowance", t(sal.comstry_allowance));
        html += infoItem("Marketing Allowance", t(sal.marketing_allowance));
        html += infoItem("Realestate Allowance", t(sal.realestate_allowance));
        html += infoItem("Car Driver Allowance", t(sal.car_driver_allowance));
        html += infoItem("TA", t(sal.ta));
        html += infoItem("DA", t(sal.da));
        html += infoItem("Other Allowance", t(sal.other_allowance));
        html += infoItem("Others", t(sal.others));
        html += '</div></div>';
        html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon red">&#x1F4C9;</span> Deductions</div>';
        html += '<div class="vc-grid">';
        html += infoItem("PF (Employee)", t(sal.pf));
        html += infoItem("PF (Company)", t(sal.pf_company));
        html += infoItem("PF Applicable", t(sal.pf_applicable));
        html += infoItem("PF Deductible", t(sal.pf_deductible));
        html += infoItem("PF Status", t(sal.pf_status));
        html += infoItem("Income Tax", t(sal.income_tax));
        html += infoItem("Monthly Income Tax", t(sal.m_income_tex));
        html += infoItem("Tax Status", t(sal.tax_status));
        html += infoItem("Group Insurance", t(sal.group_insurance));
        html += infoItem("C Fund", t(sal.cfund));
        html += infoItem("SM Money", t(sal.sm_money));
        html += infoItem("Security Amount", t(sal.security_amount));
        html += infoItem("Cooperative Share", t(sal.cooperative_share));
        html += infoItem("Motorcycle Install", t(sal.motorcycle_install));
        html += infoItem("Fixed TADA", t(sal.fixed_tada));
        html += '</div></div>';
        html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon teal">&#x1F4CA;</span> Totals & Overtime</div>';
        html += '<div class="vc-grid">';
        html += infoItem("Total Salary", t(sal.total_salary));
        html += infoItem("Total Payable", t(sal.total_payable));
        html += infoItem("Bonus Applicable", t(sal.bonus_applicable));
        html += infoItem("Overtime Applicable", t(sal.overtime_applicable));
        html += infoItem("OT Weekend", t(sal.ot_weekend_applicable));
        html += infoItem("OT Holiday", t(sal.ot_holiday_applicable));
        html += '</div></div>';
        html += '<div class="sb-section"><div class="sb-section-title"><span class="sb-icon green">&#x1F3E6;</span> Bank & Payment</div>';
        html += '<div class="vc-grid">';
        html += infoItem("Cash / Bank", t(sal.cash_bank));
        html += infoItem("Bank Name", t(sal.bank_name));
        html += infoItem("Branch Info", t(sal.branch_info));
        html += infoItem("Account No", t(sal.ac_no));
        html += infoItem("Cash Amount", t(sal.cash_amt));
        html += infoItem("Bank Amount", t(sal.bank_amt));
        html += infoItem("Cash", t(sal.cash));
        html += '</div></div>';
        html += '</div>';
    } else {
        html += '<div class="tab-panel" id="vpanelSalary">';
        html += '<div class="rp-empty"><div class="rp-empty-icon">&#x1F4B0;</div><h3>No salary information recorded yet</h3><p><a href="salary_information.php?offer_id=' + offerId + '">Add Salary Info</a></p></div>';
        html += '</div>';
    }

    html += '</div>';
    document.getElementById("viewContent").innerHTML = html;
}

function infoItem(label, value, span) {
    var cls = span ? " vc-item span-" + span : " vc-item";
    return '<div class="' + cls + '"><label>' + label + '</label><span>' + value + '</span></div>';
}

document.addEventListener("DOMContentLoaded", function() {
    loadEmployee();
});
EOSCRIPT;
ob_start();
?>
<div id="viewContent">
    <div class="rp-top">
        <h1>View Employee</h1>
    </div>
    <div class="rp-card">
        <div class="rp-empty"><div class="rp-empty-icon">&#x23F3;</div><p>Loading employee data...</p></div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
