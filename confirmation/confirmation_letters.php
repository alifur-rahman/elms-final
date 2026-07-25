<?php
$basePath    = '..';
$pageTitle   = 'Confirmation Letters';
$activePage  = 'confirmation_letter';
$breadcrumb  = 'Home / Confirmation / Confirmation Letters';
$pageStyles  = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-total{border-left-color:var(--primary)}
.cc-card.cc-total .cc-num{color:var(--primary)}
.cc-card.cc-generated{border-left-color:#8b5cf6}
.cc-card.cc-generated .cc-num{color:#8b5cf6}
.cc-card.cc-approved{border-left-color:#f59e0b}
.cc-card.cc-approved .cc-num{color:#f59e0b}
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
.rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;flex:1}
.rp-btn-text:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-btn-text.rp-btn-primary{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-btn-text.rp-btn-primary:hover{background:var(--primary);color:#fff}
.rp-btn-text.rp-btn-success{border-color:var(--success);color:var(--success);background:rgba(16,185,129,.04)}
.rp-btn-text.rp-btn-success:hover{background:var(--success);color:#fff}
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
.badge-generated{background:#f0e6ff;color:#7c3aed}
.badge-approved{background:#fef7e0;color:#e37400}
.badge-sent{background:#e6f4ea;color:#188038}
.badge-rejected{background:#fce8e6;color:var(--danger)}

/* ===== PREVIEW MODAL ===== */
.preview-wide{max-width:900px!important;width:90%!important;max-height:90vh!important;height:auto!important}
.preview-wide .modal-body{overflow-y:auto;flex:1;padding:20px 24px}
.preview-content-wrap{padding:40px 48px;border:1px solid var(--gray-200);border-radius:8px;min-height:600px;background:#fff;line-height:1.8;font-size:15px}
.preview-content-wrap h1{text-align:center;color:#1a73e8;margin-bottom:4px}
.preview-content-wrap h2{color:#1a73e8;border-bottom:2px solid #1a73e8;padding-bottom:6px;margin:20px 0 10px}
.preview-content-wrap table{width:100%;border-collapse:collapse;margin:10px 0;font-size:13px}
.preview-content-wrap th{background:#f5f5f5;border:1px solid #ddd;padding:6px 10px;text-align:left}
.preview-content-wrap td{border:1px solid #ddd;padding:6px 10px}

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
 .rp-mob-footer .rp-actions-cell{width:100%}
 .rp-mob-footer .rp-btn-text{padding:9px 12px;font-size:12px;font-weight:600;min-height:38px}
 .rp-pagination{flex-direction:column;align-items:center;gap:8px}
 .rp-pg-center{flex-wrap:wrap;justify-content:center}
 .rp-pg-rows{width:100%;justify-content:center}
 .preview-wide{width:95%!important}
 .preview-content-wrap{padding:24px;min-height:400px;font-size:14px}
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
 .rp-mob-footer{flex-direction:column;gap:6px}
 .rp-mob-footer .rp-btn-text{padding:11px 14px;font-size:13px;min-height:42px;border-radius:10px}
 .scr-tabs{overflow-x:auto;-webkit-overflow-scrolling:touch;flex-wrap:nowrap}
 .scr-tab{padding:10px 14px;font-size:12px;white-space:nowrap}
 .preview-content-wrap{padding:16px;min-height:300px;font-size:13px;line-height:1.6}
 .preview-content-wrap h1{font-size:16px}
 .preview-content-wrap h2{font-size:14px}
}
';
$pageScripts = <<<'EOSCRIPT'
var allLetters = [];
var activeTab = "all";
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;

function loadData() {
    apiGet("ajax/list_letters.php", null, function(res) {
        allLetters = res.success && res.data ? res.data : [];
        updateCounts();
        renderView();
    });
}

function updateCounts() {
    var total = allLetters.length;
    var generated = 0, approved = 0, sent = 0;
    for (var i = 0; i < allLetters.length; i++) {
        var s = allLetters[i].status;
        if (s === "Generated") generated++;
        else if (s === "Approved") approved++;
        else if (s === "Sent") sent++;
    }
    document.getElementById("cntTotal").textContent = total;
    document.getElementById("cntGenerated").textContent = generated;
    document.getElementById("cntApproved").textContent = approved;
    document.getElementById("cntSent").textContent = sent;
    document.querySelectorAll(".scr-tab-count").forEach(function(el) {
        var tab = el.getAttribute("data-count");
        if (tab === "all") el.textContent = total;
        else if (tab === "Generated") el.textContent = generated;
        else if (tab === "Approved") el.textContent = approved;
        else if (tab === "Sent") el.textContent = sent;
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
    for (var i = 0; i < allLetters.length; i++) {
        var l = allLetters[i];
        if (activeTab !== "all" && l.status !== activeTab) continue;
        if (search) {
            var hay = ((l.employee_name || "") + " " + (l.employee_code || "") + " " + (l.dept_name || "") + " " + (l.job_title || "") + " " + (l.designation_name || "")).toLowerCase();
            if (hay.indexOf(search) === -1) continue;
        }
        list.push(l);
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

function getSortVal(l, col) {
    switch(col) {
        case "employee": return (l.employee_name || "").toLowerCase();
        case "job": return (l.job_title || "").toLowerCase();
        case "dept": return (l.dept_name || "").toLowerCase();
        case "type": return (l.employment_type || "").toLowerCase();
        case "salary": return l.salary || 0;
        case "status": return (l.status || "").toLowerCase();
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
    updateSortArrows(6, "list");
}

function renderTable(rows) {
    var tbody = document.getElementById("letterTableBody");
    if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="rp-empty"><div class="rp-empty-icon">&#128196;</div><h3>No letters found</h3><p>No confirmation letters match your current filters.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var l = rows[i];
        html += "<tr>" +
            "<td><strong>" + (l.employee_name || "N/A") + "</strong><br><small style='color:#888'>" + (l.employee_code || "") + "</small></td>" +
            "<td>" + (l.job_title || "N/A") + "</td>" +
            "<td>" + (l.dept_name || "N/A") + "</td>" +
            "<td>" + (l.employment_type || "Probationer") + "</td>" +
            "<td>" + (l.salary ? formatCurrency(l.salary, "\u09F3") : "-") + "</td>" +
            "<td>" + getStatusBadge(l.status) + "</td>" +
            "<td>" + getActionHtml(l) + "</td>" +
        "</tr>";
    }
    tbody.innerHTML = html;
}

function renderMobileCards(rows) {
    var container = document.getElementById("mobileList");
    if (rows.length === 0) {
        container.innerHTML = '<div class="rp-empty"><div class="rp-empty-icon">&#128196;</div><h3>No letters found</h3><p>No confirmation letters match your current filters.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var l = rows[i];
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + (l.employee_name || "N/A") + '<small>' + (l.employee_code || "") + '</small></div>' +
                '<div class="rp-mob-badges">' + getStatusBadge(l.status) + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Job Title</label><span>' + (l.job_title || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Department</label><span>' + (l.dept_name || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Type</label><span>' + (l.employment_type || "Probationer") + '</span></div>' +
                '<div class="rp-mob-field"><label>Salary</label><span>' + (l.salary ? formatCurrency(l.salary, "\u09F3") : "-") + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer">' + getActionHtml(l) + '</div>' +
        '</div>';
    }
    container.innerHTML = html;
}

function getStatusBadge(status) {
    if (status === "Generated") return '<span class="badge badge-generated">Generated</span>';
    if (status === "Approved") return '<span class="badge badge-approved">Approved</span>';
    if (status === "Sent") return '<span class="badge badge-sent">Sent</span>';
    if (status === "Rejected") return '<span class="badge badge-rejected">Rejected</span>';
    return '<span class="badge badge-generated">' + (status || "Unknown") + '</span>';
}

function getActionHtml(l) {
    var html = '<div class="rp-actions-cell">';
    if (l.status === "Generated") {
        html += '<button class="rp-btn-text" onclick="previewLetter(' + l.id + ')">Preview</button>';
        html += '<button class="rp-btn-text rp-btn-primary" onclick="approveLetter(' + l.id + ')">Approve</button>';
    } else if (l.status === "Approved") {
        html += '<button class="rp-btn-text" onclick="previewLetter(' + l.id + ')">Preview</button>';
        html += '<button class="rp-btn-text rp-btn-success" onclick="openSendEmail(' + l.id + ')">Send</button>';
    } else if (l.status === "Sent") {
        html += '<button class="rp-btn-text" onclick="previewLetter(' + l.id + ')">Preview</button>';
    }
    html += '</div>';
    return html;
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

function previewLetter(letterId) {
    apiGet("ajax/letter_view.php?letter_id=" + letterId, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("previewContent").innerHTML = res.data.letter_content || "<p>No content</p>";
            openModal("previewLetterModal");
        } else {
            showAlert("Could not load letter", "error");
        }
    });
}

function approveLetter(letterId) {
    if (!confirm("Approve this confirmation letter?")) return;
    apiPost("ajax/letter_approve.php", { letter_id: letterId, action: "approve" }, function(res) {
        showAlert(res.message || "Approved!", "success");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
    });
}

function openSendEmail(letterId) {
    var l = null;
    for (var i = 0; i < allLetters.length; i++) { if (allLetters[i].id === letterId) { l = allLetters[i]; break; } }
    if (!l) return;
    document.getElementById("sendLetterId").value = l.id;
    document.getElementById("sendCandidateName").value = l.employee_name || "";
    document.getElementById("sendEmail").value = l.email || l.sent_to_email || "";
    document.getElementById("sendSubject").value = "Confirmation Letter - " + (l.job_title || "Position");
    document.getElementById("sendBody").value = "";
    openModal("sendEmailModal");
}

function submitSendEmail() {
    var letterId = document.getElementById("sendLetterId").value;
    var email = document.getElementById("sendEmail").value;
    var subject = document.getElementById("sendSubject").value;
    var body = document.getElementById("sendBody").value;
    if (!email) { showAlert("Please enter recipient email", "error"); return; }
    apiPost("ajax/letter_send.php", {
        letter_id: letterId,
        recipient: email,
        subject: subject,
        body: body
    }, function(res) {
        showAlert(res.message || "Sent!", "success");
        closeModal("sendEmailModal");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to send", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() { loadData(); });
EOSCRIPT;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-total">
        <div class="cc-num" id="cntTotal">0</div>
        <div class="cc-label">Total Letters</div>
    </div>
    <div class="cc-card cc-generated">
        <div class="cc-num" id="cntGenerated">0</div>
        <div class="cc-label">Generated</div>
    </div>
    <div class="cc-card cc-approved">
        <div class="cc-num" id="cntApproved">0</div>
        <div class="cc-label">Approved</div>
    </div>
    <div class="cc-card cc-sent">
        <div class="cc-num" id="cntSent">0</div>
        <div class="cc-label">Sent</div>
    </div>
</div>

<div class="rp-top">
    <h1>Confirmation Letters <small>Manage and send confirmation letters</small></h1>
    <div class="rp-search">
        <span class="rp-search-icon">&#128269;</span>
        <input type="text" id="searchInput" placeholder="Search employee, department..." onkeyup="pgPage=1;renderView()">
    </div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" data-tab="all" onclick="switchTab('all')">All <span class="scr-tab-count" data-count="all">0</span></button>
    <button class="scr-tab" data-tab="Generated" onclick="switchTab('Generated')">Generated <span class="scr-tab-count" data-count="Generated">0</span></button>
    <button class="scr-tab" data-tab="Approved" onclick="switchTab('Approved')">Approved <span class="scr-tab-count" data-count="Approved">0</span></button>
    <button class="scr-tab" data-tab="Sent" onclick="switchTab('Sent')">Sent <span class="scr-tab-count" data-count="Sent">0</span></button>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table">
            <thead><tr>
                <th class="rp-sortable" onclick="sortBy('employee')">Employee <span class="rp-sort-arrow" id="listSortArrow0" data-col="employee">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('job')">Job Title <span class="rp-sort-arrow" id="listSortArrow1" data-col="job">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('dept')">Department <span class="rp-sort-arrow" id="listSortArrow2" data-col="dept">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('type')">Type <span class="rp-sort-arrow" id="listSortArrow3" data-col="type">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('salary')">Salary <span class="rp-sort-arrow" id="listSortArrow4" data-col="salary">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('status')">Status <span class="rp-sort-arrow" id="listSortArrow5" data-col="status">&#8597;</span></th>
                <th>Action</th>
            </tr></thead>
            <tbody id="letterTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div class="rp-pagination" id="pgBar">
        <div class="rp-pg-info" id="pgInfo">Showing 0-0 of 0</div>
        <div class="rp-pg-center" id="pgCenter"></div>
        <div class="rp-pg-rows" id="pgRowsInfo"></div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal-overlay" id="previewLetterModal" onclick="if(event.target===this)closeModal('previewLetterModal')" style="display:none">
    <div class="modal modal-sticky preview-wide">
        <div class="modal-header modal-header-navy">
            <h3>Preview Confirmation Letter</h3>
            <button class="modal-close" onclick="closeModal('previewLetterModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="previewContent" class="preview-content-wrap"></div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('previewLetterModal')">Close</button>
        </div>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal-overlay" id="sendEmailModal" onclick="if(event.target===this)closeModal('sendEmailModal')" style="display:none">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Send Confirmation Letter</h3>
            <button class="modal-close" onclick="closeModal('sendEmailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="sendEmailForm">
                <input type="hidden" id="sendLetterId">
                <div class="form-group">
                    <label>Employee</label>
                    <input type="text" class="form-control" id="sendCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Recipient Email <span class="required">*</span></label>
                    <input type="email" class="form-control" id="sendEmail" placeholder="recipient@example.com" required>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" class="form-control" id="sendSubject">
                </div>
                <div class="form-group">
                    <label>Additional Message</label>
                    <textarea class="form-control" id="sendBody" rows="4" placeholder="Optional message..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('sendEmailModal')">Cancel</button>
            <button type="button" class="btn btn-success" onclick="submitSendEmail()">Send Email</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
