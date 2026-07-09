<?php
$basePath    = '..';
$pageTitle   = 'Screening';
$activePage  = 'screening';
$breadcrumb  = 'Home / Recruitment / Screening';
$pageStyles  = '
.stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: #fff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); display: flex; align-items: center; gap: 14px; }
.stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-card .stat-icon.blue { background: #e8f0fe; color: var(--primary); }
.stat-card .stat-icon.green { background: #e6f4ea; color: var(--success); }
.stat-card .stat-icon.red { background: #fce8e6; color: var(--danger); }
.stat-card .stat-info h3 { font-size: 22px; font-weight: 700; line-height: 1.2; }
.stat-card .stat-info p { font-size: 12px; color: var(--gray-500); }
.filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.filter-bar .form-control { width: auto; min-width: 130px; }
';
$pageScripts = '
var screeningData = [];
var editingScreeningId = null;

function renderScreening() {
    var tbody = document.getElementById("screeningTableBody");
    tbody.innerHTML = "";
    var stats = { pending: 0, shortlisted: 0, rejected: 0 };

    screeningData.forEach(function(row) {
        var hasScreening = row.screening_id !== null && row.screening_id !== undefined;
        var name = row.first_name + " " + row.last_name;

        if (row.candidate_status === "Applied") stats.pending++;
        if (hasScreening && row.screening_result === "Shortlist") stats.shortlisted++;
        if (hasScreening && row.screening_result === "Reject") stats.rejected++;

        var statusHtml = "";
        if (hasScreening) {
            statusHtml = "Rating: " + row.rating + " | <span class=\"badge " + getBadgeClass(row.screening_result) + "\">" + row.screening_result + "</span>";
        } else {
            statusHtml = "<span class=\"badge badge-pending\">Pending</span>";
        }

        var actionHtml = "";
        if (hasScreening) {
            actionHtml = "<button class=\"btn btn-sm btn-warning\" onclick=\"window.openScreening(" + row.candidate_id + "," + row.screening_id + ")\">Edit Screening</button>";
        } else {
            actionHtml = "<button class=\"btn btn-sm btn-primary\" onclick=\"window.openScreening(" + row.candidate_id + ",0)\">Screen Now</button>";
        }

        var tr = document.createElement("tr");
        tr.innerHTML =
            "<td><strong>" + name + "</strong></td>" +
            "<td>" + (row.job_title || "N/A") + "</td>" +
            "<td>" + formatDate(row.applied_at) + "</td>" +
            "<td>" + statusHtml + "</td>" +
            "<td>" + actionHtml + "</td>";
        tbody.appendChild(tr);
    });

    document.getElementById("statPending").textContent = stats.pending;
    document.getElementById("statShortlisted").textContent = stats.shortlisted;
    document.getElementById("statRejected").textContent = stats.rejected;
}

window.openScreening = function(candidateId, screeningId) {
    document.getElementById("scCandidateId").value = candidateId;
    document.getElementById("scScreeningId").value = screeningId;
    editingScreeningId = screeningId || null;

    if (screeningId) {
        for (var i = 0; i < screeningData.length; i++) {
            var row = screeningData[i];
            if (row.screening_id === screeningId) {
                document.getElementById("scRating").value = row.rating;
                document.getElementById("scCommunication").value = row.communication_score;
                document.getElementById("scExperience").value = row.experience_match;
                document.getElementById("scSkill").value = row.skill_match;
                document.getElementById("scEducation").value = row.education_match;
                document.getElementById("scResult").value = row.screening_result;
                document.getElementById("scRemarks").value = row.screening_remarks || "";
                break;
            }
        }
        document.getElementById("screeningModalTitle").innerHTML = "&#x270F; Edit Screening";
        document.getElementById("scSubmitBtn").innerHTML = "&#x2728; Update Screening";
    } else {
        document.getElementById("screeningForm").reset();
        document.getElementById("scCandidateId").value = candidateId;
        document.getElementById("screeningModalTitle").innerHTML = "&#x1F50D; Screen Candidate";
        document.getElementById("scSubmitBtn").innerHTML = "&#x2728; Save Screening";
    }
    openModal("screeningModal");
};

window.ajaxSaveScreening = function() {
    var candidateId = parseInt(document.getElementById("scCandidateId").value);
    var screeningId = parseInt(document.getElementById("scScreeningId").value) || 0;
    var rating = parseFloat(document.getElementById("scRating").value);
    var communication = parseFloat(document.getElementById("scCommunication").value);
    var experience = document.getElementById("scExperience").value;
    var skill = document.getElementById("scSkill").value;
    var education = document.getElementById("scEducation").value;
    var result = document.getElementById("scResult").value;
    var remarks = document.getElementById("scRemarks").value.trim();

    if (!rating || !communication || !experience || !skill || !education || !result) {
        showAlert("Please fill in all required fields", "error");
        return;
    }
    if (rating < 1 || rating > 5 || communication < 1 || communication > 5) {
        showAlert("Rating and Communication Score must be between 1 and 5", "error");
        return;
    }

    var btn = document.getElementById("scSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "\u23F3 Saving...";

    var payload = {
        candidate_id: candidateId,
        rating: rating,
        communication_score: communication,
        experience_match: experience,
        skill_match: skill,
        education_match: education,
        result: result,
        remarks: remarks
    };

    var isEdit = editingScreeningId > 0;
    if (isEdit) payload.id = editingScreeningId;

    apiPost(isEdit ? "ajax/update.php" : "ajax/submit.php", payload, function(res) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert(res.message || "Screening saved!", "success");
        closeModal("screeningModal");
        renderPage();
    }, function(err, status) {
        btn.disabled = false;
        btn.innerHTML = origText;
        var msg = (typeof err === "object" && err.message) ? err.message : "Save failed";
        showAlert(msg, "error");
    });
};

function filterByDept() {
    var deptVal = document.getElementById("deptFilter").value.toLowerCase();
    var rows = document.querySelectorAll("#screeningTable tbody tr");
    for (var i = 0; i < rows.length; i++) {
        var jobCell = rows[i].querySelectorAll("td")[1];
        if (!deptVal || (jobCell && jobCell.textContent.toLowerCase().indexOf(deptVal) !== -1)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function loadDeptFilter() {
    var sel = document.getElementById("deptFilter");
    if (!sel || sel.options.length > 1) return;
    if (typeof DATA !== "undefined" && DATA.departments) {
        DATA.departments.forEach(function(d) {
            var opt = document.createElement("option");
            opt.value = d.name;
            opt.textContent = d.name;
            sel.appendChild(opt);
        });
    }
}

function renderPage() {
    apiGet("ajax/list.php", null, function(res) {
        if (res.success && res.data) {
            screeningData = res.data;
        }
        renderScreening();
    }, function() {
        renderScreening();
    });
}

loadDeptFilter();
renderPage();
';

ob_start();
?>
<div class="stats-grid" id="statsGrid">
    <div class="stat-card">
        <div class="stat-icon blue">&#x23F3;</div>
        <div class="stat-info"><h3 id="statPending">0</h3><p>Pending Screening</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#x2B50;</div>
        <div class="stat-info"><h3 id="statShortlisted">0</h3><p>Shortlisted</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">&#x2715;</div>
        <div class="stat-info"><h3 id="statRejected">0</h3><p>Rejected</p></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Screening List</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#x1F50D;</span>
                <input type="text" id="searchInput" placeholder="Search candidate or job..." onkeyup="filterTable('searchInput','screeningTable')">
            </div>
            <select class="form-control" id="deptFilter" onchange="filterByDept()">
                <option value="">All Departments</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table id="screeningTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Applied Date</th>
                        <th>Screening Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="screeningTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Screening Form Modal -->
<div class="modal-overlay" id="screeningModal" onclick="if(event.target===this)closeModal('screeningModal')">
    <div class="modal">
        <div class="modal-header">
            <h3 id="screeningModalTitle">&#x1F50D; Screen Candidate</h3>
            <button class="modal-close" onclick="closeModal('screeningModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="screeningForm" onsubmit="event.preventDefault();window.ajaxSaveScreening()">
                <input type="hidden" id="scCandidateId">
                <input type="hidden" id="scScreeningId">

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Rating (1-5) <span class="required">*</span></label>
                        <input type="number" class="form-control" id="scRating" min="1" max="5" step="0.5" required>
                    </div>
                    <div class="form-group">
                        <label>Communication Score (1-5) <span class="required">*</span></label>
                        <input type="number" class="form-control" id="scCommunication" min="1" max="5" step="0.5" required>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Experience Match <span class="required">*</span></label>
                        <select class="form-control" id="scExperience" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Skill Match <span class="required">*</span></label>
                        <select class="form-control" id="scSkill" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Education Match <span class="required">*</span></label>
                        <select class="form-control" id="scEducation" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Result <span class="required">*</span></label>
                        <select class="form-control" id="scResult" required>
                            <option value="">Select</option>
                            <option value="Shortlist">Shortlist</option>
                            <option value="Reject">Reject</option>
                            <option value="Hold">Hold</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Overall Remarks</label>
                    <textarea class="form-control" id="scRemarks" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('screeningModal')">Cancel</button>
            <button class="btn btn-primary" id="scSubmitBtn" onclick="window.ajaxSaveScreening()">&#x2728; Save Screening</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
