<?php
$basePath    = '..';
$pageTitle   = 'Offer Letter';
$activePage  = 'offer_letter';
$breadcrumb  = 'Home / Recruitment / Offer Management / Offer Letter';
$pageStyles  = '
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-total{border-left-color:var(--primary)}
.cc-card.cc-total .cc-num{color:var(--primary)}
.cc-card.cc-draft{border-left-color:var(--gray-400)}
.cc-card.cc-draft .cc-num{color:var(--gray-600)}
.cc-card.cc-generated{border-left-color:#f59e0b}
.cc-card.cc-generated .cc-num{color:#f59e0b}
.cc-card.cc-sent{border-left-color:var(--success)}
.cc-card.cc-sent .cc-num{color:var(--success)}
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
.rp-table .rp-btn-text.rp-btn-view{border-color:var(--success);color:var(--success);background:rgba(34,197,94,.04)}
.rp-table .rp-btn-text.rp-btn-view:hover{background:var(--success);color:#fff}
.rp-table .rp-btn-text.rp-btn-warning{border-color:#f59e0b;color:#f59e0b;background:rgba(245,158,11,.04)}
.rp-table .rp-btn-text.rp-btn-warning:hover{background:#f59e0b;color:#fff}
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
.preview-wide{max-width:900px!important;width:90%!important;max-height:90vh!important;height:auto!important}
.preview-wide .modal-body{overflow-y:auto;flex:1;padding:20px 24px}
.preview-content-wrap{padding:40px 48px;border:1px solid var(--gray-200);border-radius:8px;min-height:600px;background:#fff;line-height:1.8;font-size:15px}
.preview-content-wrap h1{text-align:center;color:#1a73e8;margin-bottom:4px}
.preview-content-wrap h2{color:#1a73e8;border-bottom:2px solid #1a73e8;padding-bottom:6px;margin:20px 0 10px}
.preview-content-wrap h3{color:#3c4043;margin:16px 0 8px}
.preview-content-wrap hr{border:none;border-top:1px solid var(--gray-200);margin:16px 0}
.preview-content-wrap blockquote{border-left:4px solid #1a73e8;padding:10px 16px;background:#f1f3f4;margin:16px 0;color:#5f6368}
.preview-content-wrap table{width:100%;border-collapse:collapse;margin:10px 0;font-size:13px}
.preview-content-wrap th{background:#f5f5f5;border:1px solid #ddd;padding:6px 10px;text-align:left}
.preview-content-wrap td{border:1px solid #ddd;padding:6px 10px}
.preview-content-wrap tr:nth-child(even){background:#fafafa}
.preview-content-wrap tr:last-child{background:#f0f7ff;font-weight:600}
@media(max-width:900px){.rp-top h1{font-size:18px}.rp-search input{width:200px}}
@media(max-width:768px){
 .page-content{padding:16px}
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;margin-bottom:18px}
 .cc-card{padding:14px 14px;gap:10px}
 .cc-card .cc-num{font-size:22px}
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
$pageScripts = '
var lettersData = [];
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

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
        case 3: return (o.letter_status || "Not Generated").toLowerCase();
        case 4: return o.letter_generated_at || "";
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
    for (var i = 0; i <= 4; i++) {
        var el = document.getElementById("sortArrow" + i);
        if (!el) continue;
        if (i === sortCol) {
            el.textContent = sortDir === "asc" ? "\\u25B2" : "\\u25BC";
            el.parentNode.classList.add("rp-sorted");
        } else {
            el.textContent = "\\u2195";
            el.parentNode.classList.remove("rp-sorted");
        }
    }
}

function getFiltered() {
    var q = document.getElementById("searchLetter").value.trim().toLowerCase();
    return sortData(lettersData.filter(function(o) {
        var name = ((o.candidate_name || "") + " " + (o.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function renderPaginationBar(total, totalPages) {
    var c = document.getElementById("paginationLetter");
    if (totalPages <= 1) { c.innerHTML = ""; return; }
    var h = "<div class=\\"rp-pg-info\\">Page " + currentPage + " of " + totalPages + " &middot; " + total + " total</div>";
    h += "<div class=\\"rp-pg-center\\">";
    h += "<button class=\\"rp-pg-btn\\"" + (currentPage <= 1 ? " disabled" : "") + " onclick=\\"goToPage(1)\\">&laquo;</button>";
    h += "<button class=\\"rp-pg-btn\\"" + (currentPage <= 1 ? " disabled" : "") + " onclick=\\"goToPage(" + (currentPage - 1) + ")\\">&lsaquo;</button>";
    var s = Math.max(1, currentPage - 2), e = Math.min(totalPages, currentPage + 2);
    for (var p = s; p <= e; p++) {
        h += "<button class=\\"rp-pg-btn" + (p === currentPage ? " rp-active" : "") + "\\" onclick=\\"goToPage(" + p + ")\\">" + p + "</button>";
    }
    h += "<button class=\\"rp-pg-btn\\"" + (currentPage >= totalPages ? " disabled" : "") + " onclick=\\"goToPage(" + (currentPage + 1) + ")\\">&rsaquo;</button>";
    h += "<button class=\\"rp-pg-btn\\"" + (currentPage >= totalPages ? " disabled" : "") + " onclick=\\"goToPage(" + totalPages + ")\\">&raquo;</button>";
    h += "</div>";
    h += "<div class=\\"rp-pg-rows\\">Show <select onchange=\\"changePerPage(this.value)\\">";
    [10, 20, 50, 100].forEach(function(n) { h += "<option value=\\"" + n + "\\"" + (n === perPage ? " selected" : "") + ">" + n + "</option>"; });
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
    document.getElementById("searchLetterClear").style.display = document.getElementById("searchLetter").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}
function clearSearch() {
    document.getElementById("searchLetter").value = "";
    document.getElementById("searchLetterClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderStats() {
    var total = lettersData.length;
    var draft = 0, generated = 0, sent = 0;
    lettersData.forEach(function(o) {
        var ls = o.letter_status;
        if (ls === "Sent") sent++;
        else if (ls === "Generated") generated++;
        else draft++;
    });
    document.getElementById("ccTotal").textContent = total;
    document.getElementById("ccDraft").textContent = draft;
    document.getElementById("ccGenerated").textContent = generated;
    document.getElementById("ccSent").textContent = sent;
}

function renderMobileCards(pageData) {
    var c = document.getElementById("letterMobileList");
    if (!pageData.length) {
        c.innerHTML = "<div class=\\"rp-empty\\"><div class=\\"rp-empty-icon\\">\\uD83D\\uDCC4</div><h3>No letters found</h3><p>Approve a salary proposal first</p></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(o) {
        var idx = lettersData.indexOf(o);
        var ls = o.letter_status || "Not Generated";
        var lsClass = ls === "Sent" ? "badge badge-success" : ls === "Generated" ? "badge badge-in-progress" : "badge";
        var actions = "";
        if (ls === "Generated") {
            actions = "<a class=\\"btn btn-primary btn-sm\\" href=\\"generate_letter.php?offer_id=" + o.id + "\\">Edit</a>";
            actions += "<button class=\\"btn btn-outline btn-sm\\" onclick=\\"previewLetter(" + o.id + ")\">Preview</button>";
            actions += "<button class=\\"btn btn-success btn-sm\\" onclick=\\"openSendEmail(" + idx + ")\">Send</button>";
        } else if (ls === "Sent") {
            actions = "<button class=\\"btn btn-outline btn-sm\\" onclick=\\"previewLetter(" + o.id + ")\">Preview</button>";
        } else {
            actions = "<a class=\\"btn btn-primary btn-sm\\" href=\\"generate_letter.php?offer_id=" + o.id + "\\">Generate</a>";
        }
        h += "<div class=\\"rp-mob-card\\">";
        h += "<div class=\\"rp-mob-top\\"><div class=\\"rp-mob-title\\">" + (o.candidate_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
        h += "<div class=\\"rp-mob-badges\\"><span class=\\"" + lsClass + "\\">" + ls + "</span></div></div>";
        h += "<div class=\\"rp-mob-grid\\">";
        h += "<div class=\\"rp-mob-field\\"><label>Salary</label><span>" + (o.salary ? formatCurrency(o.salary, "\\u09F3") : "-") + "</span></div>";
        h += "<div class=\\"rp-mob-field\\"><label>Generated</label><span>" + (o.letter_generated_at || "-") + "</span></div>";
        h += "</div><div class=\\"rp-mob-footer\\">" + actions + "</div></div>";
    });
    c.innerHTML = h;
}

function renderTable() {
    var filtered = getFiltered();
    var tp = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > tp) currentPage = tp;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);
    var tbody = document.getElementById("letterTableBody");
    tbody.innerHTML = "";
    if (!pageData.length) {
        tbody.innerHTML = "<tr><td colspan=\\"6\\"><div class=\\"rp-empty\\"><div class=\\"rp-empty-icon\\">\\uD83D\\uDCC4</div><h3>No letters found</h3><p>Approve a salary proposal first</p></div></td></tr>";
    } else {
        pageData.forEach(function(o) {
            var idx = lettersData.indexOf(o);
            var ls = o.letter_status || "Not Generated";
            var lsClass = ls === "Sent" ? "badge badge-success" : ls === "Generated" ? "badge badge-in-progress" : "badge";
            var actions = "";
            if (ls === "Generated") {
                actions = "<a class=\\"rp-btn-text rp-btn-primary\\" href=\\"generate_letter.php?offer_id=" + o.id + "\\">Edit</a>";
                actions += " <button class=\\"rp-btn-text rp-btn-view\\" onclick=\\"previewLetter(" + o.id + ")\">Preview</button>";
                actions += " <button class=\\"rp-btn-text rp-btn-warning\\" onclick=\\"openSendEmail(" + idx + ")\">Send</button>";
            } else if (ls === "Sent") {
                actions = "<button class=\\"rp-btn-text rp-btn-view\\" onclick=\\"previewLetter(" + o.id + ")\">Preview</button>";
            } else {
                actions = "<a class=\\"rp-btn-text rp-btn-primary\\" href=\\"generate_letter.php?offer_id=" + o.id + "\\">Generate</a>";
            }
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (o.candidate_name || "") + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\\u09F3") : "-") + "</td>" +
                "<td><span class=\\"" + lsClass + "\\">" + ls + "</span></td>" +
                "<td>" + (o.letter_generated_at || "-") + "</td>" +
                "<td><div class=\\"rp-actions-cell\\">" + actions + "</div></td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, tp);
    updateSortArrows();
}

function openSendEmail(idx) {
    var o = lettersData[idx];
    if (!o) return;
    document.getElementById("sendOfferId").value = o.id;
    document.getElementById("sendCandidateName").value = o.candidate_name || "";
    document.getElementById("sendEmail").value = o.email || "";
    document.getElementById("sendSubject").value = "Offer Letter - " + (o.job_title || "Position");
    document.getElementById("sendBody").value = "";
    openModal("sendEmailModal");
}

function submitSendEmail() {
    var email = document.getElementById("sendEmail").value;
    if (!email) { showAlert("Please enter recipient email", "error"); return; }
    apiPost("ajax/letter_send.php", {
        offer_id: document.getElementById("sendOfferId").value,
        recipient: email,
        subject: document.getElementById("sendSubject").value,
        body: document.getElementById("sendBody").value
    }, function(res) {
        showAlert(res.message || "Email sent!", "success");
        closeModal("sendEmailModal");
        renderPage();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to send email", "error");
    });
}

function previewLetter(offerId) {
    apiGet("ajax/letter_view.php?offer_id=" + offerId, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("previewContent").innerHTML = res.data.letter_content || "<p>No content</p>";
            openModal("previewLetterModal");
        } else {
            showAlert("Could not load letter preview", "error");
        }
    });
}

function renderPage() {
    apiGet("ajax/list_offers.php?status=Approved", null, function(res) {
        lettersData = res.success && res.data ? res.data : [];
        renderStats();
        renderTable();
    }, function() {
        lettersData = [];
        renderStats();
        renderTable();
    });
}

renderPage();
';
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-total"><div><div class="cc-num" id="ccTotal">0</div><div class="cc-label">Total Approved</div></div></div>
    <div class="cc-card cc-draft"><div><div class="cc-num" id="ccDraft">0</div><div class="cc-label">Not Generated</div></div></div>
    <div class="cc-card cc-generated"><div><div class="cc-num" id="ccGenerated">0</div><div class="cc-label">Generated</div></div></div>
    <div class="cc-card cc-sent"><div><div class="cc-num" id="ccSent">0</div><div class="cc-label">Sent</div></div></div>
</div>

<div class="rp-top">
    <h1>Offer Letters</h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchLetter" placeholder="Search by candidate or job..." oninput="applyFilter()" autocomplete="off">
            <button class="rp-search-clear" id="searchLetterClear" onclick="clearSearch()">&times;</button>
        </div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table">
            <thead>
                <tr>
                    <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>
                    <th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="sortArrow1"></span></th>
                    <th class="rp-sortable" onclick="sortBy(2)">Salary <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Letter Status <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Generated <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="letterTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="letterMobileList"></div>
    <div id="paginationLetter" class="rp-pagination"></div>
</div>

<!-- Send Email Modal -->
<div class="modal-overlay" id="sendEmailModal" onclick="if(event.target===this)closeModal('sendEmailModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Send Offer Letter via Email</h3>
            <button class="modal-close" onclick="closeModal('sendEmailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="sendEmailForm">
                <input type="hidden" id="sendOfferId">
                <div class="form-row-2">
                    <div class="form-group"><label>Candidate</label><input type="text" class="form-control" id="sendCandidateName" disabled></div>
                    <div class="form-group"><label>Recipient Email <span class="required">*</span></label><input type="email" class="form-control" id="sendEmail" placeholder="recipient@example.com" required></div>
                </div>
                <div class="form-group"><label>Subject</label><input type="text" class="form-control" id="sendSubject" placeholder="Email subject"></div>
                <div class="form-group"><label>Additional Message</label><textarea class="form-control" id="sendBody" rows="4" placeholder="Optional message..."></textarea></div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('sendEmailModal')">Cancel</button>
            <button class="btn btn-success" onclick="submitSendEmail()">Send Email</button>
        </div>
    </div>
</div>

<!-- Preview Letter Modal -->
<div class="modal-overlay" id="previewLetterModal" onclick="if(event.target===this)closeModal('previewLetterModal')">
    <div class="modal modal-sticky preview-wide">
        <div class="modal-header modal-header-navy">
            <h3>Preview Offer Letter</h3>
            <button class="modal-close" onclick="closeModal('previewLetterModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="previewContent" class="preview-content-wrap"></div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('previewLetterModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
