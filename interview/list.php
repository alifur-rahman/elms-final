<?php
$basePath    = '..';
$pageTitle   = 'Interview';
$activePage  = 'interview';
$breadcrumb  = 'Home / Recruitment / Interview';
$pageStyles  = '
.tab-bar { display:flex; gap:0; margin-bottom:24px; border-bottom:2px solid var(--gray-200); }
.tab-bar .tab-btn { padding:12px 24px; border:none; background:none; cursor:pointer; font-size:14px; font-weight:500; color:var(--gray-600); border-bottom:2px solid transparent; margin-bottom:-2px; transition:var(--transition); font-family:inherit; }
.tab-bar .tab-btn.active { color:var(--primary); border-bottom-color:var(--primary); }
.tab-bar .tab-btn:hover { color:var(--primary); }
.tab-content { display:none; }
.tab-content.active { display:block; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: #fff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); display: flex; align-items: center; gap: 14px; }
.stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-card .stat-icon.blue { background: #e8f0fe; color: var(--primary); }
.stat-card .stat-icon.green { background: #e6f4ea; color: var(--success); }
.stat-card .stat-icon.yellow { background: #fef7e0; color: var(--warning); }
.stat-card .stat-info h3 { font-size: 22px; font-weight: 700; line-height: 1.2; }
.stat-card .stat-info p { font-size: 12px; color: var(--gray-500); }
.filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
';
$pageScripts = '
var interviewsData = [];
var eligibleCandidates = [];

function renderInterviews() {
    var upcomingBody = document.getElementById("upcomingTableBody");
    var completedBody = document.getElementById("completedTableBody");
    upcomingBody.innerHTML = "";
    completedBody.innerHTML = "";

    var stats = { scheduled: 0, completed: 0, pendingFeedback: 0 };

    interviewsData.forEach(function(iv) {
        var name = iv.first_name + " " + iv.last_name;

        if (iv.status === "Completed") {
            stats.completed++;
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (iv.job_title || "N/A") + "</td>" +
                "<td>" + formatDate(iv.date) + "</td>" +
                "<td>" + (iv.rating != null ? iv.rating : "-") + "</td>" +
                "<td><span class=\"badge " + getBadgeClass(iv.decision) + "\">" + (iv.decision || "-") + "</span></td>" +
                "<td>" + (iv.feedback || "-") + "</td>";
            completedBody.appendChild(tr);
        } else {
            stats.scheduled++;
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (iv.job_title || "N/A") + "</td>" +
                "<td>" + formatDateTime(iv.date) + "</td>" +
                "<td>" + (iv.type || "-") + "</td>" +
                "<td>" + (iv.interviewer || "-") + "</td>" +
                "<td>" + (iv.round_name || "Round " + iv.round) + "</td>" +
                "<td><span class=\"badge " + getBadgeClass(iv.status) + "\">" + (iv.status || "Scheduled") + "</span></td>" +
                "<td><button class=\"btn btn-sm btn-primary\" onclick=\"window.openFeedback(" + iv.id + ")\">Record Feedback</button></td>";
            upcomingBody.appendChild(tr);
        }
    });

    // Show candidates eligible but without interviews as "Not Scheduled"
    eligibleCandidates.forEach(function(c) {
        var hasInterview = false;
        for (var i = 0; i < interviewsData.length; i++) {
            if (interviewsData[i].candidate_id === c.id) { hasInterview = true; break; }
        }
        if (!hasInterview) {
            stats.pendingFeedback++;
            var name = c.first_name + " " + c.last_name;
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td>-</td><td>-</td><td>-</td><td>-</td>" +
                "<td><span class=\"badge badge-pending\">Not Scheduled</span></td>" +
                "<td><button class=\"btn btn-sm btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.id + ")\">Schedule</button></td>";
            upcomingBody.appendChild(tr);
        }
    });

    document.getElementById("statScheduled").textContent = stats.scheduled;
    document.getElementById("statCompleted").textContent = stats.completed;
    document.getElementById("statPendingFeedback").textContent = stats.pendingFeedback;
}

function switchTab(tab, btn) {
    var btns = document.querySelectorAll(".tab-btn");
    for (var i = 0; i < btns.length; i++) btns[i].classList.remove("active");
    var contents = document.querySelectorAll(".tab-content");
    for (var i = 0; i < contents.length; i++) contents[i].classList.remove("active");
    if (btn) btn.classList.add("active");
    document.getElementById("tab" + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add("active");
    document.getElementById("tableTitle").textContent = tab.charAt(0).toUpperCase() + tab.slice(1) + " Interviews";
}

window.openScheduleModal = function() {
    document.getElementById("scheduleForm").reset();
    loadCandidateSelect();
    openModal("scheduleModal");
};

window.openScheduleForCandidate = function(candidateId) {
    loadCandidateSelect();
    document.getElementById("siCandidateId").value = candidateId;
    openModal("scheduleModal");
};

function loadCandidateSelect() {
    var sel = document.getElementById("siCandidateId");
    sel.innerHTML = "<option value=\"\">Select Candidate</option>";
    eligibleCandidates.forEach(function(c) {
        var opt = document.createElement("option");
        opt.value = c.id;
        opt.textContent = c.first_name + " " + c.last_name + " - " + c.job_title;
        sel.appendChild(opt);
    });
}

window.ajaxScheduleInterview = function() {
    var candidateId = parseInt(document.getElementById("siCandidateId").value);
    var date = document.getElementById("siDate").value;
    var type = document.getElementById("siType").value;
    var interviewer = document.getElementById("siInterviewer").value.trim();
    var round = parseInt(document.getElementById("siRound").value);
    var roundName = document.getElementById("siRoundName").value.trim();

    if (!candidateId || !date || !type || !interviewer || !round) {
        showAlert("Please fill in all required fields", "error");
        return;
    }

    var btn = document.getElementById("siSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "\u23F3 Saving...";

    apiPost("ajax/submit.php", {
        candidate_id: candidateId, date: date, type: type,
        interviewer: interviewer, round: round, round_name: roundName
    }, function(res) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert(res.message || "Interview scheduled!", "success");
        closeModal("scheduleModal");
        renderPage();
    }, function(err) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
    });
};

window.openFeedback = function(interviewId) {
    document.getElementById("feedbackForm").reset();
    document.getElementById("fbInterviewId").value = interviewId;

    for (var i = 0; i < interviewsData.length; i++) {
        var iv = interviewsData[i];
        if (iv.id === interviewId) {
            if (iv.rating) document.getElementById("fbRating").value = iv.rating;
            if (iv.decision) document.getElementById("fbDecision").value = iv.decision;
            if (iv.feedback) document.getElementById("fbFeedback").value = iv.feedback;
            break;
        }
    }

    openModal("feedbackModal");
};

window.ajaxSaveFeedback = function() {
    var interviewId = parseInt(document.getElementById("fbInterviewId").value);
    var rating = parseFloat(document.getElementById("fbRating").value);
    var decision = document.getElementById("fbDecision").value;
    var feedback = document.getElementById("fbFeedback").value.trim();

    if (!rating || !decision) {
        showAlert("Please fill in rating and decision", "error");
        return;
    }
    if (rating < 1 || rating > 5) {
        showAlert("Rating must be between 1 and 5", "error");
        return;
    }

    var btn = document.getElementById("fbSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "\u23F3 Saving...";

    apiPost("ajax/update.php", {
        id: interviewId, rating: rating, decision: decision,
        feedback: feedback, status: "Completed"
    }, function(res) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert(res.message || "Feedback saved!", "success");
        closeModal("feedbackModal");
        renderPage();
    }, function(err) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
    });
};

function renderPage() {
    apiGet("ajax/list.php", null, function(res) {
        if (res.success && res.data) interviewsData = res.data;
        apiGet("ajax/candidates.php", null, function(res2) {
            if (res2.success && res2.data) eligibleCandidates = res2.data;
            renderInterviews();
        }, function() { renderInterviews(); });
    }, function() {
        apiGet("ajax/candidates.php", null, function(res2) {
            if (res2.success && res2.data) eligibleCandidates = res2.data;
            renderInterviews();
        }, function() { renderInterviews(); });
    });
}

renderPage();
';

ob_start();
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">&#x1F4C5;</div>
        <div class="stat-info"><h3 id="statScheduled">0</h3><p>Total Scheduled</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#x2705;</div>
        <div class="stat-info"><h3 id="statCompleted">0</h3><p>Completed</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">&#x23F3;</div>
        <div class="stat-info"><h3 id="statPendingFeedback">0</h3><p>Pending Scheduling</p></div>
    </div>
</div>

<div class="tab-bar">
    <button class="tab-btn active" onclick="switchTab('upcoming',this)">Upcoming</button>
    <button class="tab-btn" onclick="switchTab('completed',this)">Completed</button>
</div>

<div class="card">
    <div class="card-header">
        <h2 id="tableTitle">Upcoming Interviews</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#x1F50D;</span>
                <input type="text" id="searchInput" placeholder="Search..." onkeyup="filterTable('searchInput','upcomingTable')">
            </div>
            <button class="btn btn-primary" onclick="window.openScheduleModal()">+ Schedule Interview</button>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content active" id="tabUpcoming">
            <div class="table-container">
                <table id="upcomingTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Date / Time</th>
                            <th>Type</th>
                            <th>Interviewer</th>
                            <th>Round</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="upcomingTableBody"></tbody>
                </table>
            </div>
        </div>
        <div class="tab-content" id="tabCompleted">
            <div class="table-container">
                <table id="completedTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Date</th>
                            <th>Rating</th>
                            <th>Decision</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody id="completedTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal-overlay" id="scheduleModal" onclick="if(event.target===this)closeModal('scheduleModal')">
    <div class="modal">
        <div class="modal-header">
            <h3>Schedule Interview</h3>
            <button class="modal-close" onclick="closeModal('scheduleModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="scheduleForm" onsubmit="event.preventDefault();window.ajaxScheduleInterview()">
                <div class="form-group">
                    <label>Candidate <span class="required">*</span></label>
                    <select class="form-control" id="siCandidateId" required></select>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Interview Date & Time <span class="required">*</span></label>
                        <input type="datetime-local" class="form-control" id="siDate" required>
                    </div>
                    <div class="form-group">
                        <label>Interview Type <span class="required">*</span></label>
                        <select class="form-control" id="siType" required>
                            <option value="">Select</option>
                            <option value="Phone">Phone</option>
                            <option value="Video">Video</option>
                            <option value="In-Person">In-Person</option>
                            <option value="Technical">Technical</option>
                            <option value="Panel">Panel</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Interviewer Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="siInterviewer" required>
                    </div>
                    <div class="form-group">
                        <label>Round <span class="required">*</span></label>
                        <input type="number" class="form-control" id="siRound" min="1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Round Name</label>
                    <input type="text" class="form-control" id="siRoundName" placeholder="e.g. Technical Screening">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('scheduleModal')">Cancel</button>
            <button class="btn btn-primary" id="siSubmitBtn" onclick="window.ajaxScheduleInterview()">&#x2728; Schedule Interview</button>
        </div>
    </div>
</div>

<!-- Record Feedback Modal -->
<div class="modal-overlay" id="feedbackModal" onclick="if(event.target===this)closeModal('feedbackModal')">
    <div class="modal">
        <div class="modal-header">
            <h3>Record Feedback</h3>
            <button class="modal-close" onclick="closeModal('feedbackModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="feedbackForm" onsubmit="event.preventDefault();window.ajaxSaveFeedback()">
                <input type="hidden" id="fbInterviewId">

                <div class="form-group">
                    <label>Rating (1-5) <span class="required">*</span></label>
                    <input type="number" class="form-control" id="fbRating" min="1" max="5" step="0.5" required>
                </div>
                <div class="form-group">
                    <label>Decision <span class="required">*</span></label>
                    <select class="form-control" id="fbDecision" required>
                        <option value="">Select</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                        <option value="Hold">Hold</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Feedback</label>
                    <textarea class="form-control" id="fbFeedback" rows="4" placeholder="Enter detailed feedback..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('feedbackModal')">Cancel</button>
            <button class="btn btn-primary" id="fbSubmitBtn" onclick="window.ajaxSaveFeedback()">&#x2728; Save Feedback</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
