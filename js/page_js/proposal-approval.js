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
    [10, 20, 50, 100].forEach(function(n) {
        h += "<option value=\"" + n + "\"" + (n === perPage ? " selected" : "") + ">" + n + "</option>";
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
        c.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">\\u23F3</div><h3>No proposals found</h3></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(p) {
        var idx = approvalsData.indexOf(p);
        var status = p.proposal_status === "Pending_Approval" ? "Pending" : p.proposal_status === "Finalized" ? "Approved" : "Rejected";
        var sc = p.proposal_status === "Finalized" ? "badge badge-success" : p.proposal_status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
        var actions = "<button class=\"btn btn-outline btn-sm\" onclick=\"openView(" + idx + ")\">View</button>";
        if (p.proposal_status === "Pending_Approval") {
            actions += "<button class=\"btn btn-success btn-sm\" onclick=\"openApprove(" + idx + ")\">Approve</button>";
            actions += "<button class=\"btn btn-danger btn-sm\" onclick=\"openReject(" + idx + ")\">Reject</button>";
        }
        h += "<div class=\"rp-mob-card\">";
        h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + p.candidate_name + "<small>" + (p.job_title || "N/A") + " &middot; v" + p.version + "</small></div>";
        h += "<div class=\"rp-mob-badges\"><span class=\"" + sc + "\">" + status + "</span></div></div>";
        h += "<div class=\"rp-mob-grid\">";
        h += "<div class=\"rp-mob-field\"><label>Proposed</label><span>" + formatCurrency(p.proposed_salary, "\\u09F3") + "</span></div>";
        h += "<div class=\"rp-mob-field\"><label>Expected</label><span>" + (p.expected_salary ? formatCurrency(p.expected_salary, "\\u09F3") : "-") + "</span></div>";
        h += "<div class=\"rp-mob-field\"><label>Submitted</label><span>" + (p.submitted_at || "-") + "</span></div>";
        h += "</div><div class=\"rp-mob-footer\">" + actions + "</div></div>";
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
        tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">\\u23F3</div><h3>No proposals found</h3></div></td></tr>";
    } else {
        pageData.forEach(function(p) {
            var idx = approvalsData.indexOf(p);
            var status = p.proposal_status === "Pending_Approval" ? "Pending" : p.proposal_status === "Finalized" ? "Approved" : "Rejected";
            var sc = p.proposal_status === "Finalized" ? "badge badge-success" : p.proposal_status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
            var actions = "<button class=\"rp-btn-text rp-btn-view\" onclick=\"openView(" + idx + ")\">View</button>";
            if (p.proposal_status === "Pending_Approval") {
                actions += " <button class=\"rp-btn-text rp-btn-primary\" onclick=\"openApprove(" + idx + ")\">Approve</button>";
                actions += " <button class=\"rp-btn-text rp-btn-danger\" onclick=\"openReject(" + idx + ")\">Reject</button>";
            }
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + p.candidate_name + "</strong></td>" +
                "<td>" + (p.job_title || "N/A") + "</td>" +
                "<td>" + formatCurrency(p.proposed_salary, "\\u09F3") + "</td>" +
                "<td><span class=\"" + sc + "\">" + status + "</span></td>" +
                "<td>v" + p.version + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + actions + "</div></td>";
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
    desSelect.innerHTML = "<option value=\"\">-- Select --</option>";
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