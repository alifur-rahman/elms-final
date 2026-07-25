<?php
$basePath   = '..';
$pageTitle  = 'Confirmation - Eligible Employees';
$activePage = 'confirmation_list';
$breadcrumb = 'Home / Confirmation / Eligible Employees';
$pageStyles = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-total{border-left-color:var(--primary)}
.cc-card.cc-total .cc-num{color:var(--primary)}
.cc-card.cc-pending{border-left-color:#f59e0b}
.cc-card.cc-pending .cc-num{color:#f59e0b}
.cc-card.cc-generated{border-left-color:#8b5cf6}
.cc-card.cc-generated .cc-num{color:#8b5cf6}
.cc-card.cc-sent{border-left-color:var(--success)}
.cc-card.cc-sent .cc-num{color:var(--success)}

/* ===== HEADER ===== */
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.rp-top h1{font-size:20px;font-weight:700;color:var(--gray-900);margin:0;white-space:nowrap}
.rp-top h1 small{font-size:13px;font-weight:400;color:var(--gray-400);margin-left:8px}
.rp-search{position:relative}
.rp-search input{width:260px;padding:9px 12px 9px 36px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit}
.rp-search input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.rp-search .rp-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:14px;pointer-events:none}

/* ===== TABS ===== */
.scr-tabs{display:flex;gap:0;margin-bottom:20px;border-bottom:2px solid var(--gray-200);flex-wrap:wrap}
.scr-tab{padding:12px 28px;font-size:14px;font-weight:600;background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-2px;cursor:pointer;color:var(--gray-500);transition:all .2s;font-family:inherit;display:flex;align-items:center;gap:8px}
.scr-tab.active{color:var(--primary);border-bottom-color:var(--primary)}
.scr-tab:hover{color:var(--primary)}
.scr-tab .scr-tab-count{background:var(--gray-100);color:var(--gray-600);font-size:12px;font-weight:600;padding:2px 8px;border-radius:10px;min-width:24px;text-align:center}
.scr-tab.active .scr-tab-count{background:rgba(37,99,235,.1);color:var(--primary)}

/* ===== TABLE CARD ===== */
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
.rp-table .rp-actions-cell{display:flex;gap:6px;white-space:nowrap;align-items:center}
.rp-table .rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit;text-decoration:none;display:inline-flex;align-items:center}
.rp-table .rp-btn-text:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary:hover{background:var(--primary);color:#fff}
.rp-table .rp-btn-text.rp-btn-success{border-color:var(--success);color:var(--success);background:rgba(16,185,129,.04)}
.rp-table .rp-btn-text.rp-btn-success:hover{background:var(--success);color:#fff}
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

/* ===== BADGES ===== */
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap}
.badge-pending{background:#fef7e0;color:#e37400}
.badge-completed,.badge-success{background:#e6f4ea;color:#188038}
.badge-in-progress{background:#e8f0fe;color:var(--primary)}
.badge-danger{background:#fce8e6;color:var(--danger)}
.badge-draft{background:var(--gray-100);color:var(--gray-600)}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
 .rp-top h1{font-size:18px}
 .rp-search input{width:200px}
}
@media(max-width:768px){
 .page-content{padding:16px}
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;margin-bottom:18px}
 .cc-card{padding:14px;gap:10px}
 .cc-card .cc-num{font-size:22px}
 .scr-tab{padding:10px 16px;font-size:13px}
 .scr-tab .scr-tab-count{font-size:11px;padding:1px 6px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
 .rp-top h1 small{display:block;margin:4px 0 0;font-size:12px}
 .rp-search{flex:1}
 .rp-search input{width:100%!important}
 .rp-table-wrap{display:none}
 .rp-mobile-list{display:block}
 .rp-mob-card{background:#fff;border-radius:10px;padding:16px;margin:8px 16px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid var(--gray-100)}
 .rp-mob-card:last-child{margin-bottom:16px}
 .rp-mob-top{display:flex;align-items:start;justify-content:space-between;gap:10px;margin-bottom:10px}
 .rp-mob-title{font-size:15px;font-weight:600;color:var(--gray-900);margin:0}
 .rp-mob-title small{font-size:12px;font-weight:400;color:var(--gray-400);display:block;margin-top:2px}
 .rp-mob-badges{display:flex;gap:6px;flex-shrink:0;flex-wrap:wrap}
 .rp-mob-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 16px;margin-bottom:12px}
 .rp-mob-field label{display:block;font-size:11px;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px}
 .rp-mob-field span{font-size:13px;color:var(--gray-700);font-weight:500}
 .rp-mob-footer{display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--gray-100)}
 .rp-mob-footer .rp-btn-text{flex:1;justify-content:center;font-size:12px;padding:7px 12px}
 .rp-pagination{flex-direction:column;align-items:center;gap:8px}
 .rp-pg-center{flex-wrap:wrap;justify-content:center}
 .rp-pg-rows{width:100%;justify-content:center}
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
 .scr-tabs{overflow-x:auto;-webkit-overflow-scrolling:touch;flex-wrap:nowrap}
 .scr-tab{padding:10px 14px;font-size:12px;white-space:nowrap}
}
';
$pageScripts = <<<'EOSCRIPT'
var allData = [];
var activeTab = "all";
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;

function loadData() {
    apiGet("ajax/list_eligible.php", null, function(res) {
        allData = res.success && res.data ? res.data : [];
        updateCounts();
        renderView();
    });
}

function updateCounts() {
    var total = allData.length;
    var pending = 0, generated = 0, sent = 0;
    for (var i = 0; i < allData.length; i++) {
        var s = allData[i].confirmation_status;
        if (!s) pending++;
        else if (s === "Generated") generated++;
        else if (s === "Sent") sent++;
        else pending++;
    }
    document.getElementById("cntTotal").textContent = total;
    document.getElementById("cntPending").textContent = pending;
    document.getElementById("cntGenerated").textContent = generated;
    document.getElementById("cntSent").textContent = sent;
    document.querySelectorAll(".scr-tab-count").forEach(function(el) {
        var tab = el.getAttribute("data-count");
        if (tab === "all") el.textContent = total;
        else if (tab === "pending") el.textContent = pending;
        else if (tab === "generated") el.textContent = generated;
        else if (tab === "sent") el.textContent = sent;
    });
}

function switchTab(tab) {
    activeTab = tab;
    pgPage = 1;
    sortCol = "";
    sortAsc = true;
    document.querySelectorAll(".scr-tab").forEach(function(t) {
        t.classList.toggle("active", t.getAttribute("data-tab") === tab);
    });
    renderView();
}

function getFiltered() {
    var search = (document.getElementById("searchInput").value || "").toLowerCase().trim();
    var list = [];
    for (var i = 0; i < allData.length; i++) {
        var e = allData[i];
        var s = e.confirmation_status;
        if (activeTab === "pending" && s) continue;
        if (activeTab === "generated" && s !== "Generated") continue;
        if (activeTab === "sent" && s !== "Sent") continue;
        if (search) {
            var hay = ((e.employee_name || "") + " " + (e.employee_code || "") + " " + (e.dept_name || "") + " " + (e.job_title || "") + " " + (e.designation_name || "")).toLowerCase();
            if (hay.indexOf(search) === -1) continue;
        }
        list.push(e);
    }
    if (sortCol) {
        list.sort(function(a, b) {
            var va = getSortVal(a, sortCol);
            var vb = getSortVal(b, sortCol);
            if (va < vb) return sortAsc ? -1 : 1;
            if (va > vb) return sortAsc ? 1 : -1;
            return 0;
        });
    }
    return list;
}

function sortBy(col) {
    if (sortCol === col) sortAsc = !sortAsc;
    else { sortCol = col; sortAsc = true; }
    pgPage = 1;
    renderView();
}

function getSortVal(e, col) {
    switch(col) {
        case "employee": return (e.employee_name || "").toLowerCase();
        case "job": return (e.job_title || "").toLowerCase();
        case "dept": return (e.dept_name || "").toLowerCase();
        case "designation": return (e.designation_name || "").toLowerCase();
        case "type": return (e.employment_type || "").toLowerCase();
        case "end": return e.end_date || "";
        case "score": return e.overall_score || 0;
        default: return "";
    }
}

function updateSortArrows(count, prefix) {
    for (var i = 0; i < count; i++) {
        var el = document.getElementById(prefix + "SortArrow" + i);
        if (!el) continue;
        if (sortCol === el.getAttribute("data-col")) {
            el.textContent = sortAsc ? "\u25B2" : "\u25BC";
            el.parentElement.classList.add("rp-sorted");
        } else {
            el.textContent = "\u2195";
            el.parentElement.classList.remove("rp-sorted");
        }
    }
}

function renderView() {
    var filtered = getFiltered();
    var totalPages = Math.max(1, Math.ceil(filtered.length / pgRows));
    if (pgPage > totalPages) pgPage = totalPages;
    var start = (pgPage - 1) * pgRows;
    var paged = filtered.slice(start, start + pgRows);

    renderTable(paged);
    renderMobileCards(paged);
    renderPagination(filtered.length, totalPages);
    updateSortArrows(7, "list");
}

function renderTable(rows) {
    var tbody = document.getElementById("tableBody");
    if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8"><div class="rp-empty"><div class="rp-empty-icon">&#128100;</div><h3>No employees found</h3><p>No eligible employees match your current filters.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var e = rows[i];
        var statusBadge = getStatusBadge(e.confirmation_status);
        var action = getActionHtml(e);
        html += "<tr>" +
            "<td><strong>" + (e.employee_name || "N/A") + "</strong><br><small style='color:#888'>" + (e.employee_code || "") + "</small></td>" +
            "<td>" + (e.job_title || "N/A") + "</td>" +
            "<td>" + (e.dept_name || "N/A") + "</td>" +
            "<td>" + (e.designation_name || "N/A") + "</td>" +
            "<td>" + (e.employment_type || "Probationer") + "</td>" +
            "<td>" + (e.end_date || "-") + "</td>" +
            "<td>" + (e.overall_score != null ? e.overall_score.toFixed(1) : "-") + "</td>" +
            "<td>" + action + "</td>" +
        "</tr>";
    }
    tbody.innerHTML = html;
}

function renderMobileCards(rows) {
    var container = document.getElementById("mobileList");
    if (rows.length === 0) {
        container.innerHTML = '<div class="rp-empty"><div class="rp-empty-icon">&#128100;</div><h3>No employees found</h3><p>No eligible employees match your current filters.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var e = rows[i];
        var statusBadge = getStatusBadge(e.confirmation_status);
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + (e.employee_name || "N/A") + '<small>' + (e.employee_code || "") + '</small></div>' +
                '<div class="rp-mob-badges">' + statusBadge + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Job Title</label><span>' + (e.job_title || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Department</label><span>' + (e.dept_name || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Designation</label><span>' + (e.designation_name || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Type</label><span>' + (e.employment_type || "Probationer") + '</span></div>' +
                '<div class="rp-mob-field"><label>Probation End</label><span>' + (e.end_date || "-") + '</span></div>' +
                '<div class="rp-mob-field"><label>Score</label><span>' + (e.overall_score != null ? e.overall_score.toFixed(1) + " / 5" : "-") + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer">' + getActionHtml(e) + '</div>' +
        '</div>';
    }
    container.innerHTML = html;
}

function getStatusBadge(status) {
    if (!status) return '<span class="badge badge-pending">Pending</span>';
    if (status === "Sent") return '<span class="badge badge-completed">Sent</span>';
    if (status === "Approved") return '<span class="badge badge-in-progress">Approved</span>';
    if (status === "Generated") return '<span class="badge badge-in-progress">Generated</span>';
    if (status === "Rejected") return '<span class="badge badge-danger">Rejected</span>';
    return '<span class="badge badge-draft">' + status + '</span>';
}

function getActionHtml(e) {
    if (e.confirmation_status === "Sent") {
        return '<div class="rp-actions-cell">' +
            '<span class="badge badge-completed" style="margin-right:4px">Sent</span>' +
            '<a class="rp-btn-text" href="confirmation_letters.php">View</a></div>';
    }
    if (e.confirmation_status === "Generated" || e.confirmation_status === "Approved") {
        return '<div class="rp-actions-cell">' +
            '<a class="rp-btn-text rp-btn-primary" href="generate.php?employee_id=' + e.employee_id + '&appraisal_id=' + e.appraisal_id + '">Generate</a></div>';
    }
    return '<div class="rp-actions-cell">' +
        '<a class="rp-btn-text rp-btn-primary" href="generate.php?employee_id=' + e.employee_id + '&appraisal_id=' + e.appraisal_id + '">Generate Letter</a></div>';
}

function renderPagination(total, totalPages) {
    var pgBar = document.getElementById("pgBar");
    if (total <= pgRows) { pgBar.style.display = "none"; return; }
    pgBar.style.display = "";
    var info = document.getElementById("pgInfo");
    var center = document.getElementById("pgCenter");
    var rowsInfo = document.getElementById("pgRowsInfo");
    var start = total === 0 ? 0 : (pgPage - 1) * pgRows + 1;
    var end = Math.min(pgPage * pgRows, total);
    info.textContent = "Showing " + start + "\u2013" + end + " of " + total;

    var html = '<button class="rp-pg-btn" onclick="goPage(' + (pgPage - 1) + ')"' + (pgPage <= 1 ? ' disabled' : '') + '>\u2190</button>';
    var startP = Math.max(1, pgPage - 2);
    var endP = Math.min(totalPages, pgPage + 2);
    if (startP > 1) html += '<button class="rp-pg-btn" onclick="goPage(1)">1</button>';
    if (startP > 2) html += '<span style="color:var(--gray-400)">...</span>';
    for (var p = startP; p <= endP; p++) {
        html += '<button class="rp-pg-btn' + (p === pgPage ? ' rp-active' : '') + '" onclick="goPage(' + p + ')">' + p + '</button>';
    }
    if (endP < totalPages - 1) html += '<span style="color:var(--gray-400)">...</span>';
    if (endP < totalPages) html += '<button class="rp-pg-btn" onclick="goPage(' + totalPages + ')">' + totalPages + '</button>';
    html += '<button class="rp-pg-btn" onclick="goPage(' + (pgPage + 1) + ')"' + (pgPage >= totalPages ? ' disabled' : '') + '>\u2192</button>';
    center.innerHTML = html;

    rowsInfo.innerHTML = 'Rows: <select onchange="changeRows(this.value)"><option value="5"' + (pgRows === 5 ? ' selected' : '') + '>5</option><option value="10"' + (pgRows === 10 ? ' selected' : '') + '>10</option><option value="25"' + (pgRows === 25 ? ' selected' : '') + '>25</option><option value="50"' + (pgRows === 50 ? ' selected' : '') + '>50</option></select>';
}

function goPage(p) {
    pgPage = p;
    renderView();
}

function changeRows(val) {
    pgRows = parseInt(val) || 10;
    pgPage = 1;
    renderView();
}

document.addEventListener("DOMContentLoaded", function() { loadData(); });
EOSCRIPT;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-total">
        <div class="cc-num" id="cntTotal">0</div>
        <div class="cc-label">Total Eligible</div>
    </div>
    <div class="cc-card cc-pending">
        <div class="cc-num" id="cntPending">0</div>
        <div class="cc-label">Pending</div>
    </div>
    <div class="cc-card cc-generated">
        <div class="cc-num" id="cntGenerated">0</div>
        <div class="cc-label">Generated</div>
    </div>
    <div class="cc-card cc-sent">
        <div class="cc-num" id="cntSent">0</div>
        <div class="cc-label">Sent</div>
    </div>
</div>

<div class="rp-top">
    <h1>Eligible Employees <small>Probation completed, awaiting confirmation</small></h1>
    <div class="rp-search">
        <span class="rp-search-icon">&#128269;</span>
        <input type="text" id="searchInput" placeholder="Search employee, department..." onkeyup="pgPage=1;renderView()">
    </div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" data-tab="all" onclick="switchTab('all')">All <span class="scr-tab-count" data-count="all">0</span></button>
    <button class="scr-tab" data-tab="pending" onclick="switchTab('pending')">Pending <span class="scr-tab-count" data-count="pending">0</span></button>
    <button class="scr-tab" data-tab="generated" onclick="switchTab('generated')">Generated <span class="scr-tab-count" data-count="generated">0</span></button>
    <button class="scr-tab" data-tab="sent" onclick="switchTab('sent')">Sent <span class="scr-tab-count" data-count="sent">0</span></button>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table">
            <thead><tr>
                <th class="rp-sortable" onclick="sortBy('employee')">Employee <span class="rp-sort-arrow" id="listSortArrow0" data-col="employee">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('job')">Job Title <span class="rp-sort-arrow" id="listSortArrow1" data-col="job">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('dept')">Department <span class="rp-sort-arrow" id="listSortArrow2" data-col="dept">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('designation')">Designation <span class="rp-sort-arrow" id="listSortArrow3" data-col="designation">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('type')">Type <span class="rp-sort-arrow" id="listSortArrow4" data-col="type">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('end')">Probation End <span class="rp-sort-arrow" id="listSortArrow5" data-col="end">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('score')">Score <span class="rp-sort-arrow" id="listSortArrow6" data-col="score">&#8597;</span></th>
                <th>Action</th>
            </tr></thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div class="rp-pagination" id="pgBar">
        <div class="rp-pg-info" id="pgInfo">Showing 0-0 of 0</div>
        <div class="rp-pg-center" id="pgCenter"></div>
        <div class="rp-pg-rows" id="pgRowsInfo"></div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
