<?php
$basePath    = '..';
$pageTitle   = 'Confirmation - Eligible Employees';
$activePage  = 'confirmation_list';
$breadcrumb  = 'Home / Confirmation / Eligible Employees';
$pageStyles  = '';
$pageScripts = <<<'EOSCRIPT'
function renderEligible() {
    apiGet("ajax/list_eligible.php", null, function(res) {
        var list = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("eligibleBody");
        if (list.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"8\"><div class='empty-state'><div class='empty-icon'>&#128100;</div><p>No employees with completed probation.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < list.length; i++) {
            var e = list[i];
            var action = "";
            if (e.confirmation_status) {
                var cls = e.confirmation_status === "Sent" ? "badge-success" : e.confirmation_status === "Approved" ? "badge-in-progress" : e.confirmation_status === "Rejected" ? "badge-danger" : "badge";
                action = "<span class=\"badge " + cls + "\">" + e.confirmation_status + "</span>";
                if (e.confirmation_status === "Sent") {
                    action += " <a class=\"btn btn-sm btn-outline\" href=\"confirmation_letters.php\">View</a>";
                } else {
                    action += " <a class=\"btn btn-sm btn-outline\" href=\"generate.php?employee_id=" + e.employee_id + "&appraisal_id=" + e.appraisal_id + "\">View</a>";
                }
            } else {
                action = "<a class=\"btn btn-sm btn-primary\" href=\"generate.php?employee_id=" + e.employee_id + "&appraisal_id=" + e.appraisal_id + "\">Generate Letter</a>";
            }
            html += "<tr>" +
                "<td><strong>" + (e.employee_name || "N/A") + "</strong><br><small>" + (e.employee_code || "") + "</small></td>" +
                "<td>" + (e.job_title || "N/A") + "</td>" +
                "<td>" + (e.dept_name || "N/A") + "</td>" +
                "<td>" + (e.designation_name || "N/A") + "</td>" +
                "<td>" + (e.employment_type || "Probationer") + "</td>" +
                "<td>" + (e.end_date || "-") + "</td>" +
                "<td>" + (e.overall_score != null ? e.overall_score.toFixed(1) : "-") + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

document.addEventListener("DOMContentLoaded", function() { renderEligible(); });
EOSCRIPT;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Eligible for Confirmation</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="eligibleSearch" placeholder="Search..." onkeyup="filterTable('eligibleSearch','eligibleTable',0)">
            </div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="eligibleTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Type</th>
                        <th>Probation End</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="eligibleBody"></tbody>
            </table>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
