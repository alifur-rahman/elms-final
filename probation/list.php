<?php
$basePath   = '..';
$pageTitle  = 'Probation Appraisal';
$activePage = 'probation';
$breadcrumb = 'Home / Probation / Probation Appraisal';
$pageStyles = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-active{border-left-color:var(--primary)}
.cc-card.cc-active .cc-num{color:var(--primary)}
.cc-card.cc-completed{border-left-color:var(--success)}
.cc-card.cc-completed .cc-num{color:var(--success)}
.cc-card.cc-extended{border-left-color:#f59e0b}
.cc-card.cc-extended .cc-num{color:#f59e0b}
.cc-card.cc-pending{border-left-color:#8b5cf6}
.cc-card.cc-pending .cc-num{color:#8b5cf6}

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

/* ===== TABS ===== */
.scr-tabs{display:flex;gap:0;margin-bottom:20px;border-bottom:2px solid var(--gray-200);flex-wrap:wrap}
.scr-tab{padding:12px 28px;font-size:14px;font-weight:600;background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-2px;cursor:pointer;color:var(--gray-500);transition:all .2s;font-family:inherit;display:flex;align-items:center;gap:8px}
.scr-tab.active{color:var(--primary);border-bottom-color:var(--primary)}
.scr-tab:hover{color:var(--primary)}
.scr-tab .scr-tab-count{background:var(--gray-100);color:var(--gray-600);font-size:12px;font-weight:600;padding:2px 8px;border-radius:10px;min-width:24px;text-align:center}
.scr-tab.active .scr-tab-count{background:rgba(37,99,235,.1);color:var(--primary)}
.tab-panel{display:none}
.tab-panel.active{display:block}

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
.rp-table .rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit;text-decoration:none;display:inline-flex;align-items:center}
.rp-table .rp-btn-text:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary:hover{background:var(--primary);color:#fff}
.rp-table .rp-btn-text.rp-btn-success{border-color:var(--success);color:var(--success);background:rgba(16,185,129,.04)}
.rp-table .rp-btn-text.rp-btn-success:hover{background:var(--success);color:#fff}
.rp-table .rp-btn-text.rp-btn-view{border-color:var(--gray-300);color:var(--gray-600)}
.rp-table .rp-btn-text.rp-btn-view:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
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

/* ===== MODAL DETAIL ===== */
.info-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-bottom:20px}
.info-item{padding:12px 16px;background:var(--gray-50);border-radius:8px;border:1px solid var(--gray-200)}
.info-item label{display:block;font-size:11px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px}
.info-item span{font-size:14px;color:var(--gray-800);font-weight:500}
.sb-section{margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--gray-100)}
.sb-section:last-of-type{border-bottom:none;margin-bottom:0;padding-bottom:0}
.sb-section-title{font-size:14px;font-weight:700;color:var(--gray-800);margin-bottom:16px;display:flex;align-items:center;gap:8px}
.sb-section-title .sb-icon{width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.sb-section-title .sb-icon.blue{background:#e8f0fe;color:var(--primary)}
.sb-section-title .sb-icon.green{background:#e6f4ea;color:var(--success)}
.sb-section-title .sb-icon.orange{background:#fef3e2;color:#e67e22}
.sb-section-title .sb-icon.purple{background:#f0e6ff;color:#7c3aed}
.sb-section-title .sb-icon.red{background:#fde8e8;color:var(--danger)}
.score-meter-bar{display:flex;align-items:center;gap:12px}
.score-bar-track{flex:1;height:10px;background:var(--gray-200);border-radius:5px;overflow:hidden}
.score-bar-fill{height:100%;border-radius:5px;background:linear-gradient(90deg,#f44336,#ff9800,#ffc107,#4caf50);transition:width .5s}
.score-bar-label{font-size:14px;font-weight:600;color:var(--primary);white-space:nowrap}
.score-labels{display:flex;justify-content:space-between;margin-top:2px;font-size:10px;color:var(--gray-500)}
.comment-box{background:#f8f9fa;border-radius:6px;padding:10px 14px;font-size:13px;color:#333;margin-top:4px;min-height:20px}
.comment-section{margin-bottom:16px}
.comment-section label{font-size:12px;font-weight:600;color:var(--gray-600);display:block;margin-bottom:4px}
.wf-container{display:flex;flex-direction:column;align-items:flex-start;padding:8px 0}
.wf-item{display:flex;align-items:center;gap:12px}
.wf-circle{width:32px;height:32px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:var(--gray-400);background:#fff;flex-shrink:0}
.wf-item.wf-done .wf-circle{border-color:var(--success);background:var(--success);color:#fff}
.wf-item.wf-active .wf-circle{border-color:var(--primary);background:var(--primary);color:#fff}
.wf-info{display:flex;flex-direction:column}
.wf-info strong{font-size:13px}
.wf-status{font-size:11px;color:var(--gray-500)}
.wf-comment{font-size:11px;color:#666;font-style:italic}
.wf-connector{width:2px;height:20px;background:var(--gray-300);margin-left:15px}
.wf-connector.wf-done{background:var(--success)}
.wf-connector.wf-active{background:var(--primary)}

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
 .info-grid{grid-template-columns:1fr 1fr}
 .modal-body .info-grid{grid-template-columns:1fr 1fr}
 .modal-body .sb-section{margin-bottom:16px;padding-bottom:14px}
 .modal-body .sb-section-title{font-size:13px;margin-bottom:10px}
 .modal-body .sb-section-title .sb-icon{width:24px;height:24px;font-size:12px}
 .score-meter-bar{flex-direction:column;align-items:stretch;gap:8px}
 .score-bar-label{text-align:right}
 .score-labels{font-size:9px}
 #mdScoreVal{font-size:22px}
 .wf-item{gap:8px}
 .wf-circle{width:28px;height:28px;font-size:11px}
 .wf-info strong{font-size:12px}
 .wf-connector{margin-left:13px;height:16px}
 .comment-box{font-size:12px;padding:8px 12px}
 .comment-section label{font-size:11px}
 .modal-body .rp-table-wrap{overflow-x:auto}
 .modal-body .rp-table{min-width:400px}
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
 .info-grid{grid-template-columns:1fr}
 .modal-body .info-grid{grid-template-columns:1fr}
 .modal-body .info-item{padding:10px 12px}
 .modal-body .sb-section{margin-bottom:14px;padding-bottom:12px}
 .modal-body .sb-section-title{font-size:12px;gap:6px;margin-bottom:8px}
 .modal-body .sb-section-title .sb-icon{width:22px;height:22px;font-size:11px;border-radius:6px}
 #mdScoreVal{font-size:20px}
 .wf-circle{width:26px;height:26px;font-size:10px}
 .wf-info{gap:2px}
 .wf-info strong{font-size:11px}
 .wf-status{font-size:10px}
 .wf-comment{font-size:10px}
 .wf-connector{margin-left:12px;height:12px}
 .comment-box{font-size:11px;padding:8px 10px}
 .comment-section label{font-size:10px}
 .modal-body .rp-table{min-width:350px}
 .modal-body .rp-table thead th{font-size:10px;padding:8px 10px}
 .modal-body .rp-table td{font-size:11px;padding:10px}
}
';
$pageScripts = <<<'EOSCRIPT'
var allData = [];
var eligibleData = [];
var departments = [];
var currentTab = "eligible";
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".scr-tab").forEach(function(b) { b.classList.remove("active"); });
    document.querySelectorAll(".tab-panel").forEach(function(p) { p.classList.remove("active"); });
    document.getElementById("tabBtn" + tab).classList.add("active");
    document.getElementById("tab" + tab).classList.add("active");
    sortCol = -1;
    currentPage = 1;
    document.getElementById("searchInput").value = "";
    document.getElementById("searchClear").style.display = "none";
    renderTable();
}

function sortBy(col) {
    if (sortCol === col) { sortDir = sortDir === "asc" ? "desc" : "asc"; }
    else { sortCol = col; sortDir = "asc"; }
    currentPage = 1;
    renderTable();
}

function getSortVal(o) {
    switch (sortCol) {
        case 0: return (o.employee_name || "").toLowerCase();
        case 1: return currentTab === "appraisals" ? (o.dept_name || "").toLowerCase() : (o.designation_name || "").toLowerCase();
        case 2: return o.joining_date || "";
        case 3: return currentTab === "appraisals" ? (o.end_date || "") : (o.days_left !== null ? o.days_left : 9999);
        case 4: return currentTab === "appraisals" ? (o.days_left !== null ? o.days_left : 9999) : "";
        case 5: return currentTab === "appraisals" ? (o.overall_score || 0) : "";
        case 6: return currentTab === "appraisals" ? (o.status || "").toLowerCase() : "";
        default: return "";
    }
}

function sortData(arr) {
    if (sortCol < 0) return arr;
    return arr.slice().sort(function(a, b) {
        var va = getSortVal(a), vb = getSortVal(b);
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

function getFiltered() {
    var q = document.getElementById("searchInput").value.trim().toLowerCase();
    var src = currentTab === "appraisals" ? allData : eligibleData;
    return sortData(src.filter(function(o) {
        var name = ((o.employee_name || "") + " " + (o.dept_name || "") + " " + (o.job_title || "") + " " + (o.designation_name || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function applyFilters() {
    var statusVal = document.getElementById("statusFilter").value;
    var deptVal = document.getElementById("deptFilter").value;
    document.getElementById("searchClear").style.display = document.getElementById("searchInput").value.length > 0 ? "block" : "none";
    var params = [];
    if (statusVal) params.push("status=" + statusVal);
    if (deptVal > 0) params.push("dept_id=" + deptVal);
    var qs = params.length ? "?" + params.join("&") : "";
    apiGet("ajax/appraisals_list.php" + qs, null, function(res) {
        allData = (res.success && res.data) ? res.data : [];
        renderStats();
        if (currentTab === "appraisals") renderTable();
    }, function() { allData = []; renderStats(); renderTable(); });
}

function clearSearch() {
    document.getElementById("searchInput").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderPaginationBar(total, totalPages, containerId) {
    var c = document.getElementById(containerId);
    if (totalPages <= 1) { c.innerHTML = ""; return; }
    var h = "<div class=\"rp-pg-info\">Page " + currentPage + " of " + totalPages + " &middot; " + total + " total</div>";
    h += "<div class=\"rp-pg-center\">";
    h += "<button class=\"rp-pg-btn\"" + (currentPage <= 1 ? " disabled" : "") + " onclick=\"goToPage(1)\">&laquo;</button>";
    h += "<button class=\"rp-pg-btn\"" + (currentPage <= 1 ? " disabled" : "") + " onclick=\"goToPage(" + (currentPage - 1) + ")\">&lsaquo;</button>";
    var s = Math.max(1, currentPage - 2), e = Math.min(totalPages, currentPage + 2);
    for (var p = s; p <= e; p++) {
        h += "<button class=\"rp-pg-btn" + (p === currentPage ? " rp-active" : "") + "\" onclick=\"goToPage(" + p + ")\">" + p + "</button>";
    }
    h += "<button class=\"rp-pg-btn\"" + (currentPage >= totalPages ? " disabled" : "") + " onclick=\"goToPage(" + (currentPage + 1) + ")\">&rsaquo;</button>";
    h += "<button class=\"rp-pg-btn\"" + (currentPage >= totalPages ? " disabled" : "") + " onclick=\"goToPage(" + totalPages + ")\">&raquo;</button>";
    h += "</div>";
    h += "<div class=\"rp-pg-rows\">Show <select onchange=\"changePerPage(this.value)\">";
    [10, 20, 50, 100].forEach(function(n) { h += "<option value=\"" + n + "\"" + (n === perPage ? " selected" : "") + ">" + n + "</option>"; });
    h += "</select></div>";
    c.innerHTML = h;
}

function goToPage(p) {
    var filtered = getFiltered();
    var tp = Math.ceil(filtered.length / perPage) || 1;
    p = Math.max(1, Math.min(p, tp));
    if (p === currentPage) return;
    currentPage = p;
    renderTable();
}

function changePerPage(n) { perPage = parseInt(n); currentPage = 1; renderTable(); }

function renderStats() {
    var active = allData.filter(function(a) { return a.status === "Pending" || a.status === "In Progress" || a.status === "Draft"; }).length;
    var completed = allData.filter(function(a) { return a.status === "Completed"; }).length;
    var extended = allData.filter(function(a) { return a.recommendation === "Extend"; }).length;
    var pendingConfirm = allData.filter(function(a) { return a.status === "Completed" && a.recommendation === "Confirm"; }).length;
    document.getElementById("ccActive").textContent = active;
    document.getElementById("ccCompleted").textContent = completed;
    document.getElementById("ccExtended").textContent = extended;
    document.getElementById("ccPending").textContent = pendingConfirm;
    document.getElementById("tabCountAppraisals").textContent = allData.length;
    document.getElementById("tabCountEligible").textContent = eligibleData.length;
}

function renderMobileCards(pageData) {
    var c = document.getElementById(currentTab === "eligible" ? "eligibleMobileList" : "appraisalsMobileList");
    if (!pageData.length) {
        var emptyMsg = currentTab === "appraisals" ? "No probation appraisals found" : "All employees have been evaluated";
        c.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4CB;</div><h3>" + emptyMsg + "</h3></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(o) {
        if (currentTab === "eligible") {
            var days = o.days_left !== null ? (o.days_left < 0 ? "Overdue " + Math.abs(o.days_left) + "d" : o.days_left + "d left") : "-";
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.name || "") + "<small>" + (o.job_title || "N/A") + "</small></div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Designation</label><span>" + (o.designation_name || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Joining Date</label><span>" + (o.joining_date || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Days Left</label><span>" + days + "</span></div>";
            h += "</div><div class=\"rp-mob-footer\"><a class=\"btn btn-primary btn-sm\" href=\"new.php?employee_id=" + o.id + "\">+ Create</a></div></div>";
        } else {
            var daysBadge = "";
            if (o.days_left !== null) {
                if (o.days_left < 0) daysBadge = "<span class=\"badge badge-danger\">Overdue by " + Math.abs(o.days_left) + "d</span>";
                else if (o.days_left <= 7) daysBadge = "<span class=\"badge badge-warning\">" + o.days_left + "d left</span>";
                else daysBadge = "<span class=\"badge badge-completed\">" + o.days_left + "d left</span>";
            }
            var statusBadge = o.status === "Completed" ? "<span class=\"badge badge-completed\">Completed</span>"
                : o.status === "In Progress" ? "<span class=\"badge badge-in-progress\">In Progress</span>"
                : o.status === "Draft" ? "<span class=\"badge badge-pending\">Draft</span>"
                : o.status === "Rejected" ? "<span class=\"badge badge-danger\">Rejected</span>"
                : "<span class=\"badge badge-pending\">" + o.status + "</span>";
            var actionBtn = o.status === "Draft"
                ? "<a class=\"btn btn-outline btn-sm\" href=\"edit.php?id=" + o.id + "\">Edit</a>"
                : "<button class=\"btn btn-primary btn-sm\" onclick=\"viewDetail(" + o.id + ")\">View</button>";
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.employee_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
            h += "<div class=\"rp-mob-badges\">" + statusBadge + "</div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Department</label><span>" + (o.dept_name || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Probation End</label><span>" + (o.end_date || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Days Left</label><span>" + daysBadge + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Score</label><span>" + (o.overall_score !== null ? o.overall_score.toFixed(2) : "-") + "</span></div>";
            h += "</div><div class=\"rp-mob-footer\">" + actionBtn + "</div></div>";
        }
    });
    c.innerHTML = h;
}

function renderTable() {
    var filtered = getFiltered();
    var tp = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > tp) currentPage = tp;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);
    var tbody = document.getElementById(currentTab === "eligible" ? "eligibleTableBody" : "appraisalsTableBody");
    tbody.innerHTML = "";
    var colCount = currentTab === "appraisals" ? 8 : 5;
    if (!pageData.length) {
        var emptyMsg = currentTab === "appraisals" ? "No probation appraisals found" : "All employees have been evaluated";
        tbody.innerHTML = "<tr><td colspan=\"" + colCount + "\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4CB;</div><h3>" + emptyMsg + "</h3></div></td></tr>";
    } else if (currentTab === "eligible") {
        pageData.forEach(function(e) {
            var days = e.days_left !== null ? (e.days_left < 0 ? "Overdue " + Math.abs(e.days_left) + "d" : e.days_left + "d left") : "-";
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (e.name || "") + "</strong><br><small style='color:#888'>" + (e.job_title || "") + "</small></td>" +
                "<td>" + (e.designation_name || "-") + "</td>" +
                "<td>" + (e.joining_date || "-") + "</td>" +
                "<td>" + days + "</td>" +
                "<td><div class=\"rp-actions-cell\"><a class=\"rp-btn-text rp-btn-primary\" href=\"new.php?employee_id=" + e.id + "\">+ Create</a></div></td>";
            tbody.appendChild(tr);
        });
    } else {
        pageData.forEach(function(a) {
            var daysBadge = "";
            if (a.days_left !== null) {
                if (a.days_left < 0) daysBadge = "<span class=\"badge badge-danger\">Overdue by " + Math.abs(a.days_left) + "d</span>";
                else if (a.days_left <= 7) daysBadge = "<span class=\"badge badge-warning\">" + a.days_left + "d left</span>";
                else daysBadge = "<span class=\"badge badge-completed\">" + a.days_left + "d left</span>";
            }
            var statusBadge = a.status === "Completed" ? "<span class=\"badge badge-completed\">Completed</span>"
                : a.status === "In Progress" ? "<span class=\"badge badge-in-progress\">In Progress</span>"
                : a.status === "Draft" ? "<span class=\"badge badge-pending\">Draft</span>"
                : a.status === "Rejected" ? "<span class=\"badge badge-danger\">Rejected</span>"
                : "<span class=\"badge badge-pending\">" + a.status + "</span>";
            var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
            var action = a.status === "Draft"
                ? "<a class=\"rp-btn-text rp-btn-primary\" href=\"edit.php?id=" + a.id + "\">Edit</a>"
                : "<button class=\"rp-btn-text rp-btn-view\" onclick=\"viewDetail(" + a.id + ")\">View</button>";
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (a.employee_name || "") + "</strong><br><small style='color:#888'>" + (a.job_title || "") + "</small></td>" +
                "<td>" + (a.dept_name || "-") + "</td>" +
                "<td>" + (a.joining_date || "-") + "</td>" +
                "<td>" + (a.end_date || "-") + "</td>" +
                "<td>" + daysBadge + "</td>" +
                "<td>" + score + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + action + "</div></td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    var prefix = currentTab === "appraisals" ? "appraisals" : "eligible";
    var sortCount = currentTab === "appraisals" ? 7 : 4;
    var pgId = currentTab === "eligible" ? "eligiblePagination" : "appraisalsPagination";
    renderPaginationBar(filtered.length, tp, pgId);
    updateSortArrows(sortCount, prefix);
}

function viewDetail(id) {
    apiGet("ajax/appraisals_get.php?id=" + id, null, function(res) {
        if (!res.success || !res.data) { showAlert("Not found", "error"); return; }
        var a = res.data;
        document.getElementById("mdTitleName").textContent = a.employee_name;
        document.getElementById("mdJoining").textContent = a.joining_date || "-";
        document.getElementById("mdEnd").textContent = a.end_date || "-";
        document.getElementById("mdProbation").textContent = a.probation_months ? a.probation_months + " months" : "-";
        document.getElementById("mdDaysLeft").textContent = a.days_left !== null ? (a.days_left < 0 ? "Overdue by " + Math.abs(a.days_left) + "d" : a.days_left + "d left") : "-";
        document.getElementById("mdDept").textContent = a.dept_name || "N/A";
        document.getElementById("mdDesignation").textContent = a.designation_name || "N/A";
        document.getElementById("mdStatus").textContent = a.status;
        document.getElementById("mdStatus").className = a.status === "Completed" ? "badge badge-completed" : a.status === "Draft" ? "badge badge-pending" : a.status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
        document.getElementById("mdRecommendation").textContent = a.recommendation || "-";
        document.getElementById("mdScoreVal").textContent = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
        var pct = a.overall_score !== null ? (a.overall_score / 5 * 100) : 0;
        document.getElementById("mdScoreBar").style.width = Math.min(pct, 100) + "%";
        document.getElementById("mdScoreLabel").textContent = a.overall_score !== null ? a.overall_score.toFixed(1) + " / 5" : "0 / 5";
        document.getElementById("mdSupervisorCmt").textContent = a.supervisor_comments || "No comments";
        document.getElementById("mdDeptCmt").textContent = a.dept_head_comments || "No comments";
        document.getElementById("mdHRCmt").textContent = a.hr_review || "No comments";
        document.getElementById("mdRemarks").textContent = a.remarks || "No remarks";
        var scoresHtml = "";
        if (a.scores && a.scores.length) {
            for (var i = 0; i < a.scores.length; i++) {
                var s = a.scores[i];
                var barW = (s.score / 5 * 100);
                scoresHtml += "<tr>" +
                    "<td>" + s.category_name + "</td>" +
                    "<td>" + s.weight + "%</td>" +
                    "<td><div style='display:flex;align-items:center;gap:8px'><div style='flex:1;height:6px;background:#e9ecef;border-radius:3px;overflow:hidden'><div style='height:100%;width:" + barW + "%;background:var(--primary);border-radius:3px'></div></div><span style='font-weight:600;font-size:13px;min-width:30px'>" + s.score.toFixed(1) + "</span></div></td>" +
                    "<td style='font-size:12px;color:#666;max-width:160px'>" + (s.comments || "-") + "</td>" +
                "</tr>";
            }
        } else {
            scoresHtml = "<tr><td colspan=\"4\" style=\"text-align:center;color:#888;padding:24px\">No scores recorded yet</td></tr>";
        }
        document.getElementById("mdScoresBody").innerHTML = scoresHtml;
        var wfHtml = "";
        if (a.workflow && a.workflow.length) {
            for (var i = 0; i < a.workflow.length; i++) {
                var w = a.workflow[i];
                var wfClass = w.status === "Approved" ? "wf-done" : w.status === "In Progress" ? "wf-active" : "";
                var icon = w.status === "Approved" ? "&#10003;" : (i + 1);
                wfHtml += "<div class=\"wf-item " + wfClass + "\">" +
                    "<div class=\"wf-circle\">" + icon + "</div>" +
                    "<div class=\"wf-info\"><strong>" + w.level + "</strong>" +
                    "<span class=\"wf-status\">" + (w.status === "Approved" ? "Approved by " + (w.approved_by || "System") : w.status) + "</span>" +
                    (w.comments ? "<span class=\"wf-comment\">" + w.comments + "</span>" : "") +
                    "</div></div>";
                if (i < a.workflow.length - 1) wfHtml += "<div class=\"wf-connector " + wfClass + "\"></div>";
            }
        } else {
            wfHtml = "<div style='text-align:center;padding:16px;color:#888'>No workflow data</div>";
        }
        document.getElementById("mdWorkflow").innerHTML = wfHtml;
        openModal("viewDetailModal");
    });
}

function loadEligibleEmployees() {
    apiGet("ajax/eligible_employees.php", null, function(res) {
        eligibleData = (res.success && res.data) ? res.data : [];
        renderStats();
        if (currentTab === "eligible") renderTable();
    }, function() { eligibleData = []; renderStats(); });
}

function loadDepartments() {
    apiGet("../ajax/departments.php", null, function(res) {
        departments = res.success && res.data ? res.data : [];
        var sel = document.getElementById("deptFilter");
        sel.innerHTML = "<option value=\"0\">All Departments</option>";
        for (var i = 0; i < departments.length; i++) {
            sel.innerHTML += "<option value=\"" + departments[i].id + "\">" + departments[i].name + "</option>";
        }
    });
}

function formatDate(d) {
    if (!d) return "";
    var date = new Date(d + "T00:00:00");
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return months[date.getMonth()] + " " + ("0" + date.getDate()).slice(-2) + ", " + date.getFullYear();
}

document.addEventListener("DOMContentLoaded", function() {
    loadDepartments();
    loadEligibleEmployees();
    applyFilters();
});
EOSCRIPT;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-active"><div><div class="cc-num" id="ccActive">0</div><div class="cc-label">Active Reviews</div></div></div>
    <div class="cc-card cc-completed"><div><div class="cc-num" id="ccCompleted">0</div><div class="cc-label">Completed</div></div></div>
    <div class="cc-card cc-extended"><div><div class="cc-num" id="ccExtended">0</div><div class="cc-label">Extended</div></div></div>
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Confirm Pending</div></div></div>
</div>

<div class="rp-top">
    <h1>Probation Appraisals <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <select class="form-control" id="deptFilter" onchange="applyFilters()" style="width:180px">
            <option value="0">All Departments</option>
        </select>
        <select class="form-control" id="statusFilter" onchange="applyFilters()" style="width:160px">
            <option value="">All Status</option>
            <option value="Draft">Draft</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchInput" placeholder="Search employees..." oninput="applyFilters()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
        <a class="btn btn-primary" href="new.php">+ New Appraisal</a>
    </div>
</div>

<div class="scr-tabs">
    <button class="scr-tab" id="tabBtnappraisals" onclick="switchTab('appraisals')">Appraisals <span class="scr-tab-count" id="tabCountAppraisals">0</span></button>
    <button class="scr-tab active" id="tabBtneligible" onclick="switchTab('eligible')">Eligible Employees <span class="scr-tab-count" id="tabCountEligible">0</span></button>
</div>

<div class="tab-panel" id="tabappraisals">
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Employee <span class="rp-sort-arrow" id="appraisalsSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Department <span class="rp-sort-arrow" id="appraisalsSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Joining Date <span class="rp-sort-arrow" id="appraisalsSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Probation End <span class="rp-sort-arrow" id="appraisalsSortArrow3"></span></th>
                        <th class="rp-sortable" onclick="sortBy(4)">Days Left <span class="rp-sort-arrow" id="appraisalsSortArrow4"></span></th>
                        <th class="rp-sortable" onclick="sortBy(5)">Score <span class="rp-sort-arrow" id="appraisalsSortArrow5"></span></th>
                        <th class="rp-sortable" onclick="sortBy(6)">Status <span class="rp-sort-arrow" id="appraisalsSortArrow6"></span></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="appraisalsTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="appraisalsMobileList"></div>
        <div id="appraisalsPagination" class="rp-pagination"></div>
    </div>
</div>

<div class="tab-panel active" id="tabeligible">
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Employee <span class="rp-sort-arrow" id="eligibleSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Designation <span class="rp-sort-arrow" id="eligibleSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Joining Date <span class="rp-sort-arrow" id="eligibleSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Days Left <span class="rp-sort-arrow" id="eligibleSortArrow3"></span></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="eligibleTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="eligibleMobileList"></div>
        <div id="eligiblePagination" class="rp-pagination"></div>
    </div>
</div>

<!-- View Detail Modal -->
<div class="modal-overlay" id="viewDetailModal" onclick="if(event.target===this)closeModal('viewDetailModal')" style="display:none">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Appraisal Details - <span id="mdTitleName">Employee</span></h3>
            <button class="modal-close" onclick="closeModal('viewDetailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon blue">&#x1F4CB;</span> Appraisal Information</div>
                <div class="info-grid">
                    <div class="info-item"><label>Joining Date</label><span id="mdJoining">-</span></div>
                    <div class="info-item"><label>Probation End</label><span id="mdEnd">-</span></div>
                    <div class="info-item"><label>Probation Period</label><span id="mdProbation">-</span></div>
                    <div class="info-item"><label>Days Left</label><span id="mdDaysLeft">-</span></div>
                    <div class="info-item"><label>Department</label><span id="mdDept">-</span></div>
                    <div class="info-item"><label>Designation</label><span id="mdDesignation">-</span></div>
                    <div class="info-item"><label>Status</label><span id="mdStatus" class="badge">-</span></div>
                    <div class="info-item"><label>Recommendation</label><span id="mdRecommendation">-</span></div>
                </div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F4CA;</span> Overall Score</div>
                <div class="score-meter-bar">
                    <div class="score-bar-track">
                        <div class="score-bar-fill" id="mdScoreBar" style="width:0%"></div>
                    </div>
                    <span class="score-bar-label" id="mdScoreLabel">0 / 5</span>
                </div>
                <div class="score-labels"><span>Poor</span><span>Average</span><span>Good</span><span>Excellent</span></div>
                <div style="text-align:right;font-size:28px;font-weight:700;color:var(--primary)" id="mdScoreVal">0.00</div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x1F4DD;</span> Category Scores</div>
                <table class="rp-table">
                    <thead><tr><th>Category</th><th>Weight</th><th>Score</th><th>Comments</th></tr></thead>
                    <tbody id="mdScoresBody"></tbody>
                </table>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon purple">&#x1F504;</span> Workflow Progress</div>
                <div class="wf-container" id="mdWorkflow"></div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon teal">&#x1F4AC;</span> Comments & Reviews</div>
                <div class="comment-section">
                    <label>Supervisor Comments</label>
                    <div class="comment-box" id="mdSupervisorCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>Department Head Comments</label>
                    <div class="comment-box" id="mdDeptCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>HR Review</label>
                    <div class="comment-box" id="mdHRCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>Overall Remarks</label>
                    <div class="comment-box" id="mdRemarks">No remarks</div>
                </div>
            </div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn" onclick="closeModal('viewDetailModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
