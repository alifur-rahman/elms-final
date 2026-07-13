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
@media (max-width:768px){ .score-grid { grid-template-columns: 1fr; gap: 8px; } }
@media (max-width:480px){ .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } .stat-card { padding: 12px; gap: 8px; } .stat-card .stat-icon { width: 34px; height: 34px; font-size: 15px; } .stat-card .stat-info h3 { font-size: 17px; } }
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
    if (totalPages <= 1) { document.getElementById("paginationBar").innerHTML = ""; return; }
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
                "<button class=\"btn btn-sm btn-outline\" onclick=\"window.editInterview(" + c.interview_id + ")\" style=\"margin-right:4px\">Edit</button>" +
                "<button class=\"btn btn-sm btn-outline\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
        } else if (st === "next_round") {
            actionsHtml =
                "<button class=\"btn btn-sm btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Next Round Interview\',false)\" style=\"margin-right:4px\">Next Round</button>" +
                "<button class=\"btn btn-sm btn-outline\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
        } else {
            if (c.decision === "Fail" || c.decision === "Hold") {
                actionsHtml =
                    "<button class=\"btn btn-sm btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Reschedule Interview\',true," + (c.interview_id || 0) + ")\" style=\"margin-right:4px\">Reschedule</button>" +
                    "<button class=\"btn btn-sm btn-outline\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
            } else {
                actionsHtml = "<button class=\"btn btn-sm btn-outline\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
            }
        }

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
            "<td style=\"white-space:nowrap\">" + actionsHtml + "</td>";
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
    document.getElementById("scheduleModalTitle").textContent = "Schedule Interview";
    document.getElementById("siSubmitBtn").textContent = "\u2728 Schedule Interview";
    loadCandidateSelect();
    openModal("scheduleModal");
};

