<?php
$basePath    = '..';
$pageTitle   = 'Proposal Approval';
$activePage  = 'offer_proposal_approval';
$breadcrumb  = 'Home / Recruitment / Offer Management / Proposal Approval';
$pageStyles  = '
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-pending{border-left-color:#f59e0b}
.cc-card.cc-pending .cc-num{color:#f59e0b}
.cc-card.cc-approved{border-left-color:var(--success)}
.cc-card.cc-approved .cc-num{color:var(--success)}
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
.rp-table .rp-btn-text.rp-btn-view{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-view:hover{background:var(--primary);color:#fff}
.rp-table .rp-btn-text.rp-btn-primary{border-color:var(--success);color:var(--success);background:rgba(34,197,94,.04)}
.rp-table .rp-btn-text.rp-btn-primary:hover{background:var(--success);color:#fff}
.rp-table .rp-btn-text.rp-btn-danger{border-color:var(--danger);color:var(--danger);background:rgba(239,68,68,.04)}
.rp-table .rp-btn-text.rp-btn-danger:hover{background:var(--danger);color:#fff}
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
.vc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.vc-item{margin-bottom:8px}
.vc-item label{display:block;font-size:11px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px}
.vc-item span{font-size:14px;color:var(--gray-800);font-weight:500}
.vc-section{margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--gray-100)}
.vc-section:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
.vc-section-title{font-size:13px;font-weight:700;color:var(--gray-700);margin-bottom:12px}
@media(max-width:900px){
 .rp-top h1{font-size:18px}
 .rp-search input{width:200px}
}
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
 .vc-grid{grid-template-columns:1fr 1fr}
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
 .vc-grid{grid-template-columns:1fr}
}
';
$pageScripts = '
var approvalsData = [];
var currentTab = "Pending_Approval";
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".scr-tab").forEach(function(b) { b.classList.remove("active"); });
    document.getElementById("tabBtn" + tab).classList.add("active");
    var titles = {Pending_Approval: "Pending Approvals", Finalized: "Approved Proposals", Rejected: "Rejected Proposals"};
    document.getElementById("tableTitle").textContent = titles[tab] || tab;
    sortCol = -1;
    currentPage = 1;
    document.getElementById("searchApproval").value = "";
    document.getElementById("searchApprovalClear").style.display = "none";
    fetchApprovals();
}

function sortBy(col) {
    if (sortCol === col) { sortDir = sortDir === "asc" ? "desc" : "asc"; }
    else { sortCol = col; sortDir = "asc"; }
    currentPage = 1;
    renderTable();
}

function getSortVal(p) {
    switch (sortCol) {
        case 0: return (p.candidate_name || "").toLowerCase();
        case 1: return (p.job_title || "").toLowerCase();
        case 2: return p.proposed_salary || 0;
        case 3: return (p.proposal_status || "").toLowerCase();
        case 4: return p.version || 0;
        case 5: return p.submitted_at || "";
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
    for (var i = 0; i <= 5; i++) {
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
    var q = document.getElementById("searchApproval").value.trim().toLowerCase();
    return sortData(approvalsData.filter(function(p) {
        var name = ((p.candidate_name || "") + " " + (p.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function renderPaginationBar(total, totalPages) {
    var c = document.getElementById("paginationApproval");
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
    [10, 20, 50, 100].forEach(function(n) {
        h += "<option value=\\"" + n + "\\"" + (n === perPage ? " selected" : "") + ">" + n + "</option>";
    });
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
    document.getElementById("searchApprovalClear").style.display = document.getElementById("searchApproval").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}
function clearSearch() {
    document.getElementById("searchApproval").value = "";
    document.getElementById("searchApprovalClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function loadStats() {
    var done = 0, total = 3;
    function check() { done++; }
    apiGet("ajax/list_approvals.php?status=Pending_Approval", null, function(r) {
        var n = (r.success && r.data) ? r.data.length : 0;
        document.getElementById("ccPending").textContent = n;
        document.getElementById("tabCountPending").textContent = n;
        check();
    }, check);
    apiGet("ajax/list_approvals.php?status=Finalized", null, function(r) {
        var n = (r.success && r.data) ? r.data.length : 0;
        document.getElementById("ccApproved").textContent = n;
        document.getElementById("tabCountApproved").textContent = n;
        check();
    }, check);
    apiGet("ajax/list_approvals.php?status=Rejected", null, function(r) {
        var n = (r.success && r.data) ? r.data.length : 0;
        document.getElementById("ccRejected").textContent = n;
        document.getElementById("tabCountRejected").textContent = n;
        check();
    }, check);
}

function renderMobileCards(pageData) {
    var c = document.getElementById("approvalMobileList");
    if (!pageData.length) {
        c.innerHTML = "<div class=\\"rp-empty\\"><div class=\\"rp-empty-icon\\">\\u23F3</div><h3>No proposals found</h3></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(p) {
        var idx = approvalsData.indexOf(p);
        var status = p.proposal_status === "Pending_Approval" ? "Pending" : p.proposal_status === "Finalized" ? "Approved" : "Rejected";
        var sc = p.proposal_status === "Finalized" ? "badge badge-success" : p.proposal_status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
        var actions = "<button class=\\"btn btn-outline btn-sm\\" onclick=\\"openView(" + idx + ")\">View</button>";
        if (p.proposal_status === "Pending_Approval") {
            actions += "<button class=\\"btn btn-success btn-sm\\" onclick=\\"openApprove(" + idx + ")\">Approve</button>";
            actions += "<button class=\\"btn btn-danger btn-sm\\" onclick=\\"openReject(" + idx + ")\">Reject</button>";
        }
        h += "<div class=\\"rp-mob-card\\">";
        h += "<div class=\\"rp-mob-top\\"><div class=\\"rp-mob-title\\">" + p.candidate_name + "<small>" + (p.job_title || "N/A") + " &middot; v" + p.version + "</small></div>";
        h += "<div class=\\"rp-mob-badges\\"><span class=\\"" + sc + "\\">" + status + "</span></div></div>";
        h += "<div class=\\"rp-mob-grid\\">";
        h += "<div class=\\"rp-mob-field\\"><label>Proposed</label><span>" + formatCurrency(p.proposed_salary, "\\u09F3") + "</span></div>";
        h += "<div class=\\"rp-mob-field\\"><label>Expected</label><span>" + (p.expected_salary ? formatCurrency(p.expected_salary, "\\u09F3") : "-") + "</span></div>";
        h += "<div class=\\"rp-mob-field\\"><label>Submitted</label><span>" + (p.submitted_at || "-") + "</span></div>";
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
    var tbody = document.getElementById("approvalTableBody");
    tbody.innerHTML = "";
    if (!pageData.length) {
        tbody.innerHTML = "<tr><td colspan=\\"6\\"><div class=\\"rp-empty\\"><div class=\\"rp-empty-icon\\">\\u23F3</div><h3>No proposals found</h3></div></td></tr>";
    } else {
        pageData.forEach(function(p) {
            var idx = approvalsData.indexOf(p);
            var status = p.proposal_status === "Pending_Approval" ? "Pending" : p.proposal_status === "Finalized" ? "Approved" : "Rejected";
            var sc = p.proposal_status === "Finalized" ? "badge badge-success" : p.proposal_status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
            var actions = "<button class=\\"rp-btn-text rp-btn-view\\" onclick=\\"openView(" + idx + ")\">View</button>";
            if (p.proposal_status === "Pending_Approval") {
                actions += " <button class=\\"rp-btn-text rp-btn-primary\\" onclick=\\"openApprove(" + idx + ")\">Approve</button>";
                actions += " <button class=\\"rp-btn-text rp-btn-danger\\" onclick=\\"openReject(" + idx + ")\">Reject</button>";
            }
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + p.candidate_name + "</strong></td>" +
                "<td>" + (p.job_title || "N/A") + "</td>" +
                "<td>" + formatCurrency(p.proposed_salary, "\\u09F3") + "</td>" +
                "<td><span class=\\"" + sc + "\\">" + status + "</span></td>" +
                "<td>v" + p.version + "</td>" +
                "<td><div class=\\"rp-actions-cell\\">" + actions + "</div></td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, tp);
    updateSortArrows();
}

function openApprove(idx) {
    var p = approvalsData[idx];
    if (!p) return;
    document.getElementById("apprProposalId").value = p.proposal_id;
    document.getElementById("apprCandidateName").value = p.candidate_name;
    document.getElementById("apprJobTitle").value = p.job_title;
    document.getElementById("apprProposedSalary").value = formatCurrency(p.proposed_salary, "\\u09F3");
    document.getElementById("apprJoiningDate").value = "";
    document.getElementById("apprProbation").value = 6;
    document.getElementById("apprNotes").value = "";
    var desSelect = document.getElementById("apprDesignation");
    desSelect.innerHTML = "<option value=\\"\\">-- Select --</option>";
    var deptId = p.dept_id || 0;
    if (deptId > 0) {
        apiGet("ajax/designations.php?dept_id=" + deptId, null, function(dres) {
            if (dres.success && dres.data) {
                dres.data.forEach(function(d) {
                    var opt = document.createElement("option");
                    opt.value = d.id; opt.textContent = d.name;
                    desSelect.appendChild(opt);
                });
            }
        });
    }
    openModal("approveProposalModal");
}

function submitApprove() {
    var proposalId = document.getElementById("apprProposalId").value;
    var joiningDate = document.getElementById("apprJoiningDate").value;
    if (!joiningDate) { showAlert("Please select a joining date", "error"); return; }
    apiPost("ajax/proposal_approve.php", {
        proposal_id: proposalId, action: "Approved",
        joining_date: joiningDate,
        designation_id: document.getElementById("apprDesignation").value,
        probation_months: document.getElementById("apprProbation").value,
        notes: document.getElementById("apprNotes").value
    }, function(res) {
        showAlert(res.message || "Proposal approved!", "success");
        closeModal("approveProposalModal");
        loadStats(); fetchApprovals();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to approve", "error");
    });
}

function openReject(idx) {
    var p = approvalsData[idx];
    if (!p) return;
    document.getElementById("rejProposalId").value = p.proposal_id;
    document.getElementById("rejCandidateName").value = p.candidate_name;
    document.getElementById("rejComments").value = "";
    openModal("rejectProposalModal");
}

function submitReject() {
    apiPost("ajax/proposal_approve.php", {
        proposal_id: document.getElementById("rejProposalId").value,
        action: "Rejected",
        comments: document.getElementById("rejComments").value
    }, function(res) {
        showAlert(res.message || "Proposal rejected", "info");
        closeModal("rejectProposalModal");
        loadStats(); fetchApprovals();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to reject", "error");
    });
}

function openView(idx) {
    var p = approvalsData[idx];
    if (!p) return;
    document.getElementById("vwCandidate").textContent = p.candidate_name;
    document.getElementById("vwJobTitle").textContent = p.job_title;
    document.getElementById("vwExpectedSalary").textContent = p.expected_salary ? formatCurrency(p.expected_salary, "\\u09F3") : "-";
    document.getElementById("vwProposedSalary").textContent = formatCurrency(p.proposed_salary, "\\u09F3");
    document.getElementById("vwVersion").textContent = "v" + p.version;
    document.getElementById("vwStatus").textContent = p.proposal_status === "Pending_Approval" ? "Pending" : p.proposal_status === "Finalized" ? "Approved" : "Rejected";
    document.getElementById("vwSubmitted").textContent = p.submitted_at || "-";
    document.getElementById("vwFinalized").textContent = p.finalized_at || "-";
    document.getElementById("vwNegotiationNotes").textContent = p.negotiation_notes || "None";
    var bd = p.salary_breakdown;
    var hasBd = bd && bd.gross_salary;
    document.getElementById("vwBreakdownSection").style.display = hasBd ? "" : "none";
    if (hasBd) {
        document.getElementById("vwGross").textContent = formatCurrency(bd.gross_salary, "\\u09F3");
        document.getElementById("vwBasic").textContent = formatCurrency(bd.basic, "\\u09F3");
        document.getElementById("vwHouseRent").textContent = formatCurrency(bd.house_rent, "\\u09F3");
        document.getElementById("vwMedical").textContent = formatCurrency(bd.medical_allowance, "\\u09F3");
        document.getElementById("vwConveyance").textContent = formatCurrency(bd.conveyance_allowance, "\\u09F3");
        document.getElementById("vwTotalSalary").textContent = formatCurrency(bd.total_salary, "\\u09F3");
        document.getElementById("vwPersonalPf").textContent = formatCurrency(bd.personal_pf || 0, "\\u09F3");
        document.getElementById("vwCompanyPf").textContent = formatCurrency(bd.company_pf || 0, "\\u09F3");
        document.getElementById("vwPfDeductible").textContent = formatCurrency(bd.pf_deductible || 0, "\\u09F3");
        document.getElementById("vwMonthlyTax").textContent = formatCurrency(bd.monthly_income_tax || 0, "\\u09F3");
        document.getElementById("vwTotalPayable").textContent = formatCurrency(bd.total_payable, "\\u09F3");
        document.getElementById("vwGender").textContent = bd.gender === "female" ? "Female" : "Male";
        document.getElementById("vwPfApplicable").textContent = bd.pf_applicable ? "Yes" : "No";
        document.getElementById("vwOtOut").textContent = bd.ot_applicable_out ? "Yes" : "No";
        document.getElementById("vwOtHoliday").textContent = bd.ot_holiday ? "Yes" : "No";
        document.getElementById("vwOtWeekend").textContent = bd.ot_weekend ? "Yes" : "No";
        document.getElementById("vwBankName").textContent = bd.bank_name || "-";
        document.getElementById("vwAccountNo").textContent = bd.account_no || "-";
        document.getElementById("vwBankCode").textContent = bd.receiving_bank_code || "-";
        document.getElementById("vwReceivingBank").textContent = bd.receiving_bank_name || "-";
        document.getElementById("vwSalaryGivenBy").textContent = bd.salary_given_by || "-";
        document.getElementById("vwCashPaid").textContent = bd.cash_paid ? formatCurrency(bd.cash_paid, "\\u09F3") : "-";
        document.getElementById("vwBankPaid").textContent = bd.bank_paid ? formatCurrency(bd.bank_paid, "\\u09F3") : "-";
    }
    openModal("viewProposalModal");
}

function fetchApprovals() {
    apiGet("ajax/list_approvals.php?status=" + currentTab, null, function(res) {
        approvalsData = res.success && res.data ? res.data : [];
        renderTable();
    }, function() {
        approvalsData = [];
        renderTable();
    });
}

document.addEventListener("DOMContentLoaded", function() {
    loadStats();
    fetchApprovals();
});
';
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Pending Approval</div></div></div>
    <div class="cc-card cc-approved"><div><div class="cc-num" id="ccApproved">0</div><div class="cc-label">Approved</div></div></div>
    <div class="cc-card cc-rejected"><div><div class="cc-num" id="ccRejected">0</div><div class="cc-label">Rejected</div></div></div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" id="tabBtnPending_Approval" onclick="switchTab('Pending_Approval')">Pending <span class="scr-tab-count" id="tabCountPending">0</span></button>
    <button class="scr-tab" id="tabBtnFinalized" onclick="switchTab('Finalized')">Approved <span class="scr-tab-count" id="tabCountApproved">0</span></button>
    <button class="scr-tab" id="tabBtnRejected" onclick="switchTab('Rejected')">Rejected <span class="scr-tab-count" id="tabCountRejected">0</span></button>
</div>

<div class="rp-top">
    <h1 id="tableTitle">Pending Approvals</h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchApproval" placeholder="Search by candidate or job..." oninput="applyFilter()" autocomplete="off">
            <button class="rp-search-clear" id="searchApprovalClear" onclick="clearSearch()">&times;</button>
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
                    <th class="rp-sortable" onclick="sortBy(2)">Proposed Salary <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Status <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Version <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="approvalTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="approvalMobileList"></div>
    <div id="paginationApproval" class="rp-pagination"></div>
</div>

<!-- Approve Modal -->
<div class="modal-overlay" id="approveProposalModal" onclick="if(event.target===this)closeModal('approveProposalModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Approve Salary Proposal</h3>
            <button class="modal-close" onclick="closeModal('approveProposalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="approveProposalForm">
                <input type="hidden" id="apprProposalId">
                <div class="form-row-2">
                    <div class="form-group"><label>Candidate</label><input type="text" class="form-control" id="apprCandidateName" disabled></div>
                    <div class="form-group"><label>Job Title</label><input type="text" class="form-control" id="apprJobTitle" disabled></div>
                </div>
                <div class="form-row-2">
                    <div class="form-group"><label>Proposed Salary</label><input type="text" class="form-control" id="apprProposedSalary" disabled></div>
                </div>
                <hr style="margin:12px 0;border-color:var(--gray-200)">
                <p style="font-size:13px;font-weight:600;margin:0 0 12px">Offer Details (auto-created on approval)</p>
                <div class="form-row-2">
                    <div class="form-group"><label>Joining Date <span class="required">*</span></label><input type="date" class="form-control" id="apprJoiningDate" required></div>
                    <div class="form-group"><label>Designation</label><select class="form-control" id="apprDesignation"></select></div>
                </div>
                <div class="form-row-2">
                    <div class="form-group"><label>Probation (months)</label><input type="number" class="form-control" id="apprProbation" value="6" min="1" max="12"></div>
                </div>
                <div class="form-group"><label>Notes</label><textarea class="form-control" id="apprNotes" rows="2" placeholder="Additional notes..."></textarea></div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('approveProposalModal')">Cancel</button>
            <button class="btn btn-success" onclick="submitApprove()">Approve &amp; Create Offer</button>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal-overlay" id="rejectProposalModal" onclick="if(event.target===this)closeModal('rejectProposalModal')">
    <div class="modal modal-small">
        <div class="modal-header modal-header-danger">
            <h3>Reject Salary Proposal</h3>
            <button class="modal-close" onclick="closeModal('rejectProposalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="rejectProposalForm">
                <input type="hidden" id="rejProposalId">
                <div class="form-group"><label>Candidate</label><input type="text" class="form-control" id="rejCandidateName" disabled></div>
                <div class="form-group"><label>Comments</label><textarea class="form-control" id="rejComments" rows="3" placeholder="Reason for rejection..."></textarea></div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('rejectProposalModal')">Cancel</button>
            <button class="btn btn-danger" onclick="submitReject()">Reject Proposal</button>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal-overlay" id="viewProposalModal" onclick="if(event.target===this)closeModal('viewProposalModal')">
    <div class="modal modal-sticky" style="max-width:780px;width:95%">
        <div class="modal-header modal-header-navy">
            <h3>Proposal Details</h3>
            <button class="modal-close" onclick="closeModal('viewProposalModal')">&times;</button>
        </div>
        <div class="modal-body" style="padding:24px">
            <div class="vc-section">
                <div class="vc-section-title">Candidate Information</div>
                <div class="vc-grid">
                    <div class="vc-item"><label>Candidate</label><span id="vwCandidate"></span></div>
                    <div class="vc-item"><label>Job Title</label><span id="vwJobTitle"></span></div>
                    <div class="vc-item"><label>Expected Salary</label><span id="vwExpectedSalary"></span></div>
                    <div class="vc-item"><label>Gender</label><span id="vwGender"></span></div>
                </div>
            </div>
            <div class="vc-section">
                <div class="vc-section-title">Proposal Info</div>
                <div class="vc-grid">
                    <div class="vc-item"><label>Proposed Salary</label><span id="vwProposedSalary"></span></div>
                    <div class="vc-item"><label>Version</label><span id="vwVersion"></span></div>
                    <div class="vc-item"><label>Status</label><span id="vwStatus"></span></div>
                    <div class="vc-item"><label>Submitted</label><span id="vwSubmitted"></span></div>
                    <div class="vc-item"><label>Finalized</label><span id="vwFinalized"></span></div>
                </div>
            </div>
            <div class="vc-section" id="vwBreakdownSection">
                <div class="vc-section-title">Salary Breakdown</div>
                <div class="vc-grid">
                    <div class="vc-item"><label>Gross Salary</label><span id="vwGross" style="font-weight:600"></span></div>
                    <div class="vc-item"><label>Basic Salary</label><span id="vwBasic"></span></div>
                    <div class="vc-item"><label>House Rent</label><span id="vwHouseRent"></span></div>
                    <div class="vc-item"><label>Medical Allowance</label><span id="vwMedical"></span></div>
                    <div class="vc-item"><label>Conveyance</label><span id="vwConveyance"></span></div>
                    <div class="vc-item"><label>Total Salary</label><span id="vwTotalSalary" style="font-weight:600;color:var(--primary)"></span></div>
                </div>
                <div class="vc-section-title" style="margin-top:16px">Deductions</div>
                <div class="vc-grid">
                    <div class="vc-item"><label>Personal PF</label><span id="vwPersonalPf"></span></div>
                    <div class="vc-item"><label>Company PF</label><span id="vwCompanyPf"></span></div>
                    <div class="vc-item"><label>PF Deductible</label><span id="vwPfDeductible"></span></div>
                    <div class="vc-item"><label>Monthly Tax</label><span id="vwMonthlyTax"></span></div>
                    <div class="vc-item"><label>Total Payable</label><span id="vwTotalPayable" style="font-weight:700;color:var(--success);font-size:15px"></span></div>
                </div>
                <div class="vc-section-title" style="margin-top:16px">Overtime &amp; PF Settings</div>
                <div class="vc-grid">
                    <div class="vc-item"><label>PF Applicable</label><span id="vwPfApplicable"></span></div>
                    <div class="vc-item"><label>OT (Out)</label><span id="vwOtOut"></span></div>
                    <div class="vc-item"><label>OT Holiday</label><span id="vwOtHoliday"></span></div>
                    <div class="vc-item"><label>OT Weekend</label><span id="vwOtWeekend"></span></div>
                </div>
                <div class="vc-section-title" style="margin-top:16px">Bank &amp; Payment</div>
                <div class="vc-grid">
                    <div class="vc-item"><label>Bank Name</label><span id="vwBankName"></span></div>
                    <div class="vc-item"><label>Account No</label><span id="vwAccountNo"></span></div>
                    <div class="vc-item"><label>Bank Code</label><span id="vwBankCode"></span></div>
                    <div class="vc-item"><label>Receiving Bank</label><span id="vwReceivingBank"></span></div>
                    <div class="vc-item"><label>Salary By</label><span id="vwSalaryGivenBy"></span></div>
                    <div class="vc-item"><label>Cash Paid</label><span id="vwCashPaid"></span></div>
                    <div class="vc-item"><label>Bank Paid</label><span id="vwBankPaid"></span></div>
                </div>
            </div>
            <div class="vc-section">
                <div class="vc-section-title">Negotiation Notes</div>
                <p id="vwNegotiationNotes" style="font-size:13px;color:var(--gray-700);margin:0;padding:8px 12px;background:var(--gray-50);border-radius:6px"></p>
            </div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('viewProposalModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
