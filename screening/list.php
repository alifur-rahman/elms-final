<?php
$basePath    = '..';
$pageTitle   = 'Screening';
$activePage  = 'screening';
$breadcrumb  = 'Home / Recruitment / Screening';
$pageStyles  = '
.tab-bar { display: flex; gap: 0; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; }
.tab-btn { padding: 10px 24px; font-size: 14px; font-weight: 600; background: none; border: none; border-bottom: 3px solid transparent; margin-bottom: -2px; cursor: pointer; color: #64748b; transition: all .2s; }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-btn:hover { color: var(--primary); }
.tab-content { display: none; }
.tab-content.active { display: block; }
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
.pagination-bar { display:flex; align-items:center; justify-content:space-between; margin-top:16px; flex-wrap:wrap; gap:10px; }
.pagination-bar .page-info { font-size:13px; color:var(--gray-500); }
.pagination-bar .page-btns { display:flex; gap:4px; align-items:center; }
.pagination-bar .page-btn { min-width:32px; height:32px; padding:0 10px; border:1px solid var(--gray-200); border-radius:6px; background:#fff; font-size:13px; cursor:pointer; color:var(--gray-700); transition:var(--transition); font-family:inherit; }
.pagination-bar .page-btn:hover { border-color:var(--primary); color:var(--primary); }
.pagination-bar .page-btn.active { background:var(--primary); color:#fff; border-color:var(--primary); }
.pagination-bar .page-btn:disabled { opacity:.4; cursor:default; }
.pagination-bar .per-page { display:flex; align-items:center; gap:6px; font-size:13px; color:var(--gray-500); }
.pagination-bar .per-page select { padding:4px 8px; border:1px solid var(--gray-200); border-radius:4px; font-size:13px; font-family:inherit; }
';
$pageScripts = '
var upcomingData = [];
var completeData = [];
var editingScreeningId = null;
var currentPage = 1;
var perPage = 10;

function switchTab(name) {
    document.querySelectorAll(".tab-btn").forEach(function(b) { b.classList.remove("active"); });
    document.querySelectorAll(".tab-content").forEach(function(c) { c.classList.remove("active"); });
    document.getElementById("tabBtn" + name).classList.add("active");
    document.getElementById("tab" + name).classList.add("active");
    currentPage = 1;
    if (name === "Upcoming") renderUpcoming();
    else renderComplete();
}

function getFilteredUpcoming() {
    var q = document.getElementById("searchUpcoming").value.trim().toLowerCase();
    return upcomingData.filter(function(c) {
        var name = (c.first_name + " " + c.last_name + " " + (c.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
}

function getFilteredComplete() {
    var q = document.getElementById("searchComplete").value.trim().toLowerCase();
    return completeData.filter(function(row) {
        var name = (row.first_name + " " + row.last_name + " " + (row.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
}

function renderPaginationBar(containerId, total, totalPages) {
    var html = "";
    html += "<div class=\"page-info\">Showing page " + currentPage + " of " + totalPages + " (" + total + " total)</div>";
    html += "<div class=\"page-btns\">";
    html += "<button class=\"page-btn\" onclick=\"goToPage(1)\" " + (currentPage <= 1 ? "disabled" : "") + ">&#xAB;</button>";
    html += "<button class=\"page-btn\" onclick=\"goToPage(" + (currentPage - 1) + ")\" " + (currentPage <= 1 ? "disabled" : "") + ">&#x2039;</button>";
    var start = Math.max(1, currentPage - 2);
    var end = Math.min(totalPages, currentPage + 2);
    for (var p = start; p <= end; p++) {
        html += "<button class=\"page-btn" + (p === currentPage ? " active" : "") + "\" onclick=\"goToPage(" + p + ")\">" + p + "</button>";
    }
    html += "<button class=\"page-btn\" onclick=\"goToPage(" + (currentPage + 1) + ")\" " + (currentPage >= totalPages ? "disabled" : "") + ">&#x203A;</button>";
    html += "<button class=\"page-btn\" onclick=\"goToPage(" + totalPages + ")\" " + (currentPage >= totalPages ? "disabled" : "") + ">&#xBB;</button>";
    html += "</div>";
    html += "<div class=\"per-page\">Rows: <select onchange=\"changePerPage(this.value)\">";
    [10, 20, 50, 100].forEach(function(n) {
        html += "<option value=\"" + n + "\"" + (n === perPage ? " selected" : "") + ">" + n + "</option>";
    });
    html += "</select></div>";
    document.getElementById(containerId).innerHTML = html;
}

function goToPage(p) {
    var filtered = document.getElementById("tabUpcoming").classList.contains("active") ? getFilteredUpcoming() : getFilteredComplete();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (p < 1) p = 1;
    if (p > totalPages) p = totalPages;
    if (p === currentPage) return;
    currentPage = p;
    if (document.getElementById("tabUpcoming").classList.contains("active")) renderUpcoming();
    else renderComplete();
}

function changePerPage(n) {
    perPage = parseInt(n);
    currentPage = 1;
    if (document.getElementById("tabUpcoming").classList.contains("active")) renderUpcoming();
    else renderComplete();
}

function applyUpcomingFilter() {
    currentPage = 1;
    renderUpcoming();
}

function applyCompleteFilter() {
    currentPage = 1;
    renderComplete();
}

function renderUpcoming() {
    var filtered = getFilteredUpcoming();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("upcomingTableBody");
    tbody.innerHTML = "";
    pageData.forEach(function(c) {
        var name = c.first_name + " " + c.last_name;
        var tr = document.createElement("tr");
        tr.innerHTML =
            "<td><strong>" + name + "</strong></td>" +
            "<td>" + (c.job_title || "N/A") + "</td>" +
            "<td>" + formatDate(c.applied_at) + "</td>" +
            "<td><span class=\"badge badge-pending\">Applied</span></td>" +
            "<td><button class=\"btn btn-sm btn-primary\" onclick=\"window.openScreening(" + c.candidate_id + ",0)\">Screen Now</button></td>";
        tbody.appendChild(tr);
    });
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"5\" style=\"text-align:center;padding:32px;color:var(--gray-400)\">No pending screenings</td></tr>";
    }
    document.getElementById("statPending").textContent = upcomingData.length;
    renderPaginationBar("paginationUpcoming", filtered.length, totalPages);
}

function renderComplete() {
    var filtered = getFilteredComplete();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("completeTableBody");
    tbody.innerHTML = "";
    var stats = { shortlisted: 0, rejected: 0 };
    completeData.forEach(function(row) {
        if (row.screening_result === "Shortlist") stats.shortlisted++;
        if (row.screening_result === "Reject") stats.rejected++;
    });
    pageData.forEach(function(row) {
        var name = row.first_name + " " + row.last_name;
        var tr = document.createElement("tr");
        tr.innerHTML =
            "<td><strong>" + name + "</strong></td>" +
            "<td>" + (row.job_title || "N/A") + "</td>" +
            "<td>" + formatDate(row.applied_at) + "</td>" +
            "<td>Rating: " + (row.rating || "-") + " | <span class=\"badge " + getBadgeClass(row.screening_result) + "\">" + (row.screening_result || "-") + "</span></td>" +
            "<td>" + (row.screening_date ? formatDate(row.screening_date) : "-") + "</td>" +
            "<td><button class=\"btn btn-sm btn-warning\" onclick=\"window.openScreening(" + row.candidate_id + "," + row.screening_id + ")\">Edit</button></td>";
        tbody.appendChild(tr);
    });
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"6\" style=\"text-align:center;padding:32px;color:var(--gray-400)\">No completed screenings</td></tr>";
    }
    document.getElementById("statShortlisted").textContent = stats.shortlisted;
    document.getElementById("statRejected").textContent = stats.rejected;
    renderPaginationBar("paginationComplete", filtered.length, totalPages);
}

window.openScreening = function(candidateId, screeningId) {
    document.getElementById("scCandidateId").value = candidateId;
    document.getElementById("scScreeningId").value = screeningId;
    editingScreeningId = screeningId || null;

    if (screeningId) {
        for (var i = 0; i < completeData.length; i++) {
            var row = completeData[i];
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
    apiGet("ajax/list_upcoming.php", null, function(res) {
        if (res.success && res.data) upcomingData = res.data;
        else upcomingData = [];
        renderUpcoming();
    }, function() { upcomingData = []; renderUpcoming(); });
    apiGet("ajax/list_complete.php", null, function(res) {
        if (res.success && res.data) completeData = res.data;
        else completeData = [];
        renderComplete();
    }, function() { completeData = []; renderComplete(); });
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

<div class="tab-bar">
    <button class="tab-btn active" id="tabBtnUpcoming" onclick="switchTab('Upcoming')">&#x1F4C5; Upcoming</button>
    <button class="tab-btn" id="tabBtnComplete" onclick="switchTab('Complete')">&#x2705; Complete</button>
</div>

<div class="tab-content active" id="tabUpcoming">
    <div class="card">
        <div class="card-header">
            <h2>Upcoming Screenings</h2>
            <div class="filter-bar">
                <div class="search-box">
                    <span class="search-icon">&#x1F50D;</span>
                    <input type="text" id="searchUpcoming" placeholder="Search candidate..." onkeyup="applyUpcomingFilter()">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table id="upcomingTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="upcomingTableBody"></tbody>
                </table>
                <div id="paginationUpcoming" class="pagination-bar"></div>
            </div>
        </div>
    </div>
</div>

<div class="tab-content" id="tabComplete">
    <div class="card">
        <div class="card-header">
            <h2>Completed Screenings</h2>
            <div class="filter-bar">
                <div class="search-box">
                    <span class="search-icon">&#x1F50D;</span>
                    <input type="text" id="searchComplete" placeholder="Search candidate..." onkeyup="applyCompleteFilter()">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table id="completeTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Applied Date</th>
                            <th>Screening Result</th>
                            <th>Screening Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="completeTableBody"></tbody>
                </table>
                <div id="paginationComplete" class="pagination-bar"></div>
            </div>
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