window.openScheduleForCandidate = function(candidateId, title, disableSelect, interviewId) {
    document.getElementById("scheduleForm").reset();
    document.getElementById("siRescheduleId").value = "";
    document.getElementById("siOldInterviewId").value = interviewId || "";
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
    var oldInterviewId = document.getElementById("siOldInterviewId").value;
    var candidateId = parseInt(document.getElementById("siCandidateId").value);
    var date = document.getElementById("siDate").value;
    var type = document.getElementById("siType").value;
    var interviewer = document.getElementById("siInterviewer").value.trim();
    var roundName = document.getElementById("siRoundName").value.trim();
    var isFinal = document.getElementById("siIsFinalRound").checked ? 1 : 0;

    if (!candidateId || !date || !type || !interviewer) {
        showAlert("Please fill in all required fields", "error");
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
            interviewer: interviewer, round_name: roundName,
            is_final_round: isFinal,
            reschedule_id: parseInt(oldInterviewId) || 0
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

window.openCombinedView = function(candidateId) {
    var iv = null;
    var cName = "";
    for (var i = 0; i < candidatesData.length; i++) {
        if (candidatesData[i].candidate_id === candidateId) {
            iv = candidatesData[i];
            cName = iv.first_name + " " + iv.last_name;
            break;
        }
    }
    if (!iv) return;

    document.getElementById("combinedViewTitle").textContent = "Interview Logs of - " + cName;

    document.getElementById("combinedViewBody").innerHTML = "<div id=\"combinedHistoryBody\"><div style=\"text-align:center;padding:24px;color:var(--gray-400)\">Loading...</div></div>";
    openModal("combinedViewModal");

    apiGet("ajax/all_interviews.php?candidate_id=" + candidateId, null, function(res) {
        if (!res.success || !res.data) {
            document.getElementById("combinedHistoryBody").innerHTML = "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">Failed to load history.</div>";
            return;
        }
        var data = res.data;
        var screening = res.screening;
        var h = "<div class=\"iv-logs\">";

        // Screening card (if exists)
        if (screening) {
            var scrResult = screening.result || "";
            var scrResultBadge = "";
            if (scrResult === "Shortlist") scrResultBadge = "<span class=\"badge badge-completed\">Shortlisted</span>";
            else if (scrResult === "Hold") scrResultBadge = "<span class=\"badge badge-pending\">Hold</span>";
            else if (scrResult === "Reject") scrResultBadge = "<span class=\"badge\" style=\"background:#fce8e6;color:#d93025\">Rejected</span>";

            h +=
                "<div class=\"iv-card\">" +
                    "<div class=\"iv-card-header\" style=\"border-left:4px solid #3b82f6\">" +
                        "<div class=\"iv-card-header-left\">" +
                            "<span class=\"iv-round\">Screening</span>" +
                            "<span class=\"iv-round-name\">Feedback</span>" +
                        "</div>" +
                        "<div class=\"iv-card-header-right\">" +
                            scrResultBadge +
                            "<span class=\"iv-date\">" + (screening.date || "") + "</span>" +
                        "</div>" +
                    "</div>" +
                    "<div class=\"iv-card-body\">" +
                        "<div class=\"iv-meta\">" +
                            "<span><strong>Screened by:</strong> " + (screening.screened_by || "-") + "</span>" +
                        "</div>" +
                        "<div class=\"iv-scores\">" +
                            "<div class=\"iv-score-item\"><label>Rating</label><div class=\"iv-score-bar\"><div class=\"iv-score-fill iv-score-fill-blue\" style=\"width:" + ((screening.rating || 0) / 5 * 100) + "%\"></div></div><span>" + (screening.rating != null ? screening.rating : "-") + "/5</span></div>" +
                            "<div class=\"iv-score-item\"><label>Communication</label><div class=\"iv-score-bar\"><div class=\"iv-score-fill iv-score-fill-blue\" style=\"width:" + ((screening.communication_score || 0) / 5 * 100) + "%\"></div></div><span>" + (screening.communication_score != null ? screening.communication_score : "-") + "/5</span></div>" +
                        "</div>" +
                        "<div class=\"iv-scores\" style=\"margin-top:4px\">" +
                            "<div class=\"iv-score-item\"><label>Experience</label><span style=\"font-size:12px;font-weight:600;color:var(--gray-700)\">" + (screening.experience_match || "-") + "</span></div>" +
                            "<div class=\"iv-score-item\"><label>Skill Match</label><span style=\"font-size:12px;font-weight:600;color:var(--gray-700)\">" + (screening.skill_match || "-") + "</span></div>" +
                            "<div class=\"iv-score-item\"><label>Education</label><span style=\"font-size:12px;font-weight:600;color:var(--gray-700)\">" + (screening.education_match || "-") + "</span></div>" +
                        "</div>" +
                        (screening.remarks ? "<div class=\"iv-feedback\" style=\"margin-top:8px\">" + screening.remarks + "</div>" : "") +
                    "</div>" +
                "</div>";
        }

        // Interview logs
        if (data.length === 0) {
            h += "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">No interview records yet.</div>";
        } else {
            for (var i = 0; i < data.length; i++) {
                var iv2 = data[i];
                var isCompleted = iv2.status === "Completed";
                var isRescheduled = iv2.status === "Rescheduled";
                var isPending = !isCompleted && !isRescheduled;

                var statusBadge = "";
                if (isPending) statusBadge = "<span class=\"badge badge-pending\">Waiting for Feedback</span>";
                else if (isRescheduled) statusBadge = "<span class=\"badge\" style=\"background:var(--gray-200);color:var(--gray-600)\">Rescheduled</span>";
                else statusBadge = "<span class=\"badge badge-completed\">Completed</span>";

                var headerColor = isCompleted ? "#059669" : (isRescheduled ? "#9ca3af" : "#d97706");

                h +=
                    "<div class=\"iv-card\">" +
                        "<div class=\"iv-card-header\" style=\"border-left:4px solid " + headerColor + "\">" +
                            "<div class=\"iv-card-header-left\">" +
                                "<span class=\"iv-round-name\">" + (iv2.round_name || iv2.type || "Interview") + "</span>" +
                                (iv2.is_final_round ? "<span class=\"final-badge\">FINAL</span>" : "") +
                            "</div>" +
                            "<div class=\"iv-card-header-right\">" +
                                statusBadge +
                                "<span class=\"iv-date\">" + formatDateTime(iv2.date) + "</span>" +
                            "</div>" +
                        "</div>" +
                        "<div class=\"iv-card-body\">" +
                            "<div class=\"iv-meta\">" +
                                "<span><strong>Type:</strong> " + iv2.type + "</span>" +
                                "<span><strong>Interviewer:</strong> " + iv2.interviewer + "</span>" +
                            "</div>";
                if (isCompleted) {
                    h +=
                            "<div class=\"iv-scores\">" +
                                "<div class=\"iv-score-item\"><label>Technical</label><div class=\"iv-score-bar\"><div class=\"iv-score-fill\" style=\"width:" + ((iv2.score_technical || 0) / 5 * 100) + "%\"></div></div><span>" + (iv2.score_technical || "-") + "/5</span></div>" +
                                "<div class=\"iv-score-item\"><label>Communication</label><div class=\"iv-score-bar\"><div class=\"iv-score-fill\" style=\"width:" + ((iv2.score_communication || 0) / 5 * 100) + "%\"></div></div><span>" + (iv2.score_communication || "-") + "/5</span></div>" +
                                "<div class=\"iv-score-item\"><label>Problem Solving</label><div class=\"iv-score-bar\"><div class=\"iv-score-fill\" style=\"width:" + ((iv2.score_problem_solving || 0) / 5 * 100) + "%\"></div></div><span>" + (iv2.score_problem_solving || "-") + "/5</span></div>" +
                                "<div class=\"iv-score-item\"><label>Attitude</label><div class=\"iv-score-bar\"><div class=\"iv-score-fill\" style=\"width:" + ((iv2.score_attitude || 0) / 5 * 100) + "%\"></div></div><span>" + (iv2.score_attitude || "-") + "/5</span></div>" +
                            "</div>" +
                            "<div class=\"iv-footer\">" +
                                "<div class=\"iv-overall\"><span class=\"iv-overall-value\">" + (iv2.rating != null ? iv2.rating : "-") + "</span><span class=\"iv-overall-label\">Overall</span></div>" +
                                "<div class=\"iv-decision\"><span class=\"badge " + getBadgeClass(iv2.decision) + "\">" + (iv2.decision || "-") + "</span></div>" +
                            "</div>";
                    if (iv2.reject_reason) h += "<div class=\"iv-reject-reason\"><strong>Reason:</strong> " + iv2.reject_reason + "</div>";
                    if (iv2.feedback) h += "<div class=\"iv-feedback\">" + iv2.feedback + "</div>";
                } else if (isRescheduled) {
                    h += "<div class=\"iv-empty-state\">This round was rescheduled</div>";
                } else {
                    h += "<div class=\"iv-empty-state\">Waiting for Feedback</div>";
                }
                h +=
                        "</div>" +
                    "</div>";
            }
        }
        h += "</div>";
        document.getElementById("combinedHistoryBody").innerHTML = h;
    }, function() {
        document.getElementById("combinedHistoryBody").innerHTML = "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">Failed to load history.</div>";
    });
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
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="scheduleModalTitle">Schedule Interview</h3>
            <button class="modal-close" onclick="closeModal('scheduleModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="scheduleForm" onsubmit="event.preventDefault();window.ajaxScheduleInterview()">
                <input type="hidden" id="siRescheduleId" value="">
                <input type="hidden" id="siOldInterviewId" value="">
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
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('scheduleModal')">Cancel</button>
            <button class="btn btn-primary" id="siSubmitBtn" onclick="window.ajaxScheduleInterview()">&#x2728; Schedule Interview</button>
        </div>
    </div>
</div>

<!-- Record Feedback Modal -->
<div class="modal-overlay" id="feedbackModal" onclick="if(event.target===this)closeModal('feedbackModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
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
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('feedbackModal')">Cancel</button>
            <button class="btn btn-primary" id="fbSubmitBtn" onclick="window.ajaxSaveFeedback()">&#x2728; Save Feedback</button>
        </div>
    </div>
</div>

<!-- Combined View Modal (feedback + history) -->
<div class="modal-overlay" id="combinedViewModal" onclick="if(event.target===this)closeModal('combinedViewModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="combinedViewTitle">View</h3>
            <button class="modal-close" onclick="closeModal('combinedViewModal')">&times;</button>
        </div>
        <div class="modal-body" id="combinedViewBody"></div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline btn-sm" onclick="closeModal('combinedViewModal')">Close</button>
        </div>
    </div>
</div>

<style>
.iv-logs { display:flex; flex-direction:column; gap:16px; }
.iv-card { background:#fff; border:1px solid var(--gray-100); border-radius:10px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
.iv-card-header { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#f9fafb; border-bottom:1px solid var(--gray-100); }
.iv-card-header-left { display:flex; align-items:center; gap:8px; }
.iv-round { font-size:13px; font-weight:700; color:var(--gray-800); background:var(--gray-100); padding:2px 10px; border-radius:20px; }
.iv-round-name { font-size:14px; font-weight:600; color:var(--gray-700); }
.iv-card-header-right { display:flex; align-items:center; gap:10px; }
.iv-date { font-size:12px; color:var(--gray-400); white-space:nowrap; }
.iv-card-body { padding:14px 16px; }
.iv-meta { display:flex; gap:24px; font-size:13px; color:var(--gray-600); margin-bottom:12px; }
.iv-scores { display:grid; grid-template-columns:1fr 1fr; gap:8px 20px; margin-bottom:12px; }
.iv-score-item { display:flex; align-items:center; gap:8px; }
.iv-score-item label { font-size:12px; color:var(--gray-500); width:85px; flex-shrink:0; }
.iv-score-item span { font-size:12px; font-weight:600; color:var(--gray-700); width:30px; text-align:right; flex-shrink:0; }
.iv-score-bar { flex:1; height:6px; background:var(--gray-100); border-radius:3px; overflow:hidden; }
.iv-score-fill { height:100%; background:linear-gradient(90deg,#3b82f6,#6366f1); border-radius:3px; transition:width 0.3s; }
.iv-score-fill-blue { background:linear-gradient(90deg,#60a5fa,#3b82f6); }
.iv-footer { display:flex; align-items:center; gap:16px; margin-bottom:8px; }
.iv-overall { display:flex; align-items:baseline; gap:4px; background:var(--gray-50); padding:6px 14px; border-radius:8px; }
.iv-overall-value { font-size:18px; font-weight:700; color:var(--gray-800); }
.iv-overall-label { font-size:11px; color:var(--gray-400); text-transform:uppercase; letter-spacing:0.3px; }
.iv-decision { }
.iv-reject-reason { font-size:13px; color:#dc2626; margin-bottom:6px; padding:6px 10px; background:#fef2f2; border-radius:6px; }
.iv-feedback { font-size:13px; color:var(--gray-700); line-height:1.5; padding:8px 10px; background:#f8f9fa; border-radius:6px; border:1px solid var(--gray-100); margin-top:4px; }
.iv-empty-state { text-align:center; padding:16px; color:var(--gray-400); font-size:13px; }
@media (max-width:768px){ .iv-scores { grid-template-columns:1fr; gap:6px 12px; } .iv-meta { flex-direction:column; gap:6px; } .iv-score-item label { width:auto; flex-shrink:1; } .iv-score-item span { width:auto; flex-shrink:1; } .iv-card-header { flex-direction:column; align-items:flex-start; gap:4px; } .iv-card-header-right { width:100%; justify-content:space-between; } .iv-date { white-space:normal; } .iv-footer { flex-direction:column; align-items:flex-start; gap:8px; } }
</style>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
