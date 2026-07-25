<?php
$basePath   = '..';
$pageTitle  = 'Approval Management';
$activePage = 'probation_approve';
$breadcrumb = 'Home / Probation / Approval Management';
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
.cc-card.cc-urgent{border-left-color:#f59e0b}
.cc-card.cc-urgent .cc-num{color:#f59e0b}

/* ===== HEADER ===== */
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.rp-top h1{font-size:20px;font-weight:700;color:var(--gray-900);margin:0;white-space:nowrap}
.rp-top h1 small{font-size:13px;font-weight:400;color:var(--gray-400);margin-left:8px}
.rp-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.rp-search{position:relative}
.rp-search input{width:240px;padding:9px 12px 9px 36px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit}
.rp-search input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.rp-search .rp-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:14px;pointer-events:none}
.rp-filter-select{padding:9px 12px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;font-family:inherit;background:#fff;color:var(--gray-700);cursor:pointer;min-width:140px}

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
.rp-table .rp-actions-cell{display:flex;gap:6px;white-space:nowrap}
.rp-table .rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit;text-decoration:none;display:inline-flex;align-items:center}
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

/* ===== BADGES ===== */
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap}
.badge-pending{background:#fef7e0;color:#e37400}
.badge-completed{background:#e6f4ea;color:#188038}
.badge-danger{background:#fce8e6;color:var(--danger)}
.badge-warning{background:#fef7e0;color:#e37400}
.badge-confirm{background:#e6f4ea;color:#188038}
.badge-extend{background:#fef7e0;color:#e37400}
.badge-transfer{background:#e8f0fe;color:var(--primary)}
.badge-terminate{background:#fce8e6;color:var(--danger)}
.level-pill{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:500}
.level-pill.current{background:#e8f0fe;color:var(--primary)}
.level-pill.current::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--primary);display:inline-block}

/* ===== MODAL DETAIL ===== */
.profile-card{display:flex;gap:16px;align-items:center;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:16px;margin-bottom:16px}
.profile-avatar{width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#1565c0);color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;flex-shrink:0}
.profile-details{flex:1;display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:8px}
.detail-item label{display:block;font-size:10px;color:var(--gray-500);margin-bottom:1px}
.detail-item span{font-size:12px;font-weight:500}

.level-chain{display:flex;align-items:center;gap:4px;flex-wrap:wrap;margin-bottom:12px}
.chain-step{display:flex;align-items:center;gap:4px;font-size:11px;padding:4px 10px;border-radius:4px;background:var(--gray-100);color:var(--gray-500)}
.chain-step.active{background:#e8f0fe;color:var(--primary);font-weight:600}
.chain-step.done{background:#e6f4ea;color:var(--success)}
.chain-step.rejected{background:#fce8e6;color:var(--danger)}
.chain-arrow{color:var(--gray-400);font-size:12px}

.accordion-section{border:1px solid var(--gray-200);border-radius:8px;margin-bottom:12px;overflow:hidden}
.accordion-header{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--gray-50);cursor:pointer;font-size:13px;font-weight:600;user-select:none;transition:background .15s}
.accordion-header:hover{background:var(--gray-100)}
.accordion-header .acc-arrow{transition:transform .2s;font-size:12px;color:var(--gray-500)}
.accordion-header.open .acc-arrow{transform:rotate(90deg)}
.accordion-body{padding:12px 16px;display:none}
.accordion-body.open{display:block}

.candidate-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:4px 24px;font-size:12px}
.candidate-info-grid .info-label{color:var(--gray-500);font-size:10px;text-transform:uppercase;letter-spacing:.3px}
.candidate-info-grid .info-value{font-weight:500;margin-bottom:6px}
.candidate-info-grid .info-value.empty{color:var(--gray-400);font-style:italic}
.candidate-info-grid .full{grid-column:1/-1}
.section-divider{border:none;border-top:1px solid var(--gray-200);margin:14px 0}

.screening-score-card{display:flex;align-items:center;gap:20px;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:14px 18px;margin-bottom:12px}
.screening-score-card .ssc-rating{text-align:center;min-width:70px}
.screening-score-card .ssc-rating .ssc-val{font-size:28px;font-weight:700;line-height:1}
.screening-score-card .ssc-rating .ssc-label{font-size:10px;color:var(--gray-500)}
.screening-score-card .ssc-details{flex:1}
.screening-score-card .ssc-details .ssc-row{display:flex;gap:12px;flex-wrap:wrap;font-size:12px;margin-top:4px}
.screening-score-card .ssc-details .ssc-row span{display:flex;align-items:center;gap:4px}
.match-badge{display:inline-block;font-size:10px;font-weight:600;padding:2px 10px;border-radius:10px}
.match-badge.High{background:#e6f4ea;color:#188038}
.match-badge.Medium{background:#fef7e0;color:#e37400}
.match-badge.Low{background:#fce8e6;color:#d93025}

.interview-round-card{border:1px solid var(--gray-200);border-radius:8px;margin-bottom:10px;overflow:hidden}
.interview-round-card .irc-header{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--gray-50);cursor:pointer;font-size:13px;font-weight:600;user-select:none}
.interview-round-card .irc-header:hover{background:var(--gray-100)}
.interview-round-card .irc-header .irc-arrow{transition:transform .2s;font-size:12px;color:var(--gray-500)}
.interview-round-card .irc-header.open .irc-arrow{transform:rotate(90deg)}
.interview-round-card .irc-body{padding:12px 14px;display:none}
.interview-round-card .irc-body.open{display:block}
.interview-round-card .irc-meta{display:flex;gap:16px;font-size:12px;margin-bottom:8px;flex-wrap:wrap}
.interview-round-card .irc-meta label{color:var(--gray-500)}
.irc-score-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(100px,1fr));gap:8px;margin:8px 0}
.irc-score-item{text-align:center;padding:8px 6px;border:1px solid var(--gray-200);border-radius:6px;background:#fff}
.irc-score-item .irc-sval{font-size:18px;font-weight:700}
.irc-score-item .irc-slabel{font-size:10px;color:var(--gray-500)}

.score-card-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px}
.score-card{text-align:center;padding:10px 6px;border:1px solid var(--gray-200);border-radius:6px;background:var(--gray-50)}
.score-card .sc-val{font-size:18px;font-weight:700}
.score-card .sc-label{font-size:10px;color:var(--gray-500);margin-top:2px}
.score-card .sc-weight{font-size:9px;color:var(--gray-400)}

.approval-timeline{position:relative;padding-left:24px}
.approval-timeline::before{content:"";position:absolute;left:8px;top:0;bottom:0;width:2px;background:var(--gray-200)}
.tl-item{position:relative;padding:10px 0 10px 20px}
.tl-item::before{content:"";position:absolute;left:-18px;top:14px;width:10px;height:10px;border-radius:50%;background:var(--gray-300)}
.tl-item.done::before{background:var(--success)}
.tl-item.current::before{background:var(--primary);box-shadow:0 0 0 3px rgba(26,115,232,0.2)}
.tl-item.rejected::before{background:var(--danger)}
.tl-item .tl-time{font-size:11px;color:var(--gray-500)}
.tl-item .tl-label{font-size:13px;font-weight:500}
.tl-item .tl-user{font-size:11px;color:var(--gray-500)}
.tl-item .tl-comment{font-size:12px;color:var(--gray-600);margin-top:4px;padding:8px 12px;background:var(--gray-50);border-radius:6px}

.decision-option{display:flex;align-items:center;gap:8px;padding:12px 20px;border:2px solid var(--gray-200);border-radius:8px;cursor:pointer;transition:all .2s}
.decision-option:hover{border-color:var(--primary)}
.decision-option input[type="radio"]{width:16px;height:16px;accent-color:var(--primary)}

.audit-warning{display:flex;align-items:flex-start;gap:10px;padding:12px 16px;background:#fef7e0;border:1px solid #f9ab00;border-radius:6px;font-size:12px;color:var(--gray-700)}
.audit-warning .aw-icon{font-size:18px;flex-shrink:0}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
 .rp-top h1{font-size:18px}
 .rp-search input{width:200px}
 .rp-filter-select{min-width:120px}
}
@media(max-width:768px){
 .page-content{padding:16px}
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;margin-bottom:18px}
 .cc-card{padding:14px;gap:10px}
 .cc-card .cc-num{font-size:22px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
 .rp-top h1 small{display:block;margin:4px 0 0;font-size:12px}
 .rp-actions{width:100%;flex-wrap:wrap}
 .rp-actions .rp-filter-select{flex:1;min-width:0}
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
 .profile-card{flex-direction:column;align-items:center;text-align:center}
 .profile-details{grid-template-columns:1fr 1fr;gap:8px}
 .candidate-info-grid{grid-template-columns:1fr}
 .screening-score-card{flex-direction:column;align-items:stretch;gap:12px}
 .screening-score-card .ssc-rating{min-width:auto}
 .irc-score-grid{grid-template-columns:repeat(auto-fit,minmax(80px,1fr))}
 .score-card-grid{grid-template-columns:repeat(auto-fill,minmax(80px,1fr))}
 .decision-option{flex:1;justify-content:center;padding:10px 12px;font-size:13px}
 .accordion-body{padding:10px 12px}
 .modal-body .candidate-info-grid{grid-template-columns:1fr}
 .modal-body .irc-score-grid{grid-template-columns:repeat(auto-fit,minmax(70px,1fr))}
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
 .profile-card{padding:14px}
 .profile-avatar{width:40px;height:40px;font-size:14px}
 .profile-details{grid-template-columns:1fr}
 .detail-item{font-size:11px}
 .chain-step{font-size:10px;padding:3px 8px}
 .chain-arrow{font-size:10px}
 .decision-option{flex-direction:column;gap:6px;font-size:12px}
 .audit-warning{font-size:11px;padding:10px 12px}
 .audit-warning .aw-icon{font-size:16px}
 .screening-score-card{padding:12px}
 .screening-score-card .ssc-rating .ssc-val{font-size:22px}
 .irc-score-grid{grid-template-columns:repeat(2,1fr)}
 .score-card-grid{grid-template-columns:repeat(2,1fr)}
 .tl-item{padding:8px 0 8px 16px}
 .tl-item .tl-label{font-size:12px}
 .tl-comment{font-size:11px}
}
';
$pageScripts = <<<'EOSCRIPT'
var approvalData = [];
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;

function loadDepartments() {
    apiGet("../ajax/departments.php", null, function(res) {
        var depts = res.success && res.data ? res.data : [];
        var sel = document.getElementById("deptFilter");
        sel.innerHTML = "<option value=\"\">All Departments</option>";
        for (var i = 0; i < depts.length; i++) {
            sel.innerHTML += "<option value=\"" + depts[i].id + "\">" + depts[i].name + "</option>";
        }
    });
}

function sortBy(col) {
    if (sortCol === col) sortAsc = !sortAsc;
    else { sortCol = col; sortAsc = true; }
    renderApprovals();
}

function getSortVal(a, col) {
    switch(col) {
        case "employee": return (a.employee_name || "").toLowerCase();
        case "department": return (a.dept_name || "").toLowerCase();
        case "score": return a.overall_score || 0;
        case "level": return (a.current_level || "").toLowerCase();
        case "status": return (a.status || "").toLowerCase();
        case "days": return a.days_left != null ? a.days_left : 9999;
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

function renderApprovals() {
    var level = document.getElementById("levelFilter").value;
    var deptId = document.getElementById("deptFilter").value;
    var params = [];
    if (level) params.push("level=" + level);
    if (deptId) params.push("dept_id=" + deptId);
    var qs = params.length ? "?" + params.join("&") : "";
    apiGet("ajax/approval_list.php" + qs, null, function(res) {
        approvalData = res.success && res.data ? res.data : [];
        var search = document.getElementById("searchInput").value.toLowerCase().trim();
        var filtered = search ? approvalData.filter(function(a) {
            return a.employee_name.toLowerCase().includes(search) || a.dept_name.toLowerCase().includes(search) || (a.designation_name || "").toLowerCase().includes(search);
        }) : approvalData.slice();

        if (sortCol) {
            filtered.sort(function(a, b) {
                var va = getSortVal(a, sortCol);
                var vb = getSortVal(b, sortCol);
                if (va < vb) return sortAsc ? -1 : 1;
                if (va > vb) return sortAsc ? 1 : -1;
                return 0;
            });
        }

        var pending = approvalData.filter(function(a) { return a.status === "Pending" || a.status === "In Progress"; }).length;
        var completed = approvalData.filter(function(a) { return a.status === "Completed"; }).length;
        var urgent = approvalData.filter(function(a) { return a.days_left !== null && a.days_left >= 0 && a.days_left <= 7; }).length;

        document.getElementById("statPending").textContent = pending;
        document.getElementById("statApproved").textContent = completed;
        document.getElementById("statUrgent").textContent = urgent;

        var totalPages = Math.max(1, Math.ceil(filtered.length / pgRows));
        if (pgPage > totalPages) pgPage = totalPages;
        var start = (pgPage - 1) * pgRows;
        var paged = filtered.slice(start, start + pgRows);

        renderTable(paged);
        renderMobileCards(paged);
        renderPagination(filtered.length, totalPages);
        updateSortArrows(6, "list");
    });
}

function renderTable(rows) {
    var tbody = document.getElementById("approvalBody");
    if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="rp-empty"><div class="rp-empty-icon">\uD83D\uDCCB</div><h3>No approvals found</h3><p>No items match your current filters.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var a = rows[i];
        var daysBadge = "";
        if (a.days_left !== null) {
            if (a.days_left < 0) daysBadge = '<span class="badge badge-danger">Overdue</span>';
            else if (a.days_left <= 7) daysBadge = '<span class="badge badge-warning">' + a.days_left + 'd</span>';
            else daysBadge = '<span class="badge badge-completed">' + a.days_left + 'd</span>';
        }
        var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
        var statusBadge = a.status === "Completed" ? '<span class="badge badge-completed">Completed</span>'
            : a.status === "In Progress" ? '<span class="badge badge-pending">In Progress</span>'
            : '<span class="badge badge-pending">' + a.status + '</span>';
        var levelHtml = a.current_level
            ? '<span class="level-pill current">' + a.current_level + '</span>'
            : "-";
        html += "<tr>" +
            "<td><strong>" + a.employee_name + "</strong><br><small style='color:#888'>" + (a.designation_name || "") + "</small></td>" +
            "<td>" + a.dept_name + "</td>" +
            "<td>" + score + "</td>" +
            "<td>" + levelHtml + "</td>" +
            "<td>" + statusBadge + "</td>" +
            "<td>" + daysBadge + "</td>" +
            '<td><div class="rp-actions-cell"><button class="rp-btn-text rp-btn-primary" onclick="openApproval(' + a.id + ')">Review</button></div></td>' +
        "</tr>";
    }
    tbody.innerHTML = html;
}

function renderMobileCards(rows) {
    var container = document.getElementById("mobileList");
    if (rows.length === 0) {
        container.innerHTML = '<div class="rp-empty"><div class="rp-empty-icon">\uD83D\uDCCB</div><h3>No approvals found</h3><p>No items match your current filters.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var a = rows[i];
        var daysBadge = "";
        if (a.days_left !== null) {
            if (a.days_left < 0) daysBadge = '<span class="badge badge-danger">Overdue</span>';
            else if (a.days_left <= 7) daysBadge = '<span class="badge badge-warning">' + a.days_left + 'd</span>';
            else daysBadge = '<span class="badge badge-completed">' + a.days_left + 'd</span>';
        }
        var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
        var statusBadge = a.status === "Completed" ? '<span class="badge badge-completed">Completed</span>'
            : a.status === "In Progress" ? '<span class="badge badge-pending">In Progress</span>'
            : '<span class="badge badge-pending">' + a.status + '</span>';
        var levelPill = a.current_level ? '<span class="level-pill current">' + a.current_level + '</span>' : "";
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + a.employee_name + '<small>' + (a.designation_name || "") + '</small></div>' +
                '<div class="rp-mob-badges">' + statusBadge + daysBadge + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Department</label><span>' + a.dept_name + '</span></div>' +
                '<div class="rp-mob-field"><label>Score</label><span>' + score + ' / 5</span></div>' +
                '<div class="rp-mob-field"><label>Current Level</label><span>' + (levelPill || '-') + '</span></div>' +
                '<div class="rp-mob-field"><label>Days Left</label><span>' + (a.days_left !== null ? a.days_left + ' days' : '-') + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer"><button class="rp-btn-text rp-btn-primary" onclick="openApproval(' + a.id + ')">Review &amp; Approve</button></div>' +
        '</div>';
    }
    container.innerHTML = html;
}

function renderPagination(total, totalPages) {
    var pgBar = document.querySelector(".rp-pagination");
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
    renderApprovals();
}

function changeRows(val) {
    pgRows = parseInt(val) || 10;
    pgPage = 1;
    renderApprovals();
}

function toggleAccordion(header) {
    header.classList.toggle("open");
    var body = header.nextElementSibling;
    if (body) body.classList.toggle("open");
}

var LEVELS = ["Self Assessment", "Supervisor", "Dept Head", "HR Review", "GM", "Managing Director"];

function actionLabel(action) {
    var labels = {
        screening_shortlist: "Shortlisted", screening_reject: "Screening Rejected", screening_hold: "Screening Hold",
        interview_scheduled: "Interview Scheduled", interview_pass: "Interview Passed", interview_hold: "Interview Hold", interview_fail: "Interview Failed",
        offer_created: "Offer Created", offer_letter_generated: "Offer Letter Generated", offer_sent: "Offer Sent", offer_approved: "Offer Approved",
        salary_proposed: "Salary Proposed", salary_finalized: "Salary Finalized",
        probation_submitted: "Probation Submitted", probation_updated: "Probation Updated", probation_approved: "Probation Approved", probation_rejected: "Probation Rejected"
    };
    return labels[action] || action.replace(/_/g, " ").replace(/\b\w/g, function(c){return c.toUpperCase();});
}
function actionGroup(action) {
    if (action.indexOf("screening_") === 0) return "screening";
    if (action.indexOf("interview_") === 0) return "interview";
    if (action.indexOf("offer_") === 0 || action.indexOf("salary_") === 0) return "offer";
    return "probation";
}

function openApproval(id) {
    apiGet("ajax/appraisals_get.php?id=" + id, null, function(res) {
        if (!res.success || !res.data) { showAlert("Not found", "error"); return; }
        var a = res.data;
        document.getElementById("actProbationId").value = a.id;
        var curIdx = 0;
        for (var i = 0; i < LEVELS.length; i++) {
            if (LEVELS[i] === a.current_level) { curIdx = i; break; }
        }
        var initials = (a.employee_name || "?").split(" ").map(function(s) { return s.charAt(0); }).join("").slice(0, 2).toUpperCase();
        document.getElementById("actProfileCard").innerHTML =
            '<div class="profile-avatar">' + initials + '</div>' +
            '<div class="profile-details">' +
                '<div class="detail-item"><label>Employee</label><span>' + a.employee_name + '</span></div>' +
                '<div class="detail-item"><label>Department</label><span>' + (a.dept_name || "N/A") + '</span></div>' +
                '<div class="detail-item"><label>Score</label><span>' + (a.overall_score !== null ? a.overall_score.toFixed(2) : "-") + ' / 5</span></div>' +
                '<div class="detail-item"><label>End Date</label><span>' + (a.end_date || "N/A") + '</span></div>' +
            '</div>';
        var chainHtml = "";
        for (var i = 0; i < LEVELS.length; i++) {
            var l = LEVELS[i];
            var cls = "";
            if (i < curIdx) cls = "done";
            else if (i === curIdx && a.status !== "Completed") cls = "active";
            else if (a.status === "Completed") cls = "done";
            var check = cls === "done" ? "\u2713 " : "";
            chainHtml += "<div class=\"chain-step " + cls + "\">" + check + l + "</div>";
            if (i < LEVELS.length - 1) chainHtml += "<span class=\"chain-arrow\">\u2192</span>";
        }
        document.getElementById("actLevelChain").innerHTML = chainHtml;

        var infoFields = [
            ["Candidate Code", a.candidate_code, "half"], ["Status", a.candidate_status, "half"],
            ["Email", a.email, "full"], ["Phone", a.phone, "half"],
            ["Gender", a.gender || "\u2014", "half"], ["Date of Birth", a.dob || "\u2014", "half"],
            ["Blood Group", a.blood_group || "\u2014", "half"], ["NID", a.nid || "\u2014", "half"],
            ["Present Address", a.present_address || "\u2014", "full"],
            ["Permanent Address", a.permanent_address || "\u2014", "full"],
            ["Applied At", a.applied_at || "\u2014", "half"], ["Current Salary", a.current_salary != null ? "\u09F3" + Number(a.current_salary).toFixed(2) : "\u2014", "half"],
        ];
        document.getElementById("actPersonalInfo").innerHTML = infoFields.map(function(f) {
            var valClass = f[1] === "\u2014" || f[1] === "" ? "info-value empty" : "info-value";
            return '<div class="' + f[2] + '"><div class="info-label">' + f[0] + '</div><div class="' + valClass + '">' + f[1] + '</div></div>';
        }).join("");

        var jobFields = [
            ["Job Title", a.job_title, "half"], ["Department", a.dept_name, "half"],
            ["Designation", a.designation_name, "half"], ["Job Location", a.job_location || "\u2014", "half"],
            ["Job Type", a.job_type || "\u2014", "half"], ["Education", a.job_education || "\u2014", "full"],
            ["Experience", a.job_experience !== null ? a.job_experience + " yrs" : "\u2014", "half"],
            ["Salary Range", a.job_salary_min !== null ? "$" + a.job_salary_min.toFixed(2) + " - $" + a.job_salary_max.toFixed(2) : "\u2014", "half"],
            ["Skills", a.job_skills || "\u2014", "full"],
            ["Offer Salary", a.offer_salary !== null ? "$" + a.offer_salary.toFixed(2) : "\u2014", "half"],
            ["Offer Sent", a.offer_sent_at || "\u2014", "half"],
            ["Offer Accepted", a.offer_accepted_at || "\u2014", "half"],
            ["Offer Notes", a.offer_notes || "\u2014", "full"],
            ["Offer Response Notes", a.offer_response_notes || "\u2014", "full"],
        ];
        document.getElementById("actJobOfferInfo").innerHTML = jobFields.map(function(f) {
            var valClass = f[1] === "\u2014" || f[1] === "" ? "info-value empty" : "info-value";
            return '<div class="' + f[2] + '"><div class="info-label">' + f[0] + '</div><div class="' + valClass + '">' + f[1] + '</div></div>';
        }).join("");

        var scrHtml = "";
        if (a.screenings && a.screenings.length) {
            for (var si = 0; si < a.screenings.length; si++) {
                var sc = a.screenings[si];
                var ratingColor = sc.rating >= 4 ? "var(--success)" : sc.rating >= 3 ? "var(--warning)" : "var(--danger)";
                var matchCls = function(v) { return "match-badge " + (v || "Low"); };
                scrHtml += '<div class="screening-score-card">' +
                    '<div class="ssc-rating">' +
                        '<div class="ssc-val" style="color:' + ratingColor + '">' + (sc.rating !== null ? sc.rating.toFixed(1) : "\u2014") + '</div>' +
                        '<div class="ssc-label">Overall Rating</div>' +
                    '</div>' +
                    '<div class="ssc-details">' +
                        '<div style="font-size:12px;font-weight:500">' + (sc.screened_by || "\u2014") +
                        ' <span style="color:var(--gray-500);font-weight:400">on ' + (sc.date || "\u2014") + '</span></div>' +
                        '<div class="ssc-row">' +
                            '<span>\uD83D\uDCAC Communication: <strong>' + (sc.communication_score !== null ? sc.communication_score.toFixed(1) + "/5" : "\u2014") + '</strong></span>' +
                            '<span>\uD83C\uDF93 Education: <span class="' + matchCls(sc.education_match) + '">' + (sc.education_match || "\u2014") + '</span></span>' +
                            '<span>\uD83C\uDFE2 Experience: <span class="' + matchCls(sc.experience_match) + '">' + (sc.experience_match || "\u2014") + '</span></span>' +
                            '<span>\uD83D\uDD27 Skills: <span class="' + matchCls(sc.skill_match) + '">' + (sc.skill_match || "\u2014") + '</span></span>' +
                        '</div>' +
                        (sc.remarks ? '<div style="font-size:12px;color:var(--gray-600);margin-top:4px">\uD83D\uDCDD ' + sc.remarks + '</div>' : "") +
                        '<div style="font-size:11px;color:var(--gray-400);margin-top:4px">Result: <strong>' + (sc.result || "\u2014") + '</strong> &middot; ' + sc.created_at + '</div>' +
                    '</div>' +
                '</div>';
            }
        } else {
            scrHtml = "<div style='text-align:center;padding:16px;color:#888'>No screening data available</div>";
        }
        document.getElementById("actScreeningInfo").innerHTML = scrHtml;

        var intHtml = "";
        if (a.interviews && a.interviews.length) {
            for (var ii = 0; ii < a.interviews.length; ii++) {
                var iv = a.interviews[ii];
                var decColor = iv.decision === "Pass" ? "var(--success)" : iv.decision === "Fail" ? "var(--danger)" : "var(--warning)";
                var decBg = iv.decision === "Pass" ? "#e6f4ea" : iv.decision === "Fail" ? "#fce8e6" : "#fef7e0";
                var isOpen = ii === 0 ? "open" : "";
                intHtml += '<div class="interview-round-card">' +
                    '<div class="irc-header ' + isOpen + '" onclick="this.classList.toggle(\'open\');var b=this.nextElementSibling;if(b)b.classList.toggle(\'open\')">' +
                        '<span>Round ' + iv.round + ': ' + (iv.round_name || "Interview") + '</span>' +
                        '<span><span class="badge" style="background:' + decBg + ';color:' + decColor + ';font-size:10px;padding:2px 10px">' + (iv.decision || "Pending") + '</span> <span class="irc-arrow">&#9654;</span></span>' +
                    '</div>' +
                    '<div class="irc-body ' + isOpen + '">' +
                        '<div class="irc-meta">' +
                            '<span><label>Type:</label> ' + (iv.type || "\u2014") + '</span>' +
                            '<span><label>Interviewer:</label> ' + (iv.interviewer || "\u2014") + '</span>' +
                            '<span><label>Date:</label> ' + (iv.date || "\u2014") + '</span>' +
                            (iv.is_final_round ? '<span class="badge badge-completed" style="font-size:10px">Final Round</span>' : "") +
                        '</div>' +
                        '<div class="irc-score-grid">' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_technical >= 4 ? "var(--success)" : iv.score_technical >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_technical !== null ? iv.score_technical.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Technical</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_communication >= 4 ? "var(--success)" : iv.score_communication >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_communication !== null ? iv.score_communication.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Communication</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_problem_solving >= 4 ? "var(--success)" : iv.score_problem_solving >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_problem_solving !== null ? iv.score_problem_solving.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Problem Solving</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_attitude >= 4 ? "var(--success)" : iv.score_attitude >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_attitude !== null ? iv.score_attitude.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Attitude</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.rating >= 4 ? "var(--success)" : iv.rating >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.rating !== null ? iv.rating.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Overall</div></div>' +
                        '</div>' +
                        (iv.feedback ? '<div style="font-size:12px;color:var(--gray-600);margin-top:6px;padding:8px 10px;background:var(--gray-50);border-radius:6px">\uD83D\uDCDD ' + iv.feedback + '</div>' : "") +
                        (iv.reject_reason ? '<div style="font-size:12px;color:var(--danger);margin-top:4px">\u274C ' + iv.reject_reason + '</div>' : "") +
                    '</div>' +
                '</div>';
            }
        } else {
            intHtml = "<div style='text-align:center;padding:16px;color:#888'>No interview data available</div>";
        }
        document.getElementById("actInterviewInfo").innerHTML = intHtml;

        var recColors = { Confirm: "badge-confirm", Extend: "badge-extend", Transfer: "badge-transfer", Terminate: "badge-terminate" };
        var recClass = recColors[a.recommendation] || "badge-pending";
        document.getElementById("actSummary").innerHTML =
            '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:8px">' +
                '<span style="font-size:13px"><strong>Probation Period:</strong> ' + (a.joining_date || "-") + ' to ' + (a.end_date || "-") + '</span>' +
                '<span class="badge ' + recClass + '" style="font-size:13px;padding:4px 16px">' + (a.recommendation || "Pending") + '</span>' +
            '</div>' +
            '<p style="font-size:13px;margin:4px 0"><strong>Status:</strong> ' + a.status + '</p>' +
            '<p style="font-size:13px;margin:4px 0"><strong>Remarks:</strong> ' + (a.remarks || "No remarks yet") + '</p>';

        var scoreHtml = "";
        if (a.scores && a.scores.length) {
            for (var i = 0; i < a.scores.length; i++) {
                var s = a.scores[i];
                var pct = (s.score / 5 * 100);
                var color = pct >= 80 ? "var(--success)" : pct >= 60 ? "var(--warning)" : "var(--danger)";
                scoreHtml += '<div class="score-card">' +
                    '<div class="sc-val" style="color:' + color + '">' + s.score.toFixed(1) + '</div>' +
                    '<div class="sc-weight">' + s.weight + '%</div>' +
                    '<div class="sc-label">' + s.category_name + '</div>' +
                '</div>';
            }
        } else {
            scoreHtml = "<div style='text-align:center;padding:16px;color:#888'>No scores</div>";
        }
        document.getElementById("actScoreCards").innerHTML = scoreHtml;

        var histHtml = "";
        if (a.workflow && a.workflow.length) {
            for (var i = 0; i < LEVELS.length; i++) {
                var lvl = LEVELS[i];
                var wf = null;
                for (var j = 0; j < a.workflow.length; j++) {
                    if (a.workflow[j].level === lvl) { wf = a.workflow[j]; break; }
                }
                var hcls = "";
                var decision = "Awaiting";
                if (wf) {
                    if (wf.status === "Approved") { hcls = "done"; decision = "Approved"; }
                    else if (wf.status === "Rejected") { hcls = "rejected"; decision = "Rejected"; }
                    else if (wf.status === "Sent Back") { hcls = "done"; decision = "Sent Back"; }
                    else { hcls = "current"; decision = "Pending Review"; }
                }
                var userInfo = wf && wf.approved_by ? wf.level + " \u2014 " + wf.approved_by : lvl + " \u2014 Pending";
                var actionTime = wf && wf.action_at ? wf.action_at : "Pending";
                histHtml += '<div class="tl-item ' + hcls + '">' +
                    '<div class="tl-time">' + actionTime + '</div>' +
                    '<div class="tl-label">' + userInfo + '</div>' +
                    '<div class="tl-user">Decision: ' + decision + '</div>' +
                    (wf && wf.comments ? '<div class="tl-comment">"' + wf.comments + '"</div>' : '') +
                '</div>';
            }
        } else {
            histHtml = "<div style='text-align:center;padding:16px;color:#888'>No approval history yet</div>";
        }
        document.getElementById("actHistory").innerHTML = histHtml;

        document.querySelectorAll(".decision-option").forEach(function(el) {
            el.style.borderColor = "var(--gray-200)";
            el.style.background = "transparent";
        });
        document.getElementById("actComments").value = "";
        selectDecision("Approved");
        openModal("approvalModal");
    });
}

function selectDecision(val) {
    var colors = { Approved: "var(--success)", "Send Back": "var(--warning)", Rejected: "var(--danger)" };
    var bgColors = { Approved: "#e6f4ea", "Send Back": "#fef7e0", Rejected: "#fce8e6" };
    var labels = ["actApproveLabel", "actSendBackLabel", "actRejectLabel"];
    for (var i = 0; i < labels.length; i++) {
        var el = document.getElementById(labels[i]);
        if (!el) continue;
        el.style.borderColor = "var(--gray-200)";
        el.style.background = "transparent";
    }
    var key = val === "Send Back" ? "Send Back" : val;
    var selEl = val === "Approved" ? document.getElementById("actApproveLabel") : val === "Send Back" ? document.getElementById("actSendBackLabel") : document.getElementById("actRejectLabel");
    if (selEl) {
        selEl.style.borderColor = colors[key] || "var(--gray-200)";
        selEl.style.background = bgColors[key] || "transparent";
    }
    var rb = document.querySelector('input[name="actDecision"][value="' + val + '"]');
    if (rb) rb.checked = true;
}

function submitApproval() {
    var id = parseInt(document.getElementById("actProbationId").value);
    var decision = document.querySelector('input[name="actDecision"]:checked');
    if (!decision) { showAlert("Please select a decision", "error"); return; }
    var comments = document.getElementById("actComments").value.trim();
    if (!comments) { showAlert("Please enter comments", "error"); return; }
    apiPost("ajax/approval_action.php", { appraisal_id: id, action: decision.value === "Approved" ? "approve" : decision.value === "Send Back" ? "sendback" : "reject", comments: comments }, function(res) {
        showAlert(res.message || "Decision recorded!", "success");
        closeModal("approvalModal");
        renderApprovals();
    });
}

document.addEventListener("DOMContentLoaded", function() {
    loadDepartments();
    renderApprovals();
});
EOSCRIPT;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-active">
        <div class="cc-num" id="statPending">0</div>
        <div class="cc-label">Pending Approval</div>
    </div>
    <div class="cc-card cc-completed">
        <div class="cc-num" id="statApproved">0</div>
        <div class="cc-label">Completed</div>
    </div>
    <div class="cc-card cc-urgent">
        <div class="cc-num" id="statUrgent">0</div>
        <div class="cc-label">Urgent (&lt; 7 days)</div>
    </div>
</div>

<div class="rp-top">
    <h1>Approval Queue <small>Probation appraisal reviews</small></h1>
    <div class="rp-actions">
        <select class="rp-filter-select" id="levelFilter" onchange="pgPage=1;renderApprovals()">
            <option value="">All Levels</option>
            <option value="Supervisor">Supervisor</option>
            <option value="Dept Head">Dept Head</option>
            <option value="HR Review">HR Review</option>
            <option value="GM">GM</option>
            <option value="Managing Director">Managing Director</option>
        </select>
        <select class="rp-filter-select" id="deptFilter" onchange="pgPage=1;renderApprovals()"><option value="">All Departments</option></select>
        <div class="rp-search">
            <span class="rp-search-icon">&#128269;</span>
            <input type="text" id="searchInput" placeholder="Search employee..." onkeyup="pgPage=1;renderApprovals()">
        </div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="approvalTable">
            <thead><tr>
                <th class="rp-sortable" onclick="sortBy('employee')">Employee <span class="rp-sort-arrow" id="listSortArrow0" data-col="employee">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('department')">Department <span class="rp-sort-arrow" id="listSortArrow1" data-col="department">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('score')">Score <span class="rp-sort-arrow" id="listSortArrow2" data-col="score">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('level')">Current Level <span class="rp-sort-arrow" id="listSortArrow3" data-col="level">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('status')">Status <span class="rp-sort-arrow" id="listSortArrow4" data-col="status">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('days')">Days Left <span class="rp-sort-arrow" id="listSortArrow5" data-col="days">&#8597;</span></th>
                <th>Action</th>
            </tr></thead>
            <tbody id="approvalBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div class="rp-pagination">
        <div class="rp-pg-info" id="pgInfo">Showing 0-0 of 0</div>
        <div class="rp-pg-center" id="pgCenter"></div>
        <div class="rp-pg-rows" id="pgRowsInfo"></div>
    </div>
</div>

<!-- Approval Action Modal -->
<div class="modal-overlay" id="approvalModal" onclick="if(event.target===this)closeModal('approvalModal')" style="display:none">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Approval Action</h3>
            <button class="modal-close" onclick="closeModal('approvalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="actProbationId">

            <div id="actProfileCard" class="profile-card"></div>
            <div id="actLevelChain" class="level-chain"></div>

            <div class="accordion-section">
                <div class="accordion-header open" onclick="toggleAccordion(this)">
                    &#128100; Candidate Profile
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body open">
                    <h4 style="font-size:12px;color:var(--gray-500);margin:0 0 8px;text-transform:uppercase;letter-spacing:.5px">Personal Information</h4>
                    <div id="actPersonalInfo" class="candidate-info-grid"></div>
                    <hr class="section-divider">
                    <h4 style="font-size:12px;color:var(--gray-500);margin:0 0 8px;text-transform:uppercase;letter-spacing:.5px">Job &amp; Offer Details</h4>
                    <div id="actJobOfferInfo" class="candidate-info-grid"></div>
                </div>
            </div>

            <div class="accordion-section">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    &#128269; Screening Details
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body">
                    <div id="actScreeningInfo"></div>
                </div>
            </div>

            <div class="accordion-section">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    &#128172; Interview Rounds
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body">
                    <div id="actInterviewInfo"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128221; Appraisal Summary</h2></div>
                <div class="card-body" style="font-size:13px">
                    <div id="actSummary"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128202; Section Scores</h2></div>
                <div class="card-body">
                    <div id="actScoreCards" class="score-card-grid"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128220; Approval History</h2></div>
                <div class="card-body">
                    <div id="actHistory" class="approval-timeline"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Your Decision <span class="required">*</span></label>
                <div style="display:flex;gap:16px;margin-top:8px;flex-wrap:wrap">
                    <label class="decision-option" id="actApproveLabel" onclick="selectDecision('Approved')" style="border-color:var(--success);background:#e6f4ea">
                        <input type="radio" name="actDecision" value="Approved" checked>
                        <span style="font-weight:600;color:var(--success)">&#10004; Approve</span>
                    </label>
                    <label class="decision-option" id="actSendBackLabel" onclick="selectDecision('Send Back')">
                        <input type="radio" name="actDecision" value="Send Back">
                        <span style="font-weight:600;color:var(--warning)">&#8635; Send Back</span>
                    </label>
                    <label class="decision-option" id="actRejectLabel" onclick="selectDecision('Rejected')">
                        <input type="radio" name="actDecision" value="Rejected">
                        <span style="font-weight:600;color:var(--danger)">&#10008; Reject</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Comments <span class="required">*</span></label>
                <textarea id="actComments" class="form-control textarea-full" placeholder="Enter comments for this approval decision..."></textarea>
            </div>

            <div class="audit-warning mb-16">
                <span class="aw-icon">&#9888;</span>
                <div><strong>Audit Trail:</strong> All decisions are logged with timestamp and user identity. This action will be recorded in the employee's permanent lifecycle audit trail and cannot be undone.</div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px">
                <button class="btn btn-outline" onclick="closeModal('approvalModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitApproval()">Submit Decision</button>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
