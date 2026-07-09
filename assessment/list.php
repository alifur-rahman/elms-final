<?php
$basePath    = '..';
$pageTitle   = 'Assessment';
$activePage  = 'assessment';
$breadcrumb  = 'Home / Recruitment / Assessment';
$pageStyles  = '
.stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: #fff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); display: flex; align-items: center; gap: 14px; }
.stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-card .stat-icon.blue { background: #e8f0fe; color: var(--primary); }
.stat-card .stat-icon.green { background: #e6f4ea; color: var(--success); }
.stat-card .stat-icon.yellow { background: #fef7e0; color: var(--warning); }
.stat-card .stat-info h3 { font-size: 22px; font-weight: 700; line-height: 1.2; }
.stat-card .stat-info p { font-size: 12px; color: var(--gray-500); }
.form-section { margin-bottom: 20px; }
.form-section-title { font-size: 14px; font-weight: 600; color: var(--gray-700); margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid var(--gray-200); }
.interview-card { background: var(--gray-50); border-radius: 6px; padding: 16px; margin-bottom: 12px; }
';
$pageScripts = '
var assessmentData = [];

function renderAssessments() {
    var tbody = document.getElementById("assessmentTableBody");
    tbody.innerHTML = "";
    var stats = { pending: 0, completed: 0, totalScore: 0, count: 0 };

    assessmentData.forEach(function(c) {
        stats.count++;
        if (c.avg_rating) stats.totalScore += c.avg_rating;

        var isPending = (c.candidate_status === "Selected");
        if (isPending) stats.pending++;
        else stats.completed++;

        var avgDisplay = c.avg_rating != null ? c.avg_rating + " / 5" : "-";
        var tr = document.createElement("tr");
        tr.innerHTML =
            "<td><strong>" + c.first_name + " " + c.last_name + "</strong></td>" +
            "<td>" + (c.job_title || "N/A") + "</td>" +
            "<td>" + avgDisplay + "</td>" +
            "<td><span class=\"badge " + getBadgeClass(c.candidate_status) + "\">" + c.candidate_status + "</span></td>" +
            "<td><div class=\"btn-group\">" +
                "<button class=\"btn btn-sm btn-outline\" onclick=\"window.viewAssessment(" + c.candidate_id + ")\" style=\"margin-right:4px\">View Details</button>" +
                (isPending ? "<button class=\"btn btn-sm btn-success\" onclick=\"window.completeAssessment(" + c.candidate_id + ")\">Complete Assessment</button>" : "") +
            "</div></td>";
        tbody.appendChild(tr);
    });

    document.getElementById("statPending").textContent = stats.pending;
    document.getElementById("statCompleted").textContent = stats.completed;
    document.getElementById("statAvgScore").textContent = stats.count > 0 ? (stats.totalScore / stats.count).toFixed(1) : "0";
}

window.viewAssessment = function(candidateId) {
    var c = null;
    for (var i = 0; i < assessmentData.length; i++) { if (assessmentData[i].candidate_id === candidateId) { c = assessmentData[i]; break; } }
    if (!c) return;

    apiGet("ajax/interviews.php?candidate_id=" + candidateId, null, function(res) {
        var interviews = (res.success && res.data) ? res.data : [];
        var html = "";

        html += "<div class=\"form-section\">";
        html += "<div class=\"form-section-title\">Candidate Information</div>";
        html += "<div class=\"form-row-2\">";
        html += "<div class=\"form-group\"><label>Name</label><div>" + c.first_name + " " + c.last_name + "</div></div>";
        html += "<div class=\"form-group\"><label>Job Applied</label><div>" + (c.job_title || "N/A") + "</div></div>";
        html += "<div class=\"form-group\"><label>Email</label><div>" + (c.email || "-") + "</div></div>";
        html += "<div class=\"form-group\"><label>Phone</label><div>" + (c.phone || "-") + "</div></div>";
        html += "<div class=\"form-group\"><label>Status</label><div><span class=\"badge " + getBadgeClass(c.candidate_status) + "\">" + c.candidate_status + "</span></div></div>";
        html += "<div class=\"form-group\"><label>Applied At</label><div>" + formatDateTime(c.applied_at) + "</div></div>";
        html += "</div></div>";

        if (interviews.length > 0) {
            html += "<div class=\"form-section\">";
            html += "<div class=\"form-section-title\">Interview Feedback</div>";
            for (var i = 0; i < interviews.length; i++) {
                var iv = interviews[i];
                html += "<div class=\"interview-card\">";
                html += "<div class=\"form-row-2\">";
                html += "<div class=\"form-group\"><label>Round</label><div>" + (iv.round_name || "Round " + iv.round) + "</div></div>";
                html += "<div class=\"form-group\"><label>Type</label><div>" + (iv.type || "-") + "</div></div>";
                html += "<div class=\"form-group\"><label>Date</label><div>" + formatDateTime(iv.date) + "</div></div>";
                html += "<div class=\"form-group\"><label>Interviewer</label><div>" + (iv.interviewer || "-") + "</div></div>";
                html += "<div class=\"form-group\"><label>Rating</label><div><strong>" + (iv.rating || "-") + "</strong> / 5</div></div>";
                html += "<div class=\"form-group\"><label>Decision</label><div><span class=\"badge " + getBadgeClass(iv.decision) + "\">" + (iv.decision || "-") + "</span></div></div>";
                html += "</div>";
                if (iv.feedback) {
                    html += "<div class=\"form-group\"><label>Feedback</label><div>" + iv.feedback + "</div></div>";
                }
                html += "</div>";
            }
            html += "</div>";
        } else {
            html += "<p style=\"color:var(--gray-500)\">No interview records found for this candidate.</p>";
        }

        document.getElementById("assessmentDetailBody").innerHTML = html;
        openModal("assessmentDetailModal");
    }, function() {
        showAlert("Failed to load interview data", "error");
    });
};

window.completeAssessment = function(candidateId) {
    if (!confirm("Move this candidate to Offered stage?")) return;
    apiPost("ajax/complete.php", { id: candidateId }, function(res) {
        showAlert(res.message || "Assessment completed!", "success");
        renderPage();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
    });
};

function renderPage() {
    apiGet("ajax/list.php", null, function(res) {
        if (res.success && res.data) assessmentData = res.data;
        renderAssessments();
    }, function() { renderAssessments(); });
}

renderPage();
';

ob_start();
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">&#x1F4DD;</div>
        <div class="stat-info"><h3 id="statPending">0</h3><p>Pending Assessment</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#x2705;</div>
        <div class="stat-info"><h3 id="statCompleted">0</h3><p>Completed</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">&#x1F4CA;</div>
        <div class="stat-info"><h3 id="statAvgScore">0</h3><p>Avg Interview Score</p></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Assessment List</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#x1F50D;</span>
                <input type="text" id="searchInput" placeholder="Search candidate or job..." onkeyup="filterTable('searchInput','assessmentTable')">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table id="assessmentTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Interview Score</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="assessmentTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Assessment Detail Modal -->
<div class="modal-overlay" id="assessmentDetailModal" onclick="if(event.target===this)closeModal('assessmentDetailModal')">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3>Assessment Detail</h3>
            <button class="modal-close" onclick="closeModal('assessmentDetailModal')">&times;</button>
        </div>
        <div class="modal-body" id="assessmentDetailBody"></div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
