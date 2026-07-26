var candidatesData = [];
var eligibleCandidates = [];
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function sortBy(col) {
    if (sortCol === col) {
        sortDir = sortDir === "asc" ? "desc" : "asc";
    } else {
        sortCol = col;
        sortDir = "asc";
    }
    currentPage = 1;
    renderTable();
}

function getSortVal(c) {
    switch (sortCol) {
        case 0: return ((c.first_name || "") + " " + (c.last_name || "")).toLowerCase();
        case 1: return (c.job_title || "").toLowerCase();
        case 2: return (c.round_name || "").toLowerCase();
        case 3: return (c.type || "").toLowerCase();
        case 4: return (c.interviewer || "").toLowerCase();
        case 5: return c.date || "";
        case 6: return (c.action_state || "").toLowerCase();
        case 7: return (c.decision || "").toLowerCase();
        default: return "";
    }
}

function sortData(arr) {
    if (sortCol < 0) return arr;
    return arr.slice().sort(function(a, b) {
        var va = getSortVal(a);
        var vb = getSortVal(b);
        if (va < vb) return sortDir === "asc" ? -1 : 1;
        if (va > vb) return sortDir === "asc" ? 1 : -1;
        return 0;
    });
}

function getFilteredData() {
    var q = document.getElementById("searchInput").value.trim().toLowerCase();
    var list = candidatesData.filter(function(c) {
        var name = ((c.first_name || "") + " " + (c.last_name || "") + " " + (c.job_title || "") + " " + (c.interviewer || "") + " " + (c.type || "") + " " + (c.round_name || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
    return sortData(list);
}

function updateSortArrows() {
    for (var i = 0; i <= 7; i++) {
        var el = document.getElementById("sortArrow" + i);
        if (!el) continue;
        if (i === sortCol) {
            el.textContent = sortDir === "asc" ? "\u25B2" : "\u25BC";
            el.parentNode.classList.add("rp-sorted");
        } else {
            el.textContent = "\u2195";
            el.parentNode.classList.remove("rp-sorted");
        }
    }
}

function renderPaginationBar(total, totalPages) {
    if (totalPages <= 1) { document.getElementById("paginationBar").innerHTML = ""; return; }
    var html = "";
    html += "<div class=\"rp-pg-info\">Page " + currentPage + " of " + totalPages + " &middot; " + total + " total</div>";
    html += "<div class=\"rp-pg-center\">";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(1)\" " + (currentPage <= 1 ? "disabled" : "") + " title=\"First\">&laquo;</button>";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + (currentPage - 1) + ")\" " + (currentPage <= 1 ? "disabled" : "") + " title=\"Prev\">&lsaquo;</button>";
    var start = Math.max(1, currentPage - 2);
    var end = Math.min(totalPages, currentPage + 2);
    for (var p = start; p <= end; p++) {
        html += "<button class=\"rp-pg-btn" + (p === currentPage ? " rp-active" : "") + "\" onclick=\"goToPage(" + p + ")\">" + p + "</button>";
    }
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + (currentPage + 1) + ")\" " + (currentPage >= totalPages ? "disabled" : "") + " title=\"Next\">&rsaquo;</button>";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + totalPages + ")\" " + (currentPage >= totalPages ? "disabled" : "") + " title=\"Last\">&raquo;</button>";
    html += "</div>";
    html += "<div class=\"rp-pg-rows\">Show <select onchange=\"changePerPage(this.value)\">";
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
    document.getElementById("searchClear").style.display = document.getElementById("searchInput").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}

function clearSearch() {
    document.getElementById("searchInput").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderStats() {
    var stats = { schedule: 0, feedback: 0, next_round: 0, done: 0 };
    candidatesData.forEach(function(c) {
        var st = c.action_state;
        stats[st] = (stats[st] || 0) + 1;
    });
    document.getElementById("ccSchedule").textContent = stats.schedule || 0;
    document.getElementById("ccFeedback").textContent = stats.feedback || 0;
    document.getElementById("ccNext").textContent = stats.next_round || 0;
    document.getElementById("ccDone").textContent = stats.done || 0;
}

function getStatusInfo(c) {
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
    return { label: statusLabel, cls: statusBadgeClass };
}

function renderMobileCards(pageData) {
    var container = document.getElementById("mobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4C5;</div><h3>No candidates found</h3><p>Try adjusting your search</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(c) {
        var name = c.first_name + " " + c.last_name;
        var roundLabel = c.round_name || (c.round ? "Round " + c.round : "-");
        var finalLabel = c.is_final_round ? " <span class=\"final-badge\">FINAL</span>" : "";
        var st = getStatusInfo(c);
        var statusBadge = "<span class=\"badge " + st.cls + "\">" + st.label + "</span>";
        var decisionHtml = c.decision ? "<span class=\"badge " + getBadgeClass(c.decision) + "\">" + c.decision + "</span>" : "-";

        var actionsHtml = "";
        if (c.action_state === "schedule") {
            actionsHtml = "<button class=\"btn btn-primary btn-sm\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Schedule Interview\',false)\">Schedule</button>";
        } else if (c.action_state === "feedback") {
            actionsHtml = "<button class=\"btn btn-primary btn-sm\" onclick=\"window.openFeedback(" + c.interview_id + ")\">Feedback</button>";
        } else if (c.action_state === "next_round") {
            actionsHtml = "<button class=\"btn btn-primary btn-sm\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Next Round Interview\',false)\">Next Round</button>";
        } else {
            if (c.decision === "Fail" || c.decision === "Hold") {
                actionsHtml = "<button class=\"btn btn-primary btn-sm\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Reschedule Interview\',true," + (c.interview_id || 0) + ")\">Reschedule</button>";
            }
        }
        actionsHtml += "<button class=\"btn btn-outline btn-sm\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";

        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + name + "<small>" + (c.job_title || "N/A") + " &middot; " + roundLabel + finalLabel + "</small></div>";
        html += "<div class=\"rp-mob-badges\">" + statusBadge + "</div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Type</label><span>" + (c.type || "-") + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Interviewer</label><span>" + (c.interviewer || "-") + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Date</label><span>" + (c.date ? formatDateTime(c.date) : "-") + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Decision</label><span>" + decisionHtml + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">" + actionsHtml + "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function renderTable() {
    var filtered = getFilteredData();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    document.getElementById("resultCount").textContent = filtered.length + " candidate" + (filtered.length !== 1 ? "s" : "");

    var tbody = document.getElementById("ivTableBody");
    tbody.innerHTML = "";

    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"9\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4C5;</div><h3>No candidates found</h3><p>Try adjusting your search or schedule a new interview</p></div></td></tr>";
    } else {
        pageData.forEach(function(c) {
            var name = c.first_name + " " + c.last_name;
            var roundLabel = c.round_name || (c.round ? "Round " + c.round : "-");
            var finalLabel = c.is_final_round ? " <span class=\"final-badge\">FINAL</span>" : "";
            var dt = c.date ? formatDateTime(c.date) : "-";
            var st = getStatusInfo(c);
            var decisionHtml = c.decision ? "<span class=\"badge " + getBadgeClass(c.decision) + "\">" + c.decision + "</span>" : "-";

            var actionsHtml = "";
            if (c.action_state === "schedule") {
                actionsHtml = "<button class=\"rp-btn-text rp-btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Schedule Interview\',false)\">Schedule</button>";
                actionsHtml += "<button class=\"rp-btn-text\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
            } else if (c.action_state === "feedback") {
                actionsHtml = "<button class=\"rp-btn-text rp-btn-primary\" onclick=\"window.openFeedback(" + c.interview_id + ")\">Feedback</button>";
                actionsHtml += "<button class=\"rp-btn-text\" onclick=\"window.editInterview(" + c.interview_id + ")\">Edit</button>";
                actionsHtml += "<button class=\"rp-btn-text\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
            } else if (c.action_state === "next_round") {
                actionsHtml = "<button class=\"rp-btn-text rp-btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Next Round Interview\',false)\">Next Round</button>";
                actionsHtml += "<button class=\"rp-btn-text\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
            } else {
                if (c.decision === "Fail" || c.decision === "Hold") {
                    actionsHtml = "<button class=\"rp-btn-text rp-btn-primary\" onclick=\"window.openScheduleForCandidate(" + c.candidate_id + ",\'Reschedule Interview\',true," + (c.interview_id || 0) + ")\">Reschedule</button>";
                }
                actionsHtml += "<button class=\"rp-btn-text\" onclick=\"window.openCombinedView(" + c.candidate_id + ")\">View</button>";
            }

            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td>" + roundLabel + finalLabel + "</td>" +
                "<td>" + (c.type || "-") + "</td>" +
                "<td>" + (c.interviewer || "-") + "</td>" +
                "<td>" + dt + "</td>" +
                "<td><span class=\"badge " + st.cls + "\">" + st.label + "</span></td>" +
                "<td>" + decisionHtml + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + actionsHtml + "</div></td>";
            tbody.appendChild(tr);
        });
    }

    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, totalPages);
    updateSortArrows();
    renderStats();
}

window.editInterview = function(interviewId) {
    document.getElementById("scheduleForm").reset();
    document.getElementById("siRescheduleId").value = interviewId;
    document.getElementById("scheduleModalTitle").textContent = "Edit Interview";
    document.getElementById("siSubmitBtn").innerHTML = "Update";
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
    document.getElementById("siSubmitBtn").textContent = "Schedule Interview";
    loadCandidateSelect();
    openModal("scheduleModal");
};

window.openScheduleForCandidate = function(candidateId, title, disableSelect, interviewId) {
    document.getElementById("scheduleForm").reset();
    document.getElementById("siRescheduleId").value = "";
    loadCandidateSelect();

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
    document.getElementById("siSubmitBtn").textContent = title || "Schedule Interview";
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
    btn.innerHTML = "Saving...";

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

    document.getElementById("feedbackModalTitle").innerHTML = "Record Feedback";
    document.getElementById("fbSubmitBtn").innerHTML = "Save Feedback";
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

    document.getElementById("combinedViewTitle").textContent = "Interview Logs - " + cName;
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

        if (data.length === 0) {
            h += "<div style=\"text-align:center;padding:24px;color:var(--gray-400)\">No interview records yet.</div>";
        } else {
            for (var i = 0; i < data.length; i++) {
                var iv2 = data[i];
                var isCompleted = iv2.status === "Completed";
                var statusBadge = isCompleted ? "<span class=\"badge badge-completed\">Completed</span>" : "<span class=\"badge badge-pending\">Waiting for Feedback</span>";
                var headerColor = isCompleted ? "#059669" : "#d97706";

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
                } else {
                    h += "<div class=\"iv-empty-state\">Waiting for Feedback</div>";
                }
                h += "</div></div>";
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
    btn.innerHTML = "Saving...";

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