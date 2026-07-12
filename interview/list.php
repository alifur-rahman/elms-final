<?php
$basePath    = '..';
$pageTitle   = 'Interview';
$activePage  = 'interview';
$breadcrumb  = 'Home / Recruitment / Interview';
$pageStyles  = '
.stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; margin-bottom: 24px; }
.stat-card { background: #fff; border-radius: 8px; padding: 16px 18px; box-shadow: 0 1px 3px rgba(0,0,0,.08); display: flex; align-items: center; gap: 12px; }
.stat-card .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.stat-card .stat-icon.blue { background: #e8f0fe; color: var(--primary); }
.stat-card .stat-icon.green { background: #e6f4ea; color: var(--success); }
.stat-card .stat-icon.yellow { background: #fef7e0; color: var(--warning); }
.stat-card .stat-icon.red { background: #fce8e6; color: var(--danger); }
.stat-card .stat-icon.purple { background: #f3e8fd; color: #7c3aed; }
.stat-card .stat-info h3 { font-size: 20px; font-weight: 700; line-height: 1.2; }
.stat-card .stat-info p { font-size: 11px; color: var(--gray-500); }
.filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.score-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.score-grid .form-group { margin-bottom: 0; }
.overall-rating { text-align: center; padding: 12px; background:#f8f9fa; border-radius:8px; margin:12px 0; }
.overall-rating .or-value { font-size: 28px; font-weight:700; color:var(--primary); }
.overall-rating .or-label { font-size: 11px; color:var(--gray-500); text-transform:uppercase; letter-spacing:.5px; }
.final-badge { font-size:10px; background:#e8f0fe; color:var(--primary); padding:2px 8px; border-radius:4px; font-weight:600; }
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
var candidatesData = [];
var eligibleCandidates = [];
var currentPage = 1;
var perPage = 10;

function getFilteredData() {
    var q = document.getElementById("searchInput").value.trim().toLowerCase();
    return candidatesData.filter(function(c) {
        var name = (c.first_name + " " + c.last_name + " " + (c.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
}

function renderPaginationBar(total, totalPages) {
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
    document.getElementById("paginationBar").innerHTML = html;
}

function goToPage(p) {
    var filtered = getFilteredData();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (p < 1) p = 1;
    if (p > totalPages) p = totalPages;
    if (p === currentPage) return;
    currentPage = p;
    renderTable();
}

function changePerPage(n) {
    perPage = parseInt(n);
    currentPage = 1;
    renderTable();
}

function applyFilters() {
    currentPage = 1;
    renderTable();
}

function renderTable() {
    var filtered = getFilteredData();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("ivTableBody");
    tbody.innerHTML = "";

    var stats = { schedule: 0, feedback: 0, next_round: 0, done: 0 };

    candidatesData.forEach(function(c) {
        var st = c.action_state;
        stats[st] = (stats[st] || 0) + 1;
    });

    pageData.forEach(function(c) {
        var name = c.first_name + " " + c.last_name;
        var st = c.action_state;

        var roundLabel = c.round_name || (c.round ? "Round " + c.round : "-");
        var finalLabel = c.is_final_round ? " <span class=\"final-badge\">FINAL</span>" : "";
        var dt = c.date ? formatDateTime(c.date) : "-";
        var typeVal = c.type || "-";
        var interviewerVal = c.interviewer || "-";

        var statusLabel, statusBadgeClass;
        if (!c.interview_id) {
            statusLabel = "Not Scheduled";
            statusBadgeClass = "badge-pending";
        } else if (c.interview_status === "Waiting for Feedback") {
            statusLabel = "Waiting for Feedback";
            statusBadgeClass = "badge-pending";
        } else if (c.interview_status === "Completed" && c.decision === "Pass") {
            statusLabel = c.is_final_round ? "Selected" : "Passed";
            statusBadgeClass = c.is_final_round ? "badge-success" : getBadgeClass("Pass");
        } else if (c.interview_status === "Completed" && c.decision === "Fail") {
            statusLabel = "Rejected";
            statusBadgeClass = "badge-danger";
        } else if (c.interview_status === "Completed" && c.decision === "Hold") {
            statusLabel = "On Hold";
            statusBadgeClass = "badge-warning";
        } else {
            statusLabel = c.interview_status || "-";
            statusBadgeClass = getBadgeClass(statusLabel);
        }

        var decisionHtml = c.decision
            ? "<span class=\"badge " + getBadgeClass(c.decision) + "\">" + c.decision + "</span>"
            : "-";

        var actionsHtml = "";
        if (st === "schedule") {
            actionsHtml = "<button class=\"btn btn-sm btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Schedule Interview\',false)\">Schedule</button>";
        } else if (st === "feedback") {
            actionsHtml =
                "<button class=\"btn btn-sm btn-primary\" onclick=\"window.openFeedback(" + c.interview_id + ")\" style=\"margin-right:4px\">Feedback</button>" +
                "<button class=\"btn btn-sm btn-outline\" onclick=\"window.editInterview(" + c.interview_id + ")\">Edit</button>";
        } else if (st === "next_round") {
            actionsHtml =
                "<button class=\"btn btn-sm btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Next Round Interview\',false)\" style=\"margin-right:4px\">Next Round</button>" +
                "<button class=\"btn btn-sm btn-outline\" onclick=\"window.openFeedbackView(" + c.interview_id + ")\">View</button>";
        } else {
            if (c.decision === "Fail" || c.decision === "Hold") {
                actionsHtml =
                    "<button class=\"btn btn-sm btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Reschedule Interview\',true)\" style=\"margin-right:4px\">Reschedule</button>" +
                    "<button class=\"btn btn-sm btn-outline\" onclick=\"window.openFeedbackView(" + c.interview_id + ")\">View</button>";
            } else {
                actionsHtml = "<button class=\"btn btn-sm btn-outline\" onclick=\"window.openFeedbackView(" + c.interview_id + ")\">View</button>";
            }
        }

        var historyBtn = c.interview_id
            ? " <button class=\"btn btn-sm btn-outline\" onclick=\"window.openInterviewHistory(" + c.candidate_id + ")\" title=\"History\">\u{1F4CB}</button>"
            : "";

        var tr = document.createElement("tr");
        tr.innerHTML =
            "<td><strong>" + name + "</strong></td>" +
            "<td>" + (c.job_title || "N/A") + "</td>" +
            "<td>" + roundLabel + finalLabel + "</td>" +
            "<td>" + typeVal + "</td>" +
            "<td>" + interviewerVal + "</td>" +
            "<td>" + dt + "</td>" +
            "<td><span class=\"badge " + statusBadgeClass + "\">" + statusLabel + "</span></td>" +
            "<td>" + decisionHtml + "</td>" +
            "<td style=\"white-space:nowrap\">" + actionsHtml + historyBtn + "</td>";
        tbody.appendChild(tr);
    });

    if (pageData.length === 0) {
        var cols = document.querySelectorAll("#interviewTable thead th").length;
        tbody.innerHTML = "<tr><td colspan=\"" + cols + "\" style=\"text-align:center;padding:32px;color:var(--gray-400)\">No candidates found</td></tr>";
    }

    document.getElementById("statSchedule").textContent = stats.schedule || 0;
    document.getElementById("statFeedback").textContent = stats.feedback || 0;
    document.getElementById("statNextRound").textContent = stats.next_round || 0;
    document.getElementById("statDone").textContent = (stats.done || 0);

    renderPaginationBar(filtered.length, totalPages);
}

window.editInterview = function(interviewId) {
    document.getElementById("scheduleForm").reset();
    document.getElementById("siRescheduleId").value = interviewId;
    document.getElementById("siRoundWrapper").style.display = "none";
    document.getElementById("scheduleModalTitle").textContent = "Edit Interview";
    document.getElementById("siSubmitBtn").innerHTML = "&#x270F; Update";
    loadCandidateSelect();

    for (var i = 0; i < candidatesData.length; i++) {
        var c = candidatesData[i];
        if (c.interview_id === interviewId) {
            document.getElementById("siCandidateId").value = c.candidate_id;
            document.getElementById("siCandidateId").disabled = true;
            document.getElementById("siDate").value = c.date ? c.date.replace(" ", "T") : "";
            document.getElementById("siType").value = c.type || "";
            document.getElementById("siInterviewer").value = c.interviewer || "";
            document.getElementById("siRoundName").value = c.round_name || "";
            document.getElementById("siIsFinalRound").checked = c.is_final_round;
            break;
        }
    }
    openModal("scheduleModal");
};

window.openScheduleModal = function() {
    document.getElementById("scheduleForm").reset();
    document.getElementById("siRescheduleId").value = "";
    document.getElementById("siCandidateId").disabled = false;
    document.getElementById("siIsFinalRound").checked = false;
    document.getElementById("siRoundWrapper").style.display = "block";
    document.getElementById("scheduleModalTitle").textContent = "Schedule Interview";
    document.getElementById("siSubmitBtn").textContent = "\u2728 Schedule Interview";
    loadCandidateSelect();
    openModal("scheduleModal");
};

window.openScheduleForCandidate = function(candidateId, title, disableSelect) {
    document.getElementById("scheduleForm").reset();
    document.getElementById("siRescheduleId").value = "";
    loadCandidateSelect();

    // Ensure candidate exists in select (may not be in eligibleCandidates for Fail/Hold)
    var sel = document.getElementById("siCandidateId");
    var found = false;
    for (var i = 0; i < sel.options.length; i++) {
        if (parseInt(sel.options[i].value) === candidateId) { found = true; break; }
    }
    if (!found) {
        var name = "";
        for (var i = 0; i < candidatesData.length; i++) {
            if (candidatesData[i].candidate_id === candidateId) {
                name = candidatesData[i].first_name + " " + candidatesData[i].last_name + " - " + (candidatesData[i].job_title || "N/A");
                break;
            }
        }
        var opt = document.createElement("option");
        opt.value = candidateId;
        opt.textContent = name || ("Candidate #" + candidateId);
        sel.appendChild(opt);
    }

    document.getElementById("siCandidateId").value = candidateId;
    document.getElementById("siCandidateId").disabled = disableSelect !== false;
    document.getElementById("siIsFinalRound").checked = false;
    document.getElementById("siRoundWrapper").style.display = "block";
    document.getElementById("scheduleModalTitle").textContent = title || "Schedule Interview";
    document.getElementById("siSubmitBtn").textContent = "\u2728 " + (title || "Schedule Interview");
    onCandidateSelect();
    openModal("scheduleModal");
};

function onCandidateSelect() {
    var cid = parseInt(document.getElementById("siCandidateId").value);
    if (!cid) return;
    for (var i = 0; i < eligibleCandidates.length; i++) {
        var c = eligibleCandidates[i];
        if (c.id === cid) {
            document.getElementById("siRound").value = c.next_round || 1;
            document.getElementById("siRoundName").value = "";
            break;
        }
    }
}

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
    var rescheduleId = document.getElementById("siRescheduleId").value;
    var candidateId = parseInt(document.getElementById("siCandidateId").value);
    var date = document.getElementById("siDate").value;
    var type = document.getElementById("siType").value;
    var interviewer = document.getElementById("siInterviewer").value.trim();
    var round = rescheduleId ? 0 : parseInt(document.getElementById("siRound").value);
    var roundName = document.getElementById("siRoundName").value.trim();
    var isFinal = document.getElementById("siIsFinalRound").checked ? 1 : 0;

    if (!candidateId || !date || !type || !interviewer) {
        showAlert("Please fill in all required fields", "error");
        return;
    }
    if (!rescheduleId && !round) {
        showAlert("Round is required", "error");
        return;
    }

    var btn = document.getElementById("siSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "\u23F3 Saving...";

    if (rescheduleId) {
        apiPost("ajax/update.php", {
            id: parseInt(rescheduleId),
            date: date, type: type,
            interviewer: interviewer, round_name: roundName,
            is_final_round: isFinal
        }, function(res) {
            btn.disabled = false;
            btn.innerHTML = origText;
            showAlert(res.message || "Interview rescheduled!", "success");
            currentPage = 1;
            closeModal("scheduleModal");
            renderPage();
        }, function(err) {
            btn.disabled = false;
            btn.innerHTML = origText;
            showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
        });
    } else {
        apiPost("ajax/submit.php", {
            candidate_id: candidateId, date: date, type: type,
            interviewer: interviewer, round: round, round_name: roundName,
            is_final_round: isFinal
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
    }
};

window.openInterviewHistory = function(candidateId) {
    var historyBody = document.getElementById("historyBody");
    historyBody.innerHTML = "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">Loading...</div>";
    openModal("historyModal");

    var cName = "";
    for (var i = 0; i < candidatesData.length; i++) {
        if (candidatesData[i].candidate_id === candidateId) { cName = candidatesData[i].first_name + " " + candidatesData[i].last_name; break; }
    }
    document.getElementById("historyModalTitle").textContent = "Interview History - " + cName;

    apiGet("ajax/all_interviews.php?candidate_id=" + candidateId, null, function(res) {
        if (!res.success || !res.data) {
            historyBody.innerHTML = "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">Failed to load history.</div>";
            return;
        }
        var candidateInterviews = res.data;
        if (candidateInterviews.length === 0) {
            historyBody.innerHTML = "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">No interview records yet.</div>";
            return;
        }

        var html = "<div class=\"timeline\">";
        for (var i = 0; i < candidateInterviews.length; i++) {
            var iv = candidateInterviews[i];
            var isCompleted = iv.status === "Completed";
            var statusIcon = isCompleted ? "\u2705" : "\u23F3";
            var statusClass = isCompleted ? "timeline-completed" : "timeline-pending";
            var scores = iv.score_technical ? "T:" + iv.score_technical + " C:" + iv.score_communication + " PS:" + iv.score_problem_solving + " A:" + iv.score_attitude : "-";
            var decisionBadge = iv.decision ? "<span class=\"badge " + getBadgeClass(iv.decision) + "\" style=\"margin-left:8px\">" + iv.decision + "</span>" : "";
            html +=
                "<div class=\"timeline-item " + statusClass + "\">" +
                    "<div class=\"timeline-marker\">" + statusIcon + "</div>" +
                    "<div class=\"timeline-content\">" +
                        "<div style=\"display:flex;justify-content:space-between;align-items:center\">" +
                            "<strong>" + (iv.round_name || "Round " + iv.round) + "</strong>" +
                            "<span style=\"font-size:12px;color:var(--gray-400)\">" + formatDateTime(iv.date) + "</span>" +
                        "</div>" +
                        "<div style=\"font-size:12px;margin-top:4px\">" +
                            iv.type + " | " + iv.interviewer +
                        "</div>" +
                        "<div style=\"font-size:12px;margin-top:4px\">" +
                            "Scores: " + scores +
                        "</div>" +
                        "<div style=\"font-size:12px;margin-top:4px\">" +
                            "Overall: " + (iv.rating != null ? iv.rating : "-") +
                            decisionBadge +
                            (iv.is_final_round ? " <span class=\"final-badge\">FINAL</span>" : "") +
                        "</div>" +
                        (iv.feedback ? "<div style=\"font-size:12px;margin-top:4px;color:var(--gray-600);border-top:1px solid var(--gray-100);padding-top:4px\">" + iv.feedback + "</div>" : "") +
                    "</div>" +
                "</div>";
        }
        html += "</div>";
        historyBody.innerHTML = html;
    }, function() {
        historyBody.innerHTML = "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">Failed to load history.</div>";
    });
};

function calcOverall() {
    var tech = parseFloat(document.getElementById("fbTechnical").value) || 0;
    var comm = parseFloat(document.getElementById("fbCommunication").value) || 0;
    var prob = parseFloat(document.getElementById("fbProblemSolving").value) || 0;
    var att = parseFloat(document.getElementById("fbAttitude").value) || 0;
    var count = 0, sum = 0;
    if (tech > 0) { sum += tech; count++; }
    if (comm > 0) { sum += comm; count++; }
    if (prob > 0) { sum += prob; count++; }
    if (att > 0) { sum += att; count++; }
    var avg = count > 0 ? (sum / count).toFixed(1) : "-";
    document.getElementById("overallRatingValue").textContent = avg;
}

window.openFeedback = function(interviewId) {
    document.getElementById("feedbackForm").reset();
    document.getElementById("fbInterviewId").value = interviewId;
    document.getElementById("fbRejectReason").style.display = "none";

    for (var i = 0; i < candidatesData.length; i++) {
        var c = candidatesData[i];
        if (c.interview_id === interviewId) {
            if (c.score_technical) document.getElementById("fbTechnical").value = c.score_technical;
            if (c.score_communication) document.getElementById("fbCommunication").value = c.score_communication;
            if (c.score_problem_solving) document.getElementById("fbProblemSolving").value = c.score_problem_solving;
            if (c.score_attitude) document.getElementById("fbAttitude").value = c.score_attitude;
            if (c.decision) document.getElementById("fbDecision").value = c.decision;
            if (c.reject_reason) { document.getElementById("fbRejectReasonText").value = c.reject_reason; document.getElementById("fbRejectReason").style.display = "block"; }
            if (c.feedback) document.getElementById("fbFeedbackText").value = c.feedback;
            document.getElementById("fbIsFinalRound").checked = c.is_final_round;
            calcOverall();
            break;
        }
    }

    document.getElementById("feedbackModalTitle").innerHTML = "&#x270F; Record Feedback";
    document.getElementById("fbSubmitBtn").innerHTML = "&#x2728; Save Feedback";
    openModal("feedbackModal");
};

window.openFeedbackView = function(interviewId) {
    var iv = null;
    for (var i = 0; i < candidatesData.length; i++) { if (candidatesData[i].interview_id === interviewId) { iv = candidatesData[i]; break; } }
    if (!iv) return;

    var html = "<div class=\"score-grid\">";
    html += "<div class=\"form-group\"><label>Technical Skill</label><div><strong>" + (iv.score_technical || "-") + "</strong> / 5</div></div>";
    html += "<div class=\"form-group\"><label>Communication</label><div><strong>" + (iv.score_communication || "-") + "</strong> / 5</div></div>";
    html += "<div class=\"form-group\"><label>Problem Solving</label><div><strong>" + (iv.score_problem_solving || "-") + "</strong> / 5</div></div>";
    html += "<div class=\"form-group\"><label>Attitude</label><div><strong>" + (iv.score_attitude || "-") + "</strong> / 5</div></div>";
    html += "</div>";
    html += "<div class=\"overall-rating\"><div class=\"or-value\">" + (iv.rating || "-") + "</div><div class=\"or-label\">Overall Rating</div></div>";
    html += "<div class=\"form-group\"><label>Decision</label><div><span class=\"badge " + getBadgeClass(iv.decision) + "\">" + (iv.decision || "-") + "</span></div></div>";
    if (iv.reject_reason) html += "<div class=\"form-group\"><label>Reject Reason</label><div>" + iv.reject_reason + "</div></div>";
    if (iv.feedback) html += "<div class=\"form-group\" style=\"margin-top:12px\"><label>Feedback</label><div style=\"font-size:13px;color:var(--gray-700);line-height:1.5\">" + iv.feedback + "</div></div>";

    document.getElementById("feedbackViewBody").innerHTML = html;
    openModal("feedbackViewModal");
};

function onDecisionChange() {
    var val = document.getElementById("fbDecision").value;
    document.getElementById("fbRejectReason").style.display = (val === "Fail") ? "block" : "none";
}

window.ajaxSaveFeedback = function() {
    var interviewId = parseInt(document.getElementById("fbInterviewId").value);
    var technical = parseFloat(document.getElementById("fbTechnical").value);
    var communication = parseFloat(document.getElementById("fbCommunication").value);
    var problemSolving = parseFloat(document.getElementById("fbProblemSolving").value);
    var attitude = parseFloat(document.getElementById("fbAttitude").value);
    var decision = document.getElementById("fbDecision").value;
    var rejectReason = document.getElementById("fbRejectReasonText").value.trim();
    var feedback = document.getElementById("fbFeedbackText").value.trim();
    var isFinal = document.getElementById("fbIsFinalRound").checked ? 1 : 0;

    if (!technical || !communication || !problemSolving || !attitude || !decision) {
        showAlert("Please fill in all category scores and decision", "error");
        return;
    }
    if (technical < 1 || technical > 5 || communication < 1 || communication > 5 ||
        problemSolving < 1 || problemSolving > 5 || attitude < 1 || attitude > 5) {
        showAlert("All scores must be between 1 and 5", "error");
        return;
    }

    var btn = document.getElementById("fbSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "\u23F3 Saving...";

    apiPost("ajax/update.php", {
        id: interviewId,
        score_technical: technical,
        score_communication: communication,
        score_problem_solving: problemSolving,
        score_attitude: attitude,
        decision: decision,
        reject_reason: rejectReason,
        feedback: feedback,
        status: "Completed",
        is_final_round: isFinal
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
        if (res.success && res.data) candidatesData = res.data;
        apiGet("ajax/candidates.php", null, function(res2) {
            if (res2.success && res2.data) eligibleCandidates = res2.data;
            currentPage = 1;
            renderTable();
        }, function() { currentPage = 1; renderTable(); });
    }, function() {
        apiGet("ajax/candidates.php", null, function(res2) {
            if (res2.success && res2.data) eligibleCandidates = res2.data;
            currentPage = 1;
            renderTable();
        }, function() { currentPage = 1; renderTable(); });
    });
}

renderPage();
';

ob_start();
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">&#x1F4C5;</div>
        <div class="stat-info"><h3 id="statSchedule">0</h3><p>Needs Scheduling</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">&#x23F3;</div>
        <div class="stat-info"><h3 id="statFeedback">0</h3><p>Waiting Feedback</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">&#x1F504;</div>
        <div class="stat-info"><h3 id="statNextRound">0</h3><p>Ready for Next Round</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#x2705;</div>
        <div class="stat-info"><h3 id="statDone">0</h3><p>Completed</p></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Interview Candidates</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#x1F50D;</span>
                <input type="text" id="searchInput" placeholder="Search..." onkeyup="applyFilters()">
            </div>
            <button class="btn btn-primary" onclick="window.openScheduleModal()">+ Schedule Interview</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table id="interviewTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Round</th>
                        <th>Type</th>
                        <th>Interviewer</th>
                        <th>Date / Time</th>
                        <th>Status</th>
                        <th>Decision</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ivTableBody"></tbody>
            </table>
            <div id="paginationBar" class="pagination-bar"></div>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal-overlay" id="scheduleModal" onclick="if(event.target===this)closeModal('scheduleModal')">
    <div class="modal">
        <div class="modal-header">
            <h3 id="scheduleModalTitle">Schedule Interview</h3>
            <button class="modal-close" onclick="closeModal('scheduleModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="scheduleForm" onsubmit="event.preventDefault();window.ajaxScheduleInterview()">
                <input type="hidden" id="siRescheduleId" value="">
                <div class="form-group">
                    <label>Candidate <span class="required">*</span></label>
                    <select class="form-control" id="siCandidateId" required onchange="onCandidateSelect()"></select>
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
                    <div class="form-group" id="siRoundWrapper">
                        <label>Round <span class="required">*</span></label>
                        <input type="number" class="form-control" id="siRound" min="1" readonly style="background:#f0f0f0;cursor:not-allowed" required>
                        <small style="color:var(--gray-400);font-size:11px">Auto-calculated from existing interviews</small>
                    </div>
                </div>
                <div class="form-group">
                    <label>Round Name</label>
                    <input type="text" class="form-control" id="siRoundName" placeholder="e.g. Technical Screening">
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="checkbox-label" style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" id="siIsFinalRound" style="width:16px;height:16px;cursor:pointer">
                        <span style="font-size:13px;font-weight:500">This is the final round (passing will mark candidate as Selected)</span>
                    </label>
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
            <h3 id="feedbackModalTitle">&#x270F; Record Feedback</h3>
            <button class="modal-close" onclick="closeModal('feedbackModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="feedbackForm" onsubmit="event.preventDefault();window.ajaxSaveFeedback()">
                <input type="hidden" id="fbInterviewId">

                <div class="form-section-title">Category Scores (1-5)</div>
                <div class="score-grid">
                    <div class="form-group">
                        <label>Technical Skill <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbTechnical" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                    <div class="form-group">
                        <label>Communication <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbCommunication" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                    <div class="form-group">
                        <label>Problem Solving <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbProblemSolving" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                    <div class="form-group">
                        <label>Attitude <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbAttitude" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                </div>

                <div class="overall-rating">
                    <div class="or-value" id="overallRatingValue">-</div>
                    <div class="or-label">Overall Rating</div>
                </div>

                <div class="form-group">
                    <label>Decision <span class="required">*</span></label>
                    <select class="form-control" id="fbDecision" onchange="onDecisionChange()" required>
                        <option value="">Select</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                        <option value="Hold">Hold</option>
                    </select>
                </div>

                <div class="form-group" id="fbRejectReason" style="display:none">
                    <label>Reject Reason <span class="required">*</span></label>
                    <select class="form-control" id="fbRejectReasonText">
                        <option value="">Select</option>
                        <option value="Lack of technical skills">Lack of technical skills</option>
                        <option value="Poor communication">Poor communication</option>
                        <option value="Experience mismatch">Experience mismatch</option>
                        <option value="Cultural fit">Cultural fit</option>
                        <option value="Salary expectation mismatch">Salary expectation mismatch</option>
                        <option value="Not interested">Not interested</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Feedback Notes</label>
                    <textarea class="form-control" id="fbFeedbackText" rows="3" placeholder="Enter detailed feedback..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="checkbox-label" style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" id="fbIsFinalRound" style="width:16px;height:16px;cursor:pointer">
                        <span style="font-size:13px;font-weight:500">Mark as final round</span>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('feedbackModal')">Cancel</button>
            <button class="btn btn-primary" id="fbSubmitBtn" onclick="window.ajaxSaveFeedback()">&#x2728; Save Feedback</button>
        </div>
    </div>
</div>

<!-- View Feedback Modal (read-only) -->
<div class="modal-overlay" id="feedbackViewModal" onclick="if(event.target===this)closeModal('feedbackViewModal')">
    <div class="modal">
        <div class="modal-header">
            <h3>Feedback Details</h3>
            <button class="modal-close" onclick="closeModal('feedbackViewModal')">&times;</button>
        </div>
        <div class="modal-body" id="feedbackViewBody"></div>
        <div class="modal-footer">
            <button class="btn btn-outline btn-sm" onclick="closeModal('feedbackViewModal')">Close</button>
        </div>
    </div>
</div>

<!-- Interview History Modal -->
<div class="modal-overlay" id="historyModal" onclick="if(event.target===this)closeModal('historyModal')">
    <div class="modal">
        <div class="modal-header">
            <h3 id="historyModalTitle">Interview History</h3>
            <button class="modal-close" onclick="closeModal('historyModal')">&times;</button>
        </div>
        <div class="modal-body" id="historyBody"></div>
        <div class="modal-footer">
            <button class="btn btn-outline btn-sm" onclick="closeModal('historyModal')">Close</button>
        </div>
    </div>
</div>

<style>
.timeline { position:relative; padding-left:40px; }
.timeline::before { content:""; position:absolute; left:18px; top:0; bottom:0; width:2px; background:var(--gray-200); }
.timeline-item { position:relative; margin-bottom:20px; }
.timeline-marker { position:absolute; left:-40px; top:0; width:36px; height:36px; border-radius:50%; background:#fff; border:2px solid var(--gray-200); display:flex; align-items:center; justify-content:center; font-size:16px; }
.timeline-completed .timeline-marker { border-color:var(--success); }
.timeline-pending .timeline-marker { border-color:var(--warning); }
.timeline-content { background:#f8f9fa; border-radius:8px; padding:12px 16px; border:1px solid var(--gray-100); }
</style>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
