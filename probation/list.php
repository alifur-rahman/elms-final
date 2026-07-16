<?php
$basePath   = '..';
$pageTitle  = 'Probation Appraisal';
$activePage = 'probation';
$breadcrumb = 'Home / Probation / Probation Appraisal';
$pageStyles = '
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: var(--card-bg); border: 1px solid var(--gray-200); border-radius: 12px; padding: 20px; }
.stat-card .stat-value { font-size: 28px; font-weight: 700; color: var(--gray-900); }
.stat-card .stat-label { font-size: 12px; color: var(--gray-500); margin-top: 4px; }
.stat-card .stat-icon { font-size: 32px; margin-bottom: 8px; }
.filter-bar { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
.empty-state { text-align: center; padding: 48px 24px; color: var(--gray-500); }
.empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; }
.empty-state p { font-size: 14px; }
.info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-bottom: 20px; }
.info-item label { display: block; font-size: 11px; color: var(--gray-500); margin-bottom: 2px; }
.info-item span { font-size: 13px; font-weight: 500; }
.modal-section { margin-bottom: 20px; }
.modal-section h4 { font-size: 13px; color: var(--gray-700); margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1px solid var(--gray-200); }
.score-meter-bar { display: flex; align-items: center; gap: 12px; }
.score-bar-track { flex: 1; height: 10px; background: var(--gray-200); border-radius: 5px; overflow: hidden; }
.score-bar-fill { height: 100%; border-radius: 5px; background: linear-gradient(90deg, #f44336, #ff9800, #ffc107, #4caf50); transition: width .5s; }
.score-bar-label { font-size: 14px; font-weight: 600; color: var(--primary); white-space: nowrap; }
.score-labels { display: flex; justify-content: space-between; margin-top: 2px; font-size: 10px; color: var(--gray-500); }
.comment-box { background: #f8f9fa; border-radius: 6px; padding: 10px 14px; font-size: 13px; color: #333; margin-top: 4px; min-height: 20px; }
.comment-section { margin-bottom: 16px; }
.comment-section label { font-size: 12px; font-weight: 600; color: var(--gray-600); display: block; margin-bottom: 4px; }
.wf-container { display: flex; flex-direction: column; align-items: flex-start; padding: 8px 0; }
.wf-item { display: flex; align-items: center; gap: 12px; }
.wf-circle { width: 32px; height: 32px; border-radius: 50%; border: 2px solid var(--gray-300); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: var(--gray-400); background: #fff; flex-shrink: 0; }
.wf-item.wf-done .wf-circle { border-color: var(--success); background: var(--success); color: #fff; }
.wf-item.wf-active .wf-circle { border-color: var(--primary); background: var(--primary); color: #fff; }
.wf-info { display: flex; flex-direction: column; }
.wf-info strong { font-size: 13px; }
.wf-status { font-size: 11px; color: var(--gray-500); }
.wf-comment { font-size: 11px; color: #666; font-style: italic; }
.wf-connector { width: 2px; height: 20px; background: var(--gray-300); margin-left: 15px; }
.wf-connector.wf-done { background: var(--success); }
.wf-connector.wf-active { background: var(--primary); }
.eligible-section { margin-top: 24px; }
';
$pageScripts = <<<'EOSCRIPT'
var departments = [];

function renderStats() {
    apiGet("ajax/appraisals_list.php", null, function(res) {
        var data = res.success && res.data ? res.data : [];
        var active = data.filter(function(a) { return a.status === "Pending" || a.status === "In Progress" || a.status === "Draft"; }).length;
        var completed = data.filter(function(a) { return a.status === "Completed"; }).length;
        var extended = data.filter(function(a) { return a.recommendation === "Extend"; }).length;
        var pendingConfirm = data.filter(function(a) { return a.status === "Completed" && a.recommendation === "Confirm"; }).length;
        document.getElementById("statActive").textContent = active;
        document.getElementById("statCompleted").textContent = completed;
        document.getElementById("statExtended").textContent = extended;
        document.getElementById("statPendingConfirm").textContent = pendingConfirm;
    });
}

function loadDepartments() {
    apiGet("../ajax/departments.php", null, function(res) {
        departments = res.success && res.data ? res.data : [];
        var sel = document.getElementById("deptFilter");
        sel.innerHTML = "<option value=\"0\">All Departments</option>";
        for (var i = 0; i < departments.length; i++) {
            sel.innerHTML += "<option value=\"" + departments[i].id + "\">" + departments[i].name + "</option>";
        }
    });
}

function applyFilters() {
    var status = document.getElementById("statusFilter").value;
    var deptId = document.getElementById("deptFilter").value;
    var search = document.getElementById("searchInput").value.toLowerCase().trim();
    var params = [];
    if (status) params.push("status=" + status);
    if (deptId > 0) params.push("dept_id=" + deptId);
    var qs = params.length ? "?" + params.join("&") : "";
    apiGet("ajax/appraisals_list.php" + qs, null, function(res) {
        var data = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("appraisalTableBody");
        if (search) {
            data = data.filter(function(a) { return a.employee_name.toLowerCase().includes(search) || a.dept_name.toLowerCase().includes(search) || a.job_title.toLowerCase().includes(search); });
        }
        if (data.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"8\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No probation appraisals found.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < data.length; i++) {
            var a = data[i];
            var daysBadge = "";
            if (a.days_left !== null) {
                if (a.days_left < 0) daysBadge = "<span class=\"badge badge-danger\">Overdue by " + Math.abs(a.days_left) + "d</span>";
                else if (a.days_left <= 7) daysBadge = "<span class=\"badge badge-warning\">" + a.days_left + "d left</span>";
                else daysBadge = "<span class=\"badge badge-completed\">" + a.days_left + "d left</span>";
            }
            var statusBadge = a.status === "Completed" ? "<span class=\"badge badge-completed\">Completed</span>"
                : a.status === "In Progress" ? "<span class=\"badge badge-in-progress\">In Progress</span>"
                : a.status === "Draft" ? "<span class=\"badge badge-pending\">Draft</span>"
                : a.status === "Rejected" ? "<span class=\"badge badge-danger\">Rejected</span>"
                : "<span class=\"badge badge-pending\">" + a.status + "</span>";
            var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
            var action = "<div class=\"btn-group\">";
            if (a.status === "Draft") {
                action += "<a class=\"btn btn-sm btn-primary\" href=\"edit.php?id=" + a.id + "\">Edit</a>";
            } else {
                action += "<button class=\"btn btn-sm btn-primary\" onclick=\"viewDetail(" + a.id + ")\">View</button>";
            }
            action += "</div>";
            html += "<tr>" +
                "<td><strong>" + a.employee_name + "</strong><br><small style='color:#888'>" + a.job_title + "</small></td>" +
                "<td>" + a.dept_name + "</td>" +
                "<td>" + (a.joining_date || "-") + "</td>" +
                "<td>" + (a.end_date || "-") + "</td>" +
                "<td>" + daysBadge + "</td>" +
                "<td>" + score + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function viewDetail(id) {
    apiGet("ajax/appraisals_get.php?id=" + id, null, function(res) {
        if (!res.success || !res.data) { showAlert("Not found", "error"); return; }
        var a = res.data;
        document.getElementById("mdTitleName").textContent = a.employee_name;
        // Info grid
        document.getElementById("mdJoining").textContent = a.joining_date || "-";
        document.getElementById("mdEnd").textContent = a.end_date || "-";
        document.getElementById("mdProbation").textContent = a.probation_months ? a.probation_months + " months" : "-";
        document.getElementById("mdDaysLeft").textContent = a.days_left !== null ? (a.days_left < 0 ? "Overdue by " + Math.abs(a.days_left) + "d" : a.days_left + "d left") : "-";
        document.getElementById("mdDept").textContent = a.dept_name || "N/A";
        document.getElementById("mdDesignation").textContent = a.designation_name || "N/A";
        document.getElementById("mdStatus").textContent = a.status;
        document.getElementById("mdStatus").className = a.status === "Completed" ? "badge badge-completed" : a.status === "Draft" ? "badge badge-pending" : a.status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
        document.getElementById("mdRecommendation").textContent = a.recommendation || "-";
        document.getElementById("mdScoreVal").textContent = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
        // Score meter
        var pct = a.overall_score !== null ? (a.overall_score / 5 * 100) : 0;
        document.getElementById("mdScoreBar").style.width = Math.min(pct, 100) + "%";
        document.getElementById("mdScoreLabel").textContent = a.overall_score !== null ? a.overall_score.toFixed(1) + " / 5" : "0 / 5";
        // Comments
        document.getElementById("mdSupervisorCmt").textContent = a.supervisor_comments || "No comments";
        document.getElementById("mdDeptCmt").textContent = a.dept_head_comments || "No comments";
        document.getElementById("mdHRCmt").textContent = a.hr_review || "No comments";
        document.getElementById("mdRemarks").textContent = a.remarks || "No remarks";
        // Scores table
        var scoresHtml = "";
        if (a.scores && a.scores.length) {
            for (var i = 0; i < a.scores.length; i++) {
                var s = a.scores[i];
                var barW = (s.score / 5 * 100);
                scoresHtml += "<tr>" +
                    "<td>" + s.category_name + "</td>" +
                    "<td>" + s.weight + "%</td>" +
                    "<td><div style='display:flex;align-items:center;gap:8px'><div style='flex:1;height:6px;background:#e9ecef;border-radius:3px;overflow:hidden'><div style='height:100%;width:" + barW + "%;background:var(--primary);border-radius:3px'></div></div><span style='font-weight:600;font-size:13px;min-width:30px'>" + s.score.toFixed(1) + "</span></div></td>" +
                    "<td style='font-size:12px;color:#666;max-width:160px'>" + (s.comments || "-") + "</td>" +
                "</tr>";
            }
        } else {
            scoresHtml = "<tr><td colspan=\"4\" style=\"text-align:center;color:#888;padding:24px\">No scores recorded yet</td></tr>";
        }
        document.getElementById("mdScoresBody").innerHTML = scoresHtml;
        // Workflow
        var wfHtml = "";
        if (a.workflow && a.workflow.length) {
            for (var i = 0; i < a.workflow.length; i++) {
                var w = a.workflow[i];
                var wfClass = w.status === "Approved" ? "wf-done" : w.status === "In Progress" ? "wf-active" : "";
                var icon = w.status === "Approved" ? "&#10003;" : (i + 1);
                wfHtml += "<div class=\"wf-item " + wfClass + "\">" +
                    "<div class=\"wf-circle\">" + icon + "</div>" +
                    "<div class=\"wf-info\"><strong>" + w.level + "</strong>" +
                    "<span class=\"wf-status\">" + (w.status === "Approved" ? "Approved by " + (w.approved_by || "System") : w.status) + "</span>" +
                    (w.comments ? "<span class=\"wf-comment\">" + w.comments + "</span>" : "") +
                    "</div></div>";
                if (i < a.workflow.length - 1) wfHtml += "<div class=\"wf-connector " + wfClass + "\"></div>";
            }
        } else {
            wfHtml = "<div style='text-align:center;padding:16px;color:#888'>No workflow data</div>";
        }
        document.getElementById("mdWorkflow").innerHTML = wfHtml;
        openModal("viewDetailModal");
    });
}

function loadEligibleEmployees() {
    apiGet("ajax/eligible_employees.php", null, function(res) {
        var data = res.success && res.data ? res.data : [];
        var html = "";
        for (var i = 0; i < data.length; i++) {
            var e = data[i];
            var days = e.days_left !== null ? (e.days_left < 0 ? "Overdue " + Math.abs(e.days_left) + "d" : e.days_left + "d left") : "-";
            html += "<tr>" +
                "<td><strong>" + e.name + "</strong><br><small style='color:#888'>" + e.job_title + "</small></td>" +
                "<td>" + e.designation_name + "</td>" +
                "<td>" + (e.joining_date || "-") + "</td>" +
                "<td>" + days + "</td>" +
                "<td><a class=\"btn btn-sm btn-primary\" href=\"new.php?employee_id=" + e.id + "\">+ Create</a></td>" +
            "</tr>";
        }
        if (data.length === 0) {
            html = "<tr><td colspan=\"5\" style=\"text-align:center;padding:24px;color:#888\">All employees have been evaluated</td></tr>";
        }
        document.getElementById("eligibleBody").innerHTML = html;
    });
}

function formatDate(d) {
    if (!d) return "";
    var date = new Date(d + "T00:00:00");
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return months[date.getMonth()] + " " + ("0" + date.getDate()).slice(-2) + ", " + date.getFullYear();
}

document.addEventListener("DOMContentLoaded", function() {
    renderStats();
    loadDepartments();
    applyFilters();
    loadEligibleEmployees();
});
EOSCRIPT;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Probation Appraisals</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Search employees..." onkeyup="applyFilters()">
            </div>
            <select class="form-control" id="deptFilter" onchange="applyFilters()" style="width:180px">
                <option value="0">All Departments</option>
            </select>
            <select class="form-control" id="statusFilter" onchange="applyFilters()" style="width:160px">
                <option value="">All Status</option>
                <option value="Draft">Draft</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
            <a class="btn btn-primary" href="new.php">+ New Appraisal</a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-value" id="statActive">0</div><div class="stat-label">Active Reviews</div></div>
    <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-value" id="statCompleted">0</div><div class="stat-label">Completed</div></div>
    <div class="stat-card"><div class="stat-icon">🔄</div><div class="stat-value" id="statExtended">0</div><div class="stat-label">Extended</div></div>
    <div class="stat-card"><div class="stat-icon">🎉</div><div class="stat-value" id="statPendingConfirm">0</div><div class="stat-label">Confirm Pending</div></div>
</div>

<div class="card eligible-section">
    <div class="card-header">
        <h2>👤 Eligible Employees</h2>
        <span style="font-size:13px;color:var(--gray-500)">Employees on probation who haven't been evaluated yet</span>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>Employee</th><th>Designation</th><th>Joining Date</th><th>Days Left</th><th>Action</th></tr>
                </thead>
                <tbody id="eligibleBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="appraisalTable">
                <thead>
                    <tr><th>Employee</th><th>Department</th><th>Joining Date</th><th>Probation End</th><th>Days Left</th><th>Score</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody id="appraisalTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Detail Modal -->
<div class="modal-overlay" id="viewDetailModal" onclick="if(event.target===this)closeModal('viewDetailModal')" style="display:none">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Appraisal Details - <span id="mdTitleName">Employee</span></h3>
            <button class="modal-close" onclick="closeModal('viewDetailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-section">
                <h4>&#128203; Appraisal Information</h4>
                <div class="info-grid">
                    <div class="info-item"><label>Joining Date</label><span id="mdJoining">-</span></div>
                    <div class="info-item"><label>Probation End</label><span id="mdEnd">-</span></div>
                    <div class="info-item"><label>Probation Period</label><span id="mdProbation">-</span></div>
                    <div class="info-item"><label>Days Left</label><span id="mdDaysLeft">-</span></div>
                    <div class="info-item"><label>Department</label><span id="mdDept">-</span></div>
                    <div class="info-item"><label>Designation</label><span id="mdDesignation">-</span></div>
                    <div class="info-item"><label>Status</label><span id="mdStatus" class="badge">-</span></div>
                    <div class="info-item"><label>Recommendation</label><span id="mdRecommendation">-</span></div>
                </div>
            </div>

            <div class="modal-section">
                <h4>&#128202; Overall Score</h4>
                <div class="score-meter-bar">
                    <div class="score-bar-track">
                        <div class="score-bar-fill" id="mdScoreBar" style="width:0%"></div>
                    </div>
                    <span class="score-bar-label" id="mdScoreLabel">0 / 5</span>
                </div>
                <div class="score-labels"><span>Poor</span><span>Average</span><span>Good</span><span>Excellent</span></div>
                <div style="text-align:right;font-size:28px;font-weight:700;color:var(--primary)" id="mdScoreVal">0.00</div>
            </div>

            <div class="modal-section">
                <h4>&#128220; Category Scores</h4>
                <table class="appraisal-table">
                    <thead><tr><th>Category</th><th>Weight</th><th>Score</th><th>Comments</th></tr></thead>
                    <tbody id="mdScoresBody"></tbody>
                </table>
            </div>

            <div class="modal-section">
                <h4>&#128295; Workflow Progress</h4>
                <div class="wf-container" id="mdWorkflow"></div>
            </div>

            <div class="modal-section">
                <h4>&#128221; Comments &amp; Reviews</h4>
                <div class="comment-section">
                    <label>Supervisor Comments</label>
                    <div class="comment-box" id="mdSupervisorCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>Department Head Comments</label>
                    <div class="comment-box" id="mdDeptCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>HR Review</label>
                    <div class="comment-box" id="mdHRCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>Overall Remarks</label>
                    <div class="comment-box" id="mdRemarks">No remarks</div>
                </div>
            </div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn" onclick="closeModal('viewDetailModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
