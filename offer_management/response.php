<?php
$basePath    = '..';
$pageTitle   = 'Candidate Response';
$activePage  = 'offer_response';
$breadcrumb  = 'Home / Recruitment / Offer Management / Candidate Response';
$pageStyles  = '
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-pending{border-left-color:#f59e0b}
.cc-card.cc-pending .cc-num{color:#f59e0b}
.cc-card.cc-accepted{border-left-color:var(--success)}
.cc-card.cc-accepted .cc-num{color:var(--success)}
.cc-card.cc-rejected{border-left-color:var(--danger)}
.cc-card.cc-rejected .cc-num{color:var(--danger)}
.scr-tabs{display:flex;gap:0;margin-bottom:20px;border-bottom:2px solid var(--gray-200)}
.scr-tab{padding:12px 28px;font-size:14px;font-weight:600;background:none;border:none;border-bottom:3px solid transparent;margin-bottom:-2px;cursor:pointer;color:var(--gray-500);transition:all .2s;font-family:inherit;display:flex;align-items:center;gap:8px}
.scr-tab.active{color:var(--primary);border-bottom-color:var(--primary)}
.scr-tab:hover{color:var(--primary)}
.scr-tab .scr-tab-count{background:var(--gray-100);color:var(--gray-600);font-size:12px;font-weight:600;padding:2px 8px;border-radius:10px;min-width:24px;text-align:center}
.scr-tab.active .scr-tab-count{background:rgba(37,99,235,.1);color:var(--primary)}
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.rp-top h1{font-size:20px;font-weight:700;color:var(--gray-900);margin:0;white-space:nowrap}
.rp-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.rp-search{position:relative}
.rp-search input{width:260px;padding:9px 12px 9px 36px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit}
.rp-search input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.rp-search .rp-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:14px;pointer-events:none}
.rp-search .rp-search-clear{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:16px;display:none;padding:2px;line-height:1}
.rp-search .rp-search-clear:hover{color:var(--gray-700)}
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
.rp-pagination{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-top:1px solid var(--gray-100);flex-wrap:wrap;gap:10px}
.rp-pg-info{font-size:13px;color:var(--gray-500)}
.rp-pg-center{display:flex;align-items:center;gap:6px}
.rp-pg-btn{min-width:32px;height:32px;padding:0 10px;border:1px solid var(--gray-200);border-radius:6px;background:#fff;font-size:13px;cursor:pointer;color:var(--gray-600);transition:all .15s;font-family:inherit;display:inline-flex;align-items:center;justify-content:center}
.rp-pg-btn:hover:not(:disabled){border-color:var(--primary);color:var(--primary)}
.rp-pg-btn.rp-active{background:var(--primary);color:#fff;border-color:var(--primary)}
.rp-pg-btn:disabled{opacity:.35;cursor:default}
.rp-pg-rows{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--gray-500)}
.rp-pg-rows select{padding:5px 8px;border:1px solid var(--gray-200);border-radius:6px;font-size:13px;font-family:inherit;background:#fff;cursor:pointer}
.rp-mobile-list{display:none}
@media(max-width:900px){.rp-top h1{font-size:18px}.rp-search input{width:200px}}
@media(max-width:768px){
 .page-content{padding:16px}
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;margin-bottom:18px}
 .cc-card{padding:14px 14px;gap:10px}
 .cc-card .cc-num{font-size:22px}
 .scr-tab{padding:10px 16px;font-size:13px}
 .scr-tab .scr-tab-count{font-size:11px;padding:1px 6px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
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
 .cc-card .cc-label{font-size:10px}
 .rp-top h1{font-size:16px}
 .rp-mob-card{padding:14px;margin:6px 12px}
 .rp-mob-grid{grid-template-columns:1fr;gap:8px}
 .rp-mob-top{flex-direction:column}
 .rp-pagination{flex-direction:column;align-items:center;gap:8px}
 .rp-pg-center{flex-wrap:wrap;justify-content:center}
 .rp-pg-rows{width:100%;justify-content:center}
}
';
$pageScripts = <<<'EOSCRIPT'
var pendingData = [];
var respondedData = [];
var currentTab = "pending";
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".scr-tab").forEach(function(b) { b.classList.remove("active"); });
    document.getElementById("tabBtn" + tab).classList.add("active");
    var titles = {pending: "Awaiting Response", responded: "Responded Offers"};
    document.getElementById("tableTitle").textContent = titles[tab] || "";
    sortCol = -1;
    currentPage = 1;
    document.getElementById("searchResponse").value = "";
    document.getElementById("searchResponseClear").style.display = "none";
    buildHeaders();
    renderTable();
}

function buildHeaders() {
    var tr = document.getElementById("tableHeadRow");
    if (currentTab === "pending") {
        tr.innerHTML = '<th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="sortArrow1"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(2)">Offered Salary <span class="rp-sort-arrow" id="sortArrow2"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(3)">Letter Sent <span class="rp-sort-arrow" id="sortArrow3"></span></th>' +
            '<th>Status</th><th>Actions</th>';
    } else {
        tr.innerHTML = '<th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="sortArrow1"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(2)">Offered Salary <span class="rp-sort-arrow" id="sortArrow2"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(3)">Response <span class="rp-sort-arrow" id="sortArrow3"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(4)">Date <span class="rp-sort-arrow" id="sortArrow4"></span></th>' +
            '<th>Notes</th>';
    }
}

function sortBy(col) {
    if (sortCol === col) { sortDir = sortDir === "asc" ? "desc" : "asc"; }
    else { sortCol = col; sortDir = "asc"; }
    currentPage = 1;
    renderTable();
}

function getSortVal(o) {
    switch (sortCol) {
        case 0: return (o.candidate_name || "").toLowerCase();
        case 1: return (o.job_title || "").toLowerCase();
        case 2: return o.salary || 0;
        case 3: return currentTab === "pending" ? (o.letter_sent_at || "").toLowerCase() : (o.response_status || "").toLowerCase();
        case 4: return currentTab === "responded" ? (o.responded_at || "") : "";
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

function updateSortArrows() {
    var count = currentTab === "pending" ? 4 : 5;
    for (var i = 0; i < count; i++) {
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

function getActiveData() {
    return currentTab === "pending" ? pendingData : respondedData;
}

function getFiltered() {
    var q = document.getElementById("searchResponse").value.trim().toLowerCase();
    return sortData(getActiveData().filter(function(o) {
        var name = ((o.candidate_name || "") + " " + (o.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function renderPaginationBar(total, totalPages) {
    var c = document.getElementById("paginationResponse");
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
function applyFilter() {
    document.getElementById("searchResponseClear").style.display = document.getElementById("searchResponse").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}
function clearSearch() {
    document.getElementById("searchResponse").value = "";
    document.getElementById("searchResponseClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function loadStats() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=Pending&letter_status=Sent", null, function(r) {
        pendingData = (r.success && r.data) ? r.data : [];
        document.getElementById("ccPending").textContent = pendingData.length;
        document.getElementById("tabCountPending").textContent = pendingData.length;
        if (currentTab === "pending") renderTable();
    }, function() { pendingData = []; document.getElementById("ccPending").textContent = "0"; });
    apiGet("ajax/list_offers.php?status=Approved&response_status=Accepted,Rejected", null, function(r) {
        respondedData = (r.success && r.data) ? r.data : [];
        var accepted = 0, rejected = 0;
        respondedData.forEach(function(o) {
            if (o.response_status === "Accepted") accepted++;
            else rejected++;
        });
        document.getElementById("ccAccepted").textContent = accepted;
        document.getElementById("ccRejected").textContent = rejected;
        document.getElementById("tabCountResponded").textContent = respondedData.length;
        if (currentTab === "responded") renderTable();
    }, function() { respondedData = []; });
}

function renderMobileCards(pageData) {
    var c = document.getElementById("responseMobileList");
    if (!pageData.length) {
        c.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4EC;</div><h3>No offers found</h3></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(o) {
        var idx = getActiveData().indexOf(o);
        if (currentTab === "pending") {
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.candidate_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
            h += "<div class=\"rp-mob-badges\"><span class=\"badge badge-in-progress\">Pending</span></div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Salary</label><span>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Letter Sent</label><span>" + (o.letter_sent_at || "-") + "</span></div>";
            h += "</div><div class=\"rp-mob-footer\"><button class=\"btn btn-primary btn-sm\" onclick=\"openMarkResponse(" + idx + ")\">Record Response</button></div></div>";
        } else {
            var rsClass = o.response_status === "Accepted" ? "badge badge-success" : "badge badge-danger";
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.candidate_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
            h += "<div class=\"rp-mob-badges\"><span class=\"" + rsClass + "\">" + o.response_status + "</span></div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Salary</label><span>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Date</label><span>" + (o.responded_at || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\" style=\"grid-column:1/-1\"><label>Notes</label><span>" + (o.response_notes || "-") + "</span></div>";
            h += "</div></div>";
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
    var tbody = document.getElementById("responseTableBody");
    tbody.innerHTML = "";
    if (!pageData.length) {
        tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4EC;</div><h3>No offers found</h3></div></td></tr>";
    } else if (currentTab === "pending") {
        pageData.forEach(function(o) {
            var idx = pendingData.indexOf(o);
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (o.candidate_name || "") + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</td>" +
                "<td>" + (o.letter_sent_at || o.letter_generated_at || "-") + "</td>" +
                "<td><span class=\"badge badge-in-progress\">Pending</span></td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text rp-btn-primary\" onclick=\"openMarkResponse(" + idx + ")\">Record Response</button></div></td>";
            tbody.appendChild(tr);
        });
    } else {
        pageData.forEach(function(o) {
            var rsClass = o.response_status === "Accepted" ? "badge badge-success" : "badge badge-danger";
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (o.candidate_name || "") + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</td>" +
                "<td><span class=\"" + rsClass + "\">" + (o.response_status || "-") + "</span></td>" +
                "<td>" + (o.responded_at || "-") + "</td>" +
                "<td>" + (o.response_notes || "-") + "</td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, tp);
    updateSortArrows();
}

function openMarkResponse(idx) {
    var o = pendingData[idx];
    if (!o) return;
    document.getElementById("respOfferId").value = o.id;
    document.getElementById("respCandidateName").value = o.candidate_name || "";
    document.querySelectorAll("input[name='respStatus']").forEach(function(r) { r.checked = false; });
    document.getElementById("respNotes").value = "";
    openModal("markResponseModal");
}

function submitMarkResponse() {
    var status = document.querySelector("input[name='respStatus']:checked");
    if (!status) { showAlert("Please select a response status", "error"); return; }
    apiPost("ajax/respond.php", {
        offer_id: document.getElementById("respOfferId").value,
        response: status.value,
        notes: document.getElementById("respNotes").value
    }, function(res) {
        showAlert(res.message || "Response recorded!", "success");
        closeModal("markResponseModal");
        loadStats();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to record response", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    buildHeaders();
    loadStats();
});
EOSCRIPT;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Awaiting Response</div></div></div>
    <div class="cc-card cc-accepted"><div><div class="cc-num" id="ccAccepted">0</div><div class="cc-label">Accepted</div></div></div>
    <div class="cc-card cc-rejected"><div><div class="cc-num" id="ccRejected">0</div><div class="cc-label">Rejected</div></div></div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" id="tabBtnpending" onclick="switchTab('pending')">Pending <span class="scr-tab-count" id="tabCountPending">0</span></button>
    <button class="scr-tab" id="tabBtnresponded" onclick="switchTab('responded')">Responded <span class="scr-tab-count" id="tabCountResponded">0</span></button>
</div>

<div class="rp-top">
    <h1 id="tableTitle">Awaiting Response</h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchResponse" placeholder="Search by candidate or job..." oninput="applyFilter()" autocomplete="off">
            <button class="rp-search-clear" id="searchResponseClear" onclick="clearSearch()">&times;</button>
        </div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table">
            <thead><tr id="tableHeadRow"></tr></thead>
            <tbody id="responseTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="responseMobileList"></div>
    <div id="paginationResponse" class="rp-pagination"></div>
</div>

<!-- Mark Response Modal -->
<div class="modal-overlay" id="markResponseModal" onclick="if(event.target===this)closeModal('markResponseModal')">
    <div class="modal modal-small">
        <div class="modal-header modal-header-navy">
            <h3>Record Candidate Response</h3>
            <button class="modal-close" onclick="closeModal('markResponseModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="markResponseForm">
                <input type="hidden" id="respOfferId">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="respCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Response <span class="required">*</span></label>
                    <div style="display:flex;gap:16px;padding:8px 0">
                        <label style="display:flex;align-items:center;gap:6px">
                            <input type="radio" name="respStatus" value="Accepted"> Accepted
                        </label>
                        <label style="display:flex;align-items:center;gap:6px">
                            <input type="radio" name="respStatus" value="Rejected"> Rejected
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" id="respNotes" rows="3" placeholder="Any notes..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('markResponseModal')">Cancel</button>
            <button class="btn btn-primary" onclick="submitMarkResponse()">Save Response</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
