<?php
$basePath    = '..';
$pageTitle   = 'Screening';
$activePage  = 'screening';
$breadcrumb  = 'Home / Recruitment / Screening';
$pageStyles  = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-pending{border-left-color:var(--primary)}
.cc-card.cc-pending .cc-num{color:var(--primary)}
.cc-card.cc-shortlisted{border-left-color:var(--success)}
.cc-card.cc-shortlisted .cc-num{color:var(--success)}
.cc-card.cc-rejected{border-left-color:var(--danger)}
.cc-card.cc-rejected .cc-num{color:var(--danger)}
.cc-card.cc-hold{border-left-color:#eab308}
.cc-card.cc-hold .cc-num{color:#eab308}
/* ===== TAB BAR ===== */
.scr-tabs{display:flex;gap:0;margin-bottom:20px;border-bottom:2px solid var(--gray-200)}
.scr-tab{padding:12px 28px;font-size:14px;font-weight:600;background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-2px;cursor:pointer;color:var(--gray-500);transition:all .2s;font-family:inherit;display:flex;align-items:center;gap:8px}
.scr-tab.active{color:var(--primary);border-bottom-color:var(--primary)}
.scr-tab:hover{color:var(--primary)}
.scr-tab .scr-tab-count{background:var(--gray-100);color:var(--gray-600);font-size:12px;font-weight:600;padding:2px 8px;border-radius:10px;min-width:24px;text-align:center}
.scr-tab.active .scr-tab-count{background:rgba(37,99,235,.1);color:var(--primary)}
.scr-tab-content{display:none}
.scr-tab-content.active{display:block}
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
.rp-table .rp-btn-text.rp-btn-warning{border-color:#f59e0b;color:#f59e0b;background:rgba(245,158,11,.04)}
.rp-table .rp-btn-text.rp-btn-warning:hover{background:#f59e0b;color:#fff}
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
 .scr-tab{padding:10px 16px;font-size:13px}
 .scr-tab .scr-tab-count{font-size:11px;padding:1px 6px}
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
var upcomingData = [];
var completeData = [];
var editingScreeningId = null;
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";
var activeTab = "Upcoming";

function switchTab(name) {
    activeTab = name;
    document.querySelectorAll(".scr-tab").forEach(function(b) { b.classList.remove("active"); });
    document.querySelectorAll(".scr-tab-content").forEach(function(c) { c.classList.remove("active"); });
    document.getElementById("tabBtn" + name).classList.add("active");
    document.getElementById("tab" + name).classList.add("active");
    sortCol = -1;
    currentPage = 1;
    if (name === "Upcoming") renderUpcoming();
    else renderComplete();
}

function sortBy(col) {
    if (sortCol === col) {
        sortDir = sortDir === "asc" ? "desc" : "asc";
    } else {
        sortCol = col;
        sortDir = "asc";
    }
    currentPage = 1;
    if (activeTab === "Upcoming") renderUpcoming();
    else renderComplete();
}

function getUpcomingSortVal(c) {
    switch (sortCol) {
        case 0: return ((c.first_name || "") + " " + (c.last_name || "")).toLowerCase();
        case 1: return (c.job_title || "").toLowerCase();
        case 2: return c.applied_at || "";
        case 3: return "applied";
        default: return "";
    }
}

function getCompleteSortVal(r) {
    switch (sortCol) {
        case 0: return ((r.first_name || "") + " " + (r.last_name || "")).toLowerCase();
        case 1: return (r.job_title || "").toLowerCase();
        case 2: return r.applied_at || "";
        case 3: return (r.screening_result || "").toLowerCase();
        case 4: return r.screening_date || "";
        default: return "";
    }
}

function sortData(arr, getter) {
    if (sortCol < 0) return arr;
    return arr.slice().sort(function(a, b) {
        var va = getter(a);
        var vb = getter(b);
        if (va < vb) return sortDir === "asc" ? -1 : 1;
        if (va > vb) return sortDir === "asc" ? 1 : -1;
        return 0;
    });
}

function updateSortArrows(count, prefix) {
    for (var i = 0; i < count; i++) {
        var el = document.getElementById(prefix + "SortArrow" + i);
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

function getFilteredUpcoming() {
    var q = document.getElementById("searchUpcoming").value.trim().toLowerCase();
    var list = upcomingData.filter(function(c) {
        var name = ((c.first_name || "") + " " + (c.last_name || "") + " " + (c.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
    return sortData(list, getUpcomingSortVal);
}

function getFilteredComplete() {
    var q = document.getElementById("searchComplete").value.trim().toLowerCase();
    var list = completeData.filter(function(row) {
        var name = ((row.first_name || "") + " " + (row.last_name || "") + " " + (row.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
    return sortData(list, getCompleteSortVal);
}

function renderPaginationBar(containerId, total, totalPages) {
    if (totalPages <= 1) { document.getElementById(containerId).innerHTML = ""; return; }
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
    document.getElementById(containerId).innerHTML = html;
}

function goToPage(p) {
    var filtered = activeTab === "Upcoming" ? getFilteredUpcoming() : getFilteredComplete();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (p < 1) p = 1;
    if (p > totalPages) p = totalPages;
    if (p === currentPage) return;
    currentPage = p;
    if (activeTab === "Upcoming") renderUpcoming();
    else renderComplete();
}

function changePerPage(n) {
    perPage = parseInt(n);
    currentPage = 1;
    if (activeTab === "Upcoming") renderUpcoming();
    else renderComplete();
}

function applyUpcomingFilter() {
    document.getElementById("searchUpcomingClear").style.display = document.getElementById("searchUpcoming").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderUpcoming();
}

function clearUpcomingSearch() {
    document.getElementById("searchUpcoming").value = "";
    document.getElementById("searchUpcomingClear").style.display = "none";
    currentPage = 1;
    renderUpcoming();
}

function applyCompleteFilter() {
    document.getElementById("searchCompleteClear").style.display = document.getElementById("searchComplete").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderComplete();
}

function clearCompleteSearch() {
    document.getElementById("searchComplete").value = "";
    document.getElementById("searchCompleteClear").style.display = "none";
    currentPage = 1;
    renderComplete();
}

function renderStats() {
    var pending = upcomingData.length;
    var shortlisted = 0, rejected = 0, hold = 0;
    completeData.forEach(function(row) {
        if (row.screening_result === "Shortlist") shortlisted++;
        if (row.screening_result === "Reject") rejected++;
        if (row.screening_result === "Hold") hold++;
    });
    document.getElementById("ccPending").textContent = pending;
    document.getElementById("ccShortlisted").textContent = shortlisted;
    document.getElementById("ccRejected").textContent = rejected;
    document.getElementById("ccHold").textContent = hold;
    document.getElementById("tabCountUpcoming").textContent = pending;
    document.getElementById("tabCountComplete").textContent = completeData.length;
}

function renderUpcomingMobileCards(pageData) {
    var container = document.getElementById("upcomingMobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4C5;</div><h3>No pending screenings</h3><p>All candidates have been screened</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(c) {
        var name = c.first_name + " " + c.last_name;
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + name + "<small>" + (c.job_title || "N/A") + "</small></div>";
        html += "<div class=\"rp-mob-badges\"><span class=\"badge badge-pending\">Applied</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Applied Date</label><span>" + formatDate(c.applied_at) + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">";
        html += "<button class=\"btn btn-primary btn-sm\" onclick=\"window.openScreening(" + c.candidate_id + ",0)\">Screen Now</button>";
        html += "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function renderUpcoming() {
    var filtered = getFilteredUpcoming();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("upcomingTableBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"5\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4C5;</div><h3>No pending screenings</h3><p>All candidates have been screened</p></div></td></tr>";
    } else {
        pageData.forEach(function(c) {
            var name = c.first_name + " " + c.last_name;
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td>" + formatDate(c.applied_at) + "</td>" +
                "<td><span class=\"badge badge-pending\">Applied</span></td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text rp-btn-primary\" onclick=\"window.openScreening(" + c.candidate_id + ",0)\">Screen Now</button></div></td>";
            tbody.appendChild(tr);
        });
    }

    renderUpcomingMobileCards(pageData);
    renderPaginationBar("paginationUpcoming", filtered.length, totalPages);
    updateSortArrows(4, "upcoming");
}

function renderCompleteMobileCards(pageData) {
    var container = document.getElementById("completeMobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x2705;</div><h3>No completed screenings</h3><p>Screened candidates will appear here</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(row) {
        var name = row.first_name + " " + row.last_name;
        var resultBadge = "<span class=\"badge " + getBadgeClass(row.screening_result) + "\">" + (row.screening_result || "-") + "</span>";
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + name + "<small>" + (row.job_title || "N/A") + "</small></div>";
        html += "<div class=\"rp-mob-badges\">" + resultBadge + "</div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Applied</label><span>" + formatDate(row.applied_at) + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Rating</label><span>" + (row.rating || "-") + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Screened On</label><span>" + (row.screening_date ? formatDate(row.screening_date) : "-") + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">";
        html += "<button class=\"btn btn-outline btn-sm rp-btn-warning\" onclick=\"window.openScreening(" + row.candidate_id + "," + row.screening_id + ")\">Edit</button>";
        html += "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function renderComplete() {
    var filtered = getFilteredComplete();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("completeTableBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x2705;</div><h3>No completed screenings</h3><p>Screened candidates will appear here</p></div></td></tr>";
    } else {
        pageData.forEach(function(row) {
            var name = row.first_name + " " + row.last_name;
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (row.job_title || "N/A") + "</td>" +
                "<td>" + formatDate(row.applied_at) + "</td>" +
                "<td>" + (row.rating || "-") + " | <span class=\"badge " + getBadgeClass(row.screening_result) + "\">" + (row.screening_result || "-") + "</span></td>" +
                "<td>" + (row.screening_date ? formatDate(row.screening_date) : "-") + "</td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text rp-btn-warning\" onclick=\"window.openScreening(" + row.candidate_id + "," + row.screening_id + ")\">Edit</button></div></td>";
            tbody.appendChild(tr);
        });
    }

    renderCompleteMobileCards(pageData);
    renderPaginationBar("paginationComplete", filtered.length, totalPages);
    updateSortArrows(5, "complete");
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
        document.getElementById("screeningModalTitle").innerHTML = "Edit Screening";
        document.getElementById("scSubmitBtn").innerHTML = "Update Screening";
    } else {
        document.getElementById("screeningForm").reset();
        document.getElementById("scCandidateId").value = candidateId;
        document.getElementById("screeningModalTitle").innerHTML = "Screen Candidate";
        document.getElementById("scSubmitBtn").innerHTML = "Save Screening";
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
    btn.innerHTML = "Saving...";

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

function renderPage() {
    apiGet("ajax/list_upcoming.php", null, function(res) {
        if (res.success && res.data) upcomingData = res.data;
        else upcomingData = [];
        renderStats();
        if (activeTab === "Upcoming") renderUpcoming();
    }, function() { upcomingData = []; renderStats(); renderUpcoming(); });
    apiGet("ajax/list_complete.php", null, function(res) {
        if (res.success && res.data) completeData = res.data;
        else completeData = [];
        renderStats();
        if (activeTab === "Complete") renderComplete();
    }, function() { completeData = []; renderStats(); renderComplete(); });
}

renderPage();
';

ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Pending</div></div></div>
    <div class="cc-card cc-shortlisted"><div><div class="cc-num" id="ccShortlisted">0</div><div class="cc-label">Shortlisted</div></div></div>
    <div class="cc-card cc-rejected"><div><div class="cc-num" id="ccRejected">0</div><div class="cc-label">Rejected</div></div></div>
    <div class="cc-card cc-hold"><div><div class="cc-num" id="ccHold">0</div><div class="cc-label">On Hold</div></div></div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" id="tabBtnUpcoming" onclick="switchTab('Upcoming')">Upcoming <span class="scr-tab-count" id="tabCountUpcoming">0</span></button>
    <button class="scr-tab" id="tabBtnComplete" onclick="switchTab('Complete')">Complete <span class="scr-tab-count" id="tabCountComplete">0</span></button>
</div>

<div class="scr-tab-content active" id="tabUpcoming">
    <div class="rp-top">
        <h1>Upcoming Screenings</h1>
        <div class="rp-actions">
            <div class="rp-search">
                <span class="rp-search-icon">&#x1F50D;</span>
                <input type="text" id="searchUpcoming" placeholder="Search by candidate or job..." oninput="applyUpcomingFilter()" autocomplete="off">
                <button class="rp-search-clear" id="searchUpcomingClear" onclick="clearUpcomingSearch()">&times;</button>
            </div>
        </div>
    </div>
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table" id="upcomingTable">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="upcomingSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="upcomingSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Applied Date <span class="rp-sort-arrow" id="upcomingSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Status <span class="rp-sort-arrow" id="upcomingSortArrow3"></span></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="upcomingTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="upcomingMobileList"></div>
        <div id="paginationUpcoming" class="rp-pagination"></div>
    </div>
</div>

<div class="scr-tab-content" id="tabComplete">
    <div class="rp-top">
        <h1>Completed Screenings</h1>
        <div class="rp-actions">
            <div class="rp-search">
                <span class="rp-search-icon">&#x1F50D;</span>
                <input type="text" id="searchComplete" placeholder="Search by candidate or job..." oninput="applyCompleteFilter()" autocomplete="off">
                <button class="rp-search-clear" id="searchCompleteClear" onclick="clearCompleteSearch()">&times;</button>
            </div>
        </div>
    </div>
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table" id="completeTable">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="completeSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="completeSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Applied Date <span class="rp-sort-arrow" id="completeSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Result <span class="rp-sort-arrow" id="completeSortArrow3"></span></th>
                        <th class="rp-sortable" onclick="sortBy(4)">Screened On <span class="rp-sort-arrow" id="completeSortArrow4"></span></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="completeTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="completeMobileList"></div>
        <div id="paginationComplete" class="rp-pagination"></div>
    </div>
</div>

<!-- Screening Form Modal -->
<div class="modal-overlay" id="screeningModal" onclick="if(event.target===this)closeModal('screeningModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="screeningModalTitle">Screen Candidate</h3>
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
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('screeningModal')">Cancel</button>
            <button class="btn btn-primary" id="scSubmitBtn" onclick="window.ajaxSaveScreening()">Save Screening</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
