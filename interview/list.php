<?php
$basePath    = '..';
$pageTitle   = 'Interview';
$activePage  = 'interview';
$breadcrumb  = 'Home / Recruitment / Interview';
$pageStyles  = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-schedule{border-left-color:var(--primary)}
.cc-card.cc-schedule .cc-num{color:var(--primary)}
.cc-card.cc-feedback{border-left-color:#f59e0b}
.cc-card.cc-feedback .cc-num{color:#f59e0b}
.cc-card.cc-next{border-left-color:#8b5cf6}
.cc-card.cc-next .cc-num{color:#8b5cf6}
.cc-card.cc-done{border-left-color:var(--success)}
.cc-card.cc-done .cc-num{color:var(--success)}
/* ===== HEADER ===== */
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.rp-top h1{font-size:20px;font-weight:700;color:var(--gray-900);margin:0;white-space:nowrap}
.rp-top h1 small{font-size:13px;font-weight:400;color:var(--gray-400);margin-left:8px}
.rp-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.rp-search{position:relative}
.rp-search input{width:260px;padding:9px 12px 9px 36px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit}
.rp-search input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.rp-search .rp-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:14px;pointer-events:none}
.rp-search .rp-search-clear{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:16px;display:none;padding:2px;line-height:1}
.rp-search .rp-search-clear:hover{color:var(--gray-700)}
/* Table card */
.rp-card{background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);overflow:hidden}
.rp-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
.rp-table{width:100%;border-collapse:collapse}
.rp-table thead th{background:var(--gray-50);padding:12px 16px;font-size:12px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.4px;text-align:left;border-bottom:1px solid var(--gray-100);white-space:nowrap;position:sticky;top:0;z-index:1}
.rp-table thead th.rp-sortable{cursor:pointer;user-select:none;transition:color .15s}
.rp-table thead th.rp-sortable:hover{color:var(--primary)}
.rp-table thead th.rp-sorted{color:var(--primary)}
.rp-sort-arrow{font-size:10px;margin-left:4px;opacity:.5}
.rp-sorted .rp-sort-arrow{opacity:1}
.rp-table tbody tr{border-bottom:1px solid var(--gray-50);transition:background .15s}
.rp-table tbody tr:last-child{border-bottom:none}
.rp-table tbody tr:hover{background:var(--gray-50)}
.rp-table td{padding:14px 16px;font-size:13px;color:var(--gray-700);vertical-align:middle}
.rp-table td strong{color:var(--gray-900);font-weight:600}
.rp-table .rp-actions-cell{display:flex;gap:6px;white-space:nowrap}
.rp-table .rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit}
.rp-table .rp-btn-text:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary:hover{background:var(--primary);color:#fff}
.rp-empty{text-align:center;padding:48px 24px;color:var(--gray-400)}
.rp-empty-icon{font-size:40px;margin-bottom:8px}
.rp-empty h3{font-size:15px;color:var(--gray-600);margin:0 0 4px}
.rp-empty p{font-size:13px;margin:0}
/* Pagination */
.rp-pagination{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-top:1px solid var(--gray-100);flex-wrap:wrap;gap:10px}
.rp-pg-info{font-size:13px;color:var(--gray-500)}
.rp-pg-center{display:flex;align-items:center;gap:6px}
.rp-pg-btn{min-width:32px;height:32px;padding:0 10px;border:1px solid var(--gray-200);border-radius:6px;background:#fff;font-size:13px;cursor:pointer;color:var(--gray-600);transition:all .15s;font-family:inherit;display:inline-flex;align-items:center;justify-content:center}
.rp-pg-btn:hover:not(:disabled){border-color:var(--primary);color:var(--primary)}
.rp-pg-btn.rp-active{background:var(--primary);color:#fff;border-color:var(--primary)}
.rp-pg-btn:disabled{opacity:.35;cursor:default}
.rp-pg-rows{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--gray-500)}
.rp-pg-rows select{padding:5px 8px;border:1px solid var(--gray-200);border-radius:6px;font-size:13px;font-family:inherit;background:#fff;cursor:pointer}
/* Mobile cards */
.rp-mobile-list{display:none}
/* Feedback modal */
.score-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.score-grid .form-group{margin-bottom:0}
.overall-rating{text-align:center;padding:12px;background:#f8f9fa;border-radius:8px;margin:12px 0}
.overall-rating .or-value{font-size:28px;font-weight:700;color:var(--primary)}
.overall-rating .or-label{font-size:11px;color:var(--gray-500);text-transform:uppercase;letter-spacing:.5px}
.final-badge{font-size:10px;background:#e8f0fe;color:var(--primary);padding:2px 8px;border-radius:4px;font-weight:600}
/* ===== RESPONSIVE ===== */
@media(max-width:900px){
 .rp-top h1{font-size:18px}
 .rp-search input{width:200px}
}
@media(max-width:768px){
 .page-content{padding:16px}
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;margin-bottom:18px}
 .cc-card{padding:14px 14px;gap:10px}
 .cc-card .cc-num{font-size:22px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
 .rp-top h1 small{display:block;margin:4px 0 0;font-size:12px}
 .rp-actions{width:100%}
 .rp-search{flex:1}
 .rp-search input{width:100%!important}
 .rp-table-wrap{display:none}
 .rp-mobile-list{display:block}
 .rp-mob-card{background:#fff;border-radius:10px;padding:16px;margin:8px 16px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid var(--gray-100)}
 .rp-mob-card:last-child{margin-bottom:16px}
 .rp-mob-top{display:flex;align-items:start;justify-content:space-between;gap:10px;margin-bottom:10px}
 .rp-mob-title{font-size:15px;font-weight:600;color:var(--gray-900);margin:0}
 .rp-mob-title small{font-size:12px;font-weight:400;color:var(--gray-400);display:block;margin-top:2px}
 .rp-mob-badges{display:flex;gap:6px;flex-shrink:0}
 .rp-mob-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 16px;margin-bottom:12px}
 .rp-mob-field label{display:block;font-size:11px;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px}
 .rp-mob-field span{font-size:13px;color:var(--gray-700);font-weight:500}
 .rp-mob-footer{display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--gray-100)}
 .rp-mob-footer .btn{flex:1;justify-content:center;font-size:12px;padding:7px 12px}
 .score-grid{grid-template-columns:1fr;gap:8px}
 .iv-scores{grid-template-columns:1fr;gap:6px 12px}
 .iv-meta{flex-direction:column;gap:6px}
 .iv-score-item label{width:auto;flex-shrink:1}
 .iv-score-item span{width:auto;flex-shrink:1}
 .iv-card-header{flex-direction:column;align-items:flex-start;gap:4px}
 .iv-card-header-right{width:100%;justify-content:space-between}
 .iv-date{white-space:normal}
 .iv-footer{flex-direction:column;align-items:flex-start;gap:8px}
}
@media(max-width:480px){
 .page-content{padding:12px}
 .cc-grid{grid-template-columns:repeat(2,1fr);gap:8px;margin-bottom:16px}
 .cc-card{padding:12px;gap:8px;border-left-width:3px}
 .cc-card .cc-num{font-size:20px}
 .cc-card .cc-label{font-size:11px}
 .rp-top h1{font-size:16px}
 .rp-mob-card{padding:14px;margin:6px 12px}
 .rp-mob-grid{grid-template-columns:1fr;gap:8px}
 .rp-mob-top{flex-direction:column}
 .rp-pagination{flex-direction:column;align-items:center;gap:8px}
 .rp-pg-center{flex-wrap:wrap;justify-content:center}
 .rp-pg-rows{width:100%;justify-content:center}
}
';

$pageScripts = '
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
';

ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-schedule"><div><div class="cc-num" id="ccSchedule">0</div><div class="cc-label">Needs Scheduling</div></div></div>
    <div class="cc-card cc-feedback"><div><div class="cc-num" id="ccFeedback">0</div><div class="cc-label">Waiting Feedback</div></div></div>
    <div class="cc-card cc-next"><div><div class="cc-num" id="ccNext">0</div><div class="cc-label">Next Round</div></div></div>
    <div class="cc-card cc-done"><div><div class="cc-num" id="ccDone">0</div><div class="cc-label">Completed</div></div></div>
</div>

<div class="rp-top">
    <h1>Interview Candidates <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchInput" placeholder="Search by candidate, job, interviewer..." oninput="applyFilters()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
        <button class="btn btn-primary" onclick="window.openScheduleModal()">+ Schedule Interview</button>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="interviewTable">
            <thead>
                <tr>
                    <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>
                    <th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="sortArrow1"></span></th>
                    <th class="rp-sortable" onclick="sortBy(2)">Round <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Type <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Interviewer <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th class="rp-sortable" onclick="sortBy(5)">Date / Time <span class="rp-sort-arrow" id="sortArrow5"></span></th>
                    <th class="rp-sortable" onclick="sortBy(6)">Status <span class="rp-sort-arrow" id="sortArrow6"></span></th>
                    <th class="rp-sortable" onclick="sortBy(7)">Decision <span class="rp-sort-arrow" id="sortArrow7"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ivTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div id="paginationBar" class="rp-pagination"></div>
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
                    <div class="form-group">
                        <label>Round Name</label>
                        <input type="text" class="form-control" id="siRoundName" placeholder="e.g. Technical Screening">
                    </div>
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
            <button class="btn btn-primary" id="siSubmitBtn" onclick="window.ajaxScheduleInterview()">Schedule Interview</button>
        </div>
    </div>
</div>

<!-- Record Feedback Modal -->
<div class="modal-overlay" id="feedbackModal" onclick="if(event.target===this)closeModal('feedbackModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="feedbackModalTitle">Record Feedback</h3>
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
            <button class="btn btn-primary" id="fbSubmitBtn" onclick="window.ajaxSaveFeedback()">Save Feedback</button>
        </div>
    </div>
</div>

<!-- Combined View Modal -->
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
</style>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
