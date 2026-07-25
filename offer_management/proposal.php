<?php
$basePath    = '..';
$pageTitle   = 'Salary Proposal';
$activePage  = 'offer_proposal';
$breadcrumb  = 'Home / Recruitment / Offer Management / Salary Proposal';
$pageStyles  = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-proposals{border-left-color:var(--primary)}
.cc-card.cc-proposals .cc-num{color:var(--primary)}
.cc-card.cc-approvals{border-left-color:#f59e0b}
.cc-card.cc-approvals .cc-num{color:#f59e0b}
.cc-card.cc-letters{border-left-color:#7c3aed}
.cc-card.cc-letters .cc-num{color:#7c3aed}
.cc-card.cc-response{border-left-color:var(--success)}
.cc-card.cc-response .cc-num{color:var(--success)}
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
.rp-table .rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit;text-decoration:none;display:inline-flex;align-items:center}
.rp-table .rp-btn-text:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-primary:hover{background:var(--primary);color:#fff}
.rp-table .rp-btn-text.rp-btn-view{border-color:var(--success);color:var(--success);background:rgba(34,197,94,.04)}
.rp-table .rp-btn-text.rp-btn-view:hover{background:var(--success);color:#fff}
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
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:10px;margin-bottom:18px}
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
var proposalData = [];
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
        case 2: return c.expected_salary || 0;
        case 3: return c.proposed_amount || 0;
        case 4: return (c.proposal_status || "").toLowerCase();
        case 5: return c.version || 0;
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

function updateSortArrows() {
    for (var i = 0; i <= 5; i++) {
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

function getFiltered() {
    var q = document.getElementById("searchProposal").value.trim().toLowerCase();
    var list = proposalData.filter(function(c) {
        var name = ((c.first_name || "") + " " + (c.last_name || "") + " " + (c.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
    return sortData(list);
}

function renderPaginationBar(total, totalPages) {
    var container = document.getElementById("paginationProposal");
    if (totalPages <= 1) { container.innerHTML = ""; return; }
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
    container.innerHTML = html;
}

function goToPage(p) {
    var filtered = getFiltered();
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

function applyFilter() {
    document.getElementById("searchClear").style.display = document.getElementById("searchProposal").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}

function clearSearch() {
    document.getElementById("searchProposal").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderStats() {
    var pending = 0, approvals = 0, letters = 0, responses = 0;
    var done = 0;
    function checkDone() {
        done++;
        if (done === 4) {
            document.getElementById("ccProposals").textContent = pending;
            document.getElementById("ccApprovals").textContent = approvals;
            document.getElementById("ccLetters").textContent = letters;
            document.getElementById("ccResponse").textContent = responses;
        }
    }
    apiGet("ajax/list_proposals.php", null, function(res) {
        var list = res.success && res.data ? res.data : [];
        pending = list.filter(function(c) { return c.proposal_status !== "Finalized"; }).length;
        checkDone();
    }, function() { checkDone(); });
    apiGet("ajax/list_pending.php", null, function(res) {
        approvals = (res.success && res.data) ? res.data.length : 0;
        checkDone();
    }, function() { checkDone(); });
    apiGet("ajax/list_offers.php?status=Approved&with_letter=1", null, function(res) {
        var list = res.success && res.data ? res.data : [];
        letters = list.filter(function(o) { return o.letter_status !== "Sent" && o.letter_status !== "Generated"; }).length;
        checkDone();
    }, function() { checkDone(); });
    apiGet("ajax/list_offers.php?status=Approved&response_status=Pending", null, function(res) {
        responses = (res.success && res.data) ? res.data.length : 0;
        checkDone();
    }, function() { checkDone(); });
}

function renderMobileCards(pageData) {
    var container = document.getElementById("proposalMobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4B0;</div><h3>No candidates ready for salary proposal</h3><p>Candidates will appear here after selection</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(c) {
        var name = c.first_name + " " + c.last_name;
        var salary = c.expected_salary ? formatCurrency(c.expected_salary, "\\u09F3") : "-";
        var proposed = c.proposed_amount ? formatCurrency(c.proposed_amount, "\\u09F3") : "-";
        var status = c.proposal_status || "None";
        var statusClass = getStatusBadgeClass(status);
        var action = "";
        if (status === "None" || status === "Draft") {
            action = "<a class=\"btn btn-primary btn-sm\" href=\"salary_breakdown.php?candidate_id=" + c.id + "\">Propose Salary</a>";
        } else if (status === "Proposed") {
            action = "<button class=\"btn btn-outline btn-sm\" onclick=\"submitForApproval(" + c.id + ")\">Submit for Approval</button> <a class=\"btn btn-outline btn-sm\" href=\"salary_breakdown.php?candidate_id=" + c.id + "&proposal_id=" + (c.proposal_id || "") + "\">Revise</a>";
        } else if (status === "Pending_Approval") {
            action = "<span class=\"badge badge-in-progress\">Pending Approval</span>";
        } else if (status === "Finalized") {
            action = "<span class=\"badge badge-success\">Ready for Offer</span>";
        }
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + name + "<small>" + (c.job_title || "N/A") + "</small></div>";
        html += "<div class=\"rp-mob-badges\"><span class=\"badge " + statusClass + "\">" + status + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Expected</label><span>" + salary + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Proposed</label><span>" + proposed + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Version</label><span>" + (c.version ? "v" + c.version : "-") + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">" + action + "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function getStatusBadgeClass(status) {
    switch (status) {
        case "Finalized": return "badge-success";
        case "Pending_Approval": return "badge-in-progress";
        case "Proposed": return "badge-in-progress";
        case "Draft": return "badge";
        default: return "badge";
    }
}

function renderTable() {
    var filtered = getFiltered();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("proposalTableBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4B0;</div><h3>No candidates ready for salary proposal</h3><p>Candidates will appear here after selection</p></div></td></tr>";
    } else {
        pageData.forEach(function(c) {
            var name = c.first_name + " " + c.last_name;
            var salary = c.expected_salary ? formatCurrency(c.expected_salary, "\\u09F3") : "-";
            var proposed = c.proposed_amount ? formatCurrency(c.proposed_amount, "\\u09F3") : "-";
            var status = c.proposal_status || "None";
            var statusClass = getStatusBadgeClass(status);
            var action = "";
            if (status === "None" || status === "Draft") {
                action = "<a class=\"rp-btn-text rp-btn-primary\" href=\"salary_breakdown.php?candidate_id=" + c.id + "\">Propose</a>";
            } else if (status === "Proposed") {
                action = "<button class=\"rp-btn-text rp-btn-primary\" onclick=\"submitForApproval(" + c.id + ")\">Submit</button> <a class=\"rp-btn-text rp-btn-view\" href=\"salary_breakdown.php?candidate_id=" + c.id + "&proposal_id=" + (c.proposal_id || "") + "\">Revise</a>";
            } else if (status === "Pending_Approval") {
                action = "<span class=\"badge badge-in-progress\">Pending Approval</span>";
            } else if (status === "Finalized") {
                action = "<span class=\"badge badge-success\">Ready for Offer</span>";
            }
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td>" + salary + "</td>" +
                "<td>" + proposed + "</td>" +
                "<td><span class=\"badge " + statusClass + "\">" + status + "</span></td>" +
                "<td>" + (c.version ? "v" + c.version : "-") + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + action + "</div></td>";
            tbody.appendChild(tr);
        });
    }

    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, totalPages);
    updateSortArrows();
}

function submitForApproval(candidateId) {
    if (!confirm("Submit this proposal for approval?")) return;
    apiPost("ajax/proposal_submit.php", {
        candidate_id: candidateId
    }, function(res) {
        showAlert(res.message || "Proposal submitted for approval!", "success");
        renderPage();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to submit", "error");
    });
}

function renderPage() {
    apiGet("ajax/list_proposals.php", null, function(res) {
        proposalData = res.success && res.data ? res.data : [];
        renderStats();
        renderTable();
    }, function() {
        proposalData = [];
        renderStats();
        renderTable();
    });
}

renderPage();
';
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-proposals"><div><div class="cc-num" id="ccProposals">0</div><div class="cc-label">Pending Salary Proposals</div></div></div>
    <div class="cc-card cc-approvals"><div><div class="cc-num" id="ccApprovals">0</div><div class="cc-label">Pending Approvals</div></div></div>
    <div class="cc-card cc-letters"><div><div class="cc-num" id="ccLetters">0</div><div class="cc-label">Letters to Generate</div></div></div>
    <div class="cc-card cc-response"><div><div class="cc-num" id="ccResponse">0</div><div class="cc-label">Awaiting Response</div></div></div>
</div>

<div class="rp-top">
    <h1>Salary Proposals</h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchProposal" placeholder="Search by candidate or job..." oninput="applyFilter()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
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
                    <th class="rp-sortable" onclick="sortBy(2)">Expected Salary <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Proposed Amount <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Status <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th class="rp-sortable" onclick="sortBy(5)">Version <span class="rp-sort-arrow" id="sortArrow5"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="proposalTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="proposalMobileList"></div>
    <div id="paginationProposal" class="rp-pagination"></div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
