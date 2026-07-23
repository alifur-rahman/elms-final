<?php
$basePath    = '..';
$pageTitle   = 'Create Employee';
$activePage  = 'create_employee';
$breadcrumb  = 'Home / Employee / Create Employee';
$pageStyles  = '
.empty-state { text-align:center; padding:48px 24px; color:var(--gray-500) }
.empty-state .empty-icon { font-size:48px; margin-bottom:12px }
.empty-state p { font-size:14px }
';
$pageScripts = <<<'EOSCRIPT'
function renderAcceptedOffers() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=Accepted", null, function(res) {
        var offers = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("empTableBody");
        if (offers.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No candidates with accepted offers. Once a candidate accepts their offer, they will appear here for employee creation.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < offers.length; i++) {
            var o = offers[i];
            var empStatus = o.employee_created ? "Created" : "Pending";
            var empClass = o.employee_created ? "badge badge-success" : "badge badge-in-progress";
            var action = "";
            if (o.employee_created) {
                action = "<a class=\"btn btn-sm btn-outline\" href=\"view.php?offer_id=" + o.id + "\">View</a>";
            } else {
                action = "<a class=\"btn btn-sm btn-primary\" href=\"create.php?offer_id=" + o.id + "\">Create Employee</a>";
            }
            html += "<tr>" +
                "<td><strong>" + (o.candidate_name || o.first_name + " " + o.last_name) + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.dept_name || "N/A") + "</td>" +
                "<td>" + (o.designation_name || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderAcceptedOffers();
});
EOSCRIPT;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Create Employee</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="empSearch" placeholder="Search..." onkeyup="filterTable('empSearch','empTable',0)">
            </div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="empTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="empTableBody"></tbody>
            </table>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
