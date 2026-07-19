<?php
$basePath    = '..';
$pageTitle   = 'Proposal Approval';
$activePage  = 'offer_proposal_approval';
$breadcrumb  = 'Home / Recruitment / Offer Management / Proposal Approval';
$pageStyles  = '
.stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px }
.stat-tile { background:#fff; border-radius:8px; padding:14px 18px; display:flex; align-items:center; gap:14px; border:1px solid var(--gray-200); box-shadow:0 1px 3px rgba(0,0,0,.04) }
.stat-tile .stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0 }
.stat-tile .stat-body h3 { font-size:22px; font-weight:700; margin:0; line-height:1.2 }
.stat-tile .stat-body p { font-size:12px; color:var(--gray-500); margin:0 }
.tabs { display:flex; border-bottom:2px solid var(--gray-200); margin-bottom:0 }
.tab { padding:10px 22px; cursor:pointer; font-size:13px; font-weight:500; color:var(--gray-500); border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .2s; display:flex; align-items:center; gap:6px }
.tab.active { color:var(--primary); border-bottom-color:var(--primary) }
.tab .tab-badge { display:inline-block; background:var(--gray-200); color:var(--gray-600); font-size:11px; font-weight:600; padding:1px 8px; border-radius:10px; line-height:18px }
.tab.active .tab-badge { background:#e8f0fe; color:var(--primary) }
.approval-row { display:flex; align-items:center; padding:14px 20px; border-bottom:1px solid var(--gray-100); transition:background .15s }
.approval-row:hover { background:#fafbfc }
.approval-row:last-child { border-bottom:none }
.approval-row .ar-initials { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:15px; color:#fff; flex-shrink:0; margin-right:14px }
.approval-row .ar-info { flex:1; min-width:0 }
.approval-row .ar-info h4 { margin:0; font-size:14px; font-weight:600; color:var(--gray-800) }
.approval-row .ar-info p { margin:2px 0 0; font-size:12px; color:var(--gray-500) }
.approval-row .ar-salary { text-align:right; margin-right:20px; flex-shrink:0 }
.approval-row .ar-salary .amount { font-size:15px; font-weight:600; color:var(--gray-800) }
.approval-row .ar-salary .label { font-size:11px; color:var(--gray-400) }
.approval-row .ar-status { flex-shrink:0; margin-right:16px }
.approval-row .ar-actions { flex-shrink:0; display:flex; gap:6px }
.empty-state { text-align:center; padding:48px 24px; color:var(--gray-500) }
.empty-state .empty-icon { font-size:42px; margin-bottom:10px }
.empty-state p { font-size:13px; margin:0 }
.vc-item { margin-bottom:12px }
.vc-item label { display:block; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:.4px; margin-bottom:2px }
.vc-item span { font-size:14px; color:var(--gray-800); font-weight:500 }
';
$pageScripts = <<<'EOSCRIPT'
var approvalsData = [];
var currentTab = "Pending_Approval";

function loadStats() {
    apiGet("ajax/list_approvals.php?status=Pending_Approval", null, function(r1) {
        var pending = r1.success && r1.data ? r1.data : [];
        document.getElementById("statPending").textContent = pending.length;
    });
    apiGet("ajax/list_approvals.php?status=Finalized", null, function(r2) {
        var approved = r2.success && r2.data ? r2.data : [];
        document.getElementById("statApproved").textContent = approved.length;
    });
    apiGet("ajax/list_approvals.php?status=Rejected", null, function(r3) {
        var rejected = r3.success && r3.data ? r3.data : [];
        document.getElementById("statRejected").textContent = rejected.length;
    });
}

function renderApprovals() {
    var tabLabel = currentTab === "Pending_Approval" ? "Pending" : currentTab === "Finalized" ? "Approved" : "Rejected";
    apiGet("ajax/list_approvals.php?status=" + currentTab, null, function(res) {
        approvalsData = res.success && res.data ? res.data : [];
        var container = document.getElementById("approvalListContainer");
        if (approvalsData.length === 0) {
            container.innerHTML = "<div class=\"empty-state\"><div class=\"empty-icon\">" + (currentTab === "Pending_Approval" ? "✅" : currentTab === "Finalized" ? "📋" : "🗑️") + "</div><p>No " + tabLabel.toLowerCase() + " proposals.</p></div>";
            return;
        }
        var initialsColors = ["#2563eb","#7c3aed","#059669","#d97706","#dc2626","#0891b2","#4f46e5","#ca8a04"];
        var html = "";
        for (var i = 0; i < approvalsData.length; i++) {
            var p = approvalsData[i];
            var nameParts = p.candidate_name.split(" ");
            var initials = (nameParts[0] ? nameParts[0][0] : "") + (nameParts.length > 1 ? nameParts[1][0] : "");
            var colorIdx = i % initialsColors.length;
            var expected = p.expected_salary ? formatCurrency(p.expected_salary, "৳") : "-";
            var proposed = formatCurrency(p.proposed_salary, "৳");

            var statusHtml = "";
            var statusLabel = p.proposal_status === "Pending_Approval" ? "Pending" : p.proposal_status === "Finalized" ? "Approved" : "Rejected";
            var statusClass = p.proposal_status === "Finalized" ? "badge badge-success" : p.proposal_status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
            statusHtml = "<span class=\"" + statusClass + "\">" + statusLabel + "</span>";

            var actionsHtml = "<button class=\"btn btn-sm btn-outline\" onclick=\"openView(" + i + ")\">View</button>";
            if (p.proposal_status === "Pending_Approval") {
                actionsHtml += "<button class=\"btn btn-sm btn-success\" onclick=\"openApprove(" + i + ")\" style=\"min-width:80px\">Approve</button><button class=\"btn btn-sm btn-danger\" onclick=\"openReject(" + i + ")\" style=\"min-width:80px\">Reject</button>";
            }

            html += "<div class=\"approval-row\">" +
                "<div class=\"ar-initials\" style=\"background:" + initialsColors[colorIdx] + "\">" + initials + "</div>" +
                "<div class=\"ar-info\">" +
                    "<h4>" + p.candidate_name + "</h4>" +
                    "<p>" + p.job_title + " &middot; v" + p.version + " &middot; " + (p.submitted_at || "-") + "</p>" +
                "</div>" +
                "<div class=\"ar-salary\">" +
                    "<div class=\"amount\">" + proposed + "</div>" +
                    "<div class=\"label\">Expected: " + expected + "</div>" +
                "</div>" +
                "<div class=\"ar-status\">" + statusHtml + "</div>" +
                "<div class=\"ar-actions\">" + actionsHtml + "</div>" +
            "</div>";
        }
        container.innerHTML = html;
    });
}

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".tab").forEach(function(el) { el.classList.remove("active"); });
    document.getElementById("tab" + tab).classList.add("active");
    renderApprovals();
}

function openApprove(idx) {
    var p = approvalsData[idx];
    if (!p) return;
    document.getElementById("apprProposalId").value = p.proposal_id;
    document.getElementById("apprCandidateName").value = p.candidate_name;
    document.getElementById("apprJobTitle").value = p.job_title;
    document.getElementById("apprProposedSalary").value = formatCurrency(p.proposed_salary, "৳");
    document.getElementById("apprJoiningDate").value = "";
    document.getElementById("apprProbation").value = 6;
    document.getElementById("apprNotes").value = "";
    var deptId = p.dept_id || 0;
    var desSelect = document.getElementById("apprDesignation");
    desSelect.innerHTML = "<option value=\"\">-- Select --</option>";
    if (deptId > 0) {
        apiGet("ajax/designations.php?dept_id=" + deptId, null, function(dres) {
            if (dres.success && dres.data) {
                for (var j = 0; j < dres.data.length; j++) {
                    var opt = document.createElement("option");
                    opt.value = dres.data[j].id;
                    opt.textContent = dres.data[j].name;
                    desSelect.appendChild(opt);
                }
            }
        });
    }
    openModal("approveProposalModal");
}

function submitApprove() {
    var proposalId = document.getElementById("apprProposalId").value;
    var joiningDate = document.getElementById("apprJoiningDate").value;
    var designationId = document.getElementById("apprDesignation").value;
    var probation = document.getElementById("apprProbation").value;
    var notes = document.getElementById("apprNotes").value;
    if (!joiningDate) { showAlert("Please select a joining date", "error"); return; }
    apiPost("ajax/proposal_approve.php", {
        proposal_id: proposalId,
        action: "Approved",
        joining_date: joiningDate,
        designation_id: designationId,
        probation_months: probation,
        notes: notes
    }, function(res) {
        showAlert(res.message || "Proposal approved!", "success");
        closeModal("approveProposalModal");
        loadStats();
        renderApprovals();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to approve proposal", "error");
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
    var proposalId = document.getElementById("rejProposalId").value;
    var comments = document.getElementById("rejComments").value;
    apiPost("ajax/proposal_approve.php", {
        proposal_id: proposalId,
        action: "Rejected",
        comments: comments
    }, function(res) {
        showAlert(res.message || "Proposal rejected", "info");
        closeModal("rejectProposalModal");
        loadStats();
        renderApprovals();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to reject proposal", "error");
    });
}

function openView(idx) {
    var p = approvalsData[idx];
    if (!p) return;

    document.getElementById("vwCandidate").textContent = p.candidate_name;
    document.getElementById("vwJobTitle").textContent = p.job_title;
    document.getElementById("vwExpectedSalary").textContent = p.expected_salary ? formatCurrency(p.expected_salary, "\u09F3") : "-";
    document.getElementById("vwProposedSalary").textContent = formatCurrency(p.proposed_salary, "\u09F3");
    document.getElementById("vwVersion").textContent = "v" + p.version;
    document.getElementById("vwStatus").textContent = p.proposal_status === "Pending_Approval" ? "Pending" : p.proposal_status === "Finalized" ? "Approved" : "Rejected";
    document.getElementById("vwSubmitted").textContent = p.submitted_at || "-";
    document.getElementById("vwFinalized").textContent = p.finalized_at || "-";
    document.getElementById("vwNegotiationNotes").textContent = p.negotiation_notes || "None";

    var bd = p.salary_breakdown;
    var hasBd = bd && bd.gross_salary;
    document.getElementById("vwBreakdownSection").style.display = hasBd ? "" : "none";
    if (hasBd) {
        document.getElementById("vwGross").textContent = formatCurrency(bd.gross_salary, "\u09F3");
        document.getElementById("vwBasic").textContent = formatCurrency(bd.basic, "\u09F3");
        document.getElementById("vwHouseRent").textContent = formatCurrency(bd.house_rent, "\u09F3");
        document.getElementById("vwMedical").textContent = formatCurrency(bd.medical_allowance, "\u09F3");
        document.getElementById("vwConveyance").textContent = formatCurrency(bd.conveyance_allowance, "\u09F3");
        document.getElementById("vwTotalSalary").textContent = formatCurrency(bd.total_salary, "\u09F3");
        document.getElementById("vwPersonalPf").textContent = formatCurrency(bd.personal_pf || 0, "\u09F3");
        document.getElementById("vwCompanyPf").textContent = formatCurrency(bd.company_pf || 0, "\u09F3");
        document.getElementById("vwPfDeductible").textContent = formatCurrency(bd.pf_deductible || 0, "\u09F3");
        document.getElementById("vwMonthlyTax").textContent = formatCurrency(bd.monthly_income_tax || 0, "\u09F3");
        document.getElementById("vwTotalPayable").textContent = formatCurrency(bd.total_payable, "\u09F3");
        document.getElementById("vwGender").textContent = bd.gender === "female" ? "Female" : "Male";

        var pfApp = bd.pf_applicable ? "Yes" : "No";
        var otOut = bd.ot_applicable_out ? "Yes" : "No";
        var otHol = bd.ot_holiday ? "Yes" : "No";
        var otWk = bd.ot_weekend ? "Yes" : "No";
        document.getElementById("vwPfApplicable").textContent = pfApp;
        document.getElementById("vwOtOut").textContent = otOut;
        document.getElementById("vwOtHoliday").textContent = otHol;
        document.getElementById("vwOtWeekend").textContent = otWk;

        document.getElementById("vwBankName").textContent = bd.bank_name || "-";
        document.getElementById("vwAccountNo").textContent = bd.account_no || "-";
        document.getElementById("vwBankCode").textContent = bd.receiving_bank_code || "-";
        document.getElementById("vwReceivingBank").textContent = bd.receiving_bank_name || "-";
        document.getElementById("vwSalaryGivenBy").textContent = bd.salary_given_by || "-";
        document.getElementById("vwCashPaid").textContent = bd.cash_paid ? formatCurrency(bd.cash_paid, "\u09F3") : "-";
        document.getElementById("vwBankPaid").textContent = bd.bank_paid ? formatCurrency(bd.bank_paid, "\u09F3") : "-";
    }

    openModal("viewProposalModal");
}

document.addEventListener("DOMContentLoaded", function() {
    loadStats();
    renderApprovals();
});
EOSCRIPT;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Proposal Approval</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="approvalSearch" placeholder="Search..." onkeyup="filterTable('approvalSearch','approvalListContainer',1)">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="stats-row">
            <div class="stat-tile">
                <div class="stat-icon" style="background:#e8f0fe;color:var(--primary)">⏳</div>
                <div class="stat-body">
                    <h3 id="statPending">-</h3>
                    <p>Pending Approval</p>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-icon" style="background:#e6f4ea;color:var(--success)">✅</div>
                <div class="stat-body">
                    <h3 id="statApproved">-</h3>
                    <p>Approved</p>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-icon" style="background:#fce8e6;color:#dc2626">❌</div>
                <div class="stat-body">
                    <h3 id="statRejected">-</h3>
                    <p>Rejected</p>
                </div>
            </div>
        </div>

        <div class="tabs">
            <div class="tab active" id="tabPending_Approval" onclick="switchTab('Pending_Approval')">
                Pending <span class="tab-badge" id="tabBadgePending">0</span>
            </div>
            <div class="tab" id="tabFinalized" onclick="switchTab('Finalized')">
                Approved <span class="tab-badge" id="tabBadgeApproved">0</span>
            </div>
            <div class="tab" id="tabRejected" onclick="switchTab('Rejected')">
                Rejected <span class="tab-badge" id="tabBadgeRejected">0</span>
            </div>
        </div>

        <div id="approvalListContainer" style="margin-top:0"></div>
    </div>
</div>

<!-- Approve Proposal Modal -->
<div class="modal-overlay" id="approveProposalModal" onclick="if(event.target===this)closeModal('approveProposalModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Approve Salary Proposal</h3>
            <button class="modal-close" onclick="closeModal('approveProposalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="approveProposalForm">
                <input type="hidden" id="apprProposalId">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                    <div class="form-group">
                        <label>Candidate</label>
                        <input type="text" class="form-control" id="apprCandidateName" disabled>
                    </div>
                    <div class="form-group">
                        <label>Job Title</label>
                        <input type="text" class="form-control" id="apprJobTitle" disabled>
                    </div>
                    <div class="form-group">
                        <label>Proposed Salary</label>
                        <input type="text" class="form-control" id="apprProposedSalary" disabled>
                    </div>
                </div>
                <hr style="margin:12px 0;border-color:var(--gray-200)">
                <p style="font-size:13px;font-weight:600;margin:0 0 12px">Offer Details (auto-created on approval)</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label>Joining Date <span class="required">*</span></label>
                        <input type="date" class="form-control" id="apprJoiningDate" required>
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <select class="form-control" id="apprDesignation"></select>
                    </div>
                    <div class="form-group">
                        <label>Probation (months)</label>
                        <input type="number" class="form-control" id="apprProbation" value="6" min="1" max="12">
                    </div>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" id="apprNotes" rows="2" placeholder="Additional notes..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('approveProposalModal')">Cancel</button>
            <button type="button" class="btn btn-success" onclick="submitApprove()">Approve & Create Offer</button>
        </div>
    </div>
</div>

<!-- Reject Proposal Modal -->
<div class="modal-overlay" id="rejectProposalModal" onclick="if(event.target===this)closeModal('rejectProposalModal')">
    <div class="modal modal-small">
        <div class="modal-header modal-header-danger">
            <h3>Reject Salary Proposal</h3>
            <button class="modal-close" onclick="closeModal('rejectProposalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="rejectProposalForm">
                <input type="hidden" id="rejProposalId">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="rejCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Comments</label>
                    <textarea class="form-control" id="rejComments" rows="3" placeholder="Reason for rejection..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('rejectProposalModal')">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="submitReject()">Reject Proposal</button>
        </div>
    </div>
</div>

<!-- View Proposal Modal -->
<div class="modal-overlay" id="viewProposalModal" onclick="if(event.target===this)closeModal('viewProposalModal')">
    <div class="modal modal-sticky" style="max-width:780px;width:95%">
        <div class="modal-header modal-header-navy">
            <h3>Proposal Details</h3>
            <button class="modal-close" onclick="closeModal('viewProposalModal')">&times;</button>
        </div>
        <div class="modal-body" style="padding:24px">
            <div class="form-section">
                <div class="form-section-title">Candidate Information</div>
                <div class="form-row-3">
                    <div class="vc-item"><label>Candidate</label><span id="vwCandidate"></span></div>
                    <div class="vc-item"><label>Job Title</label><span id="vwJobTitle"></span></div>
                    <div class="vc-item"><label>Expected Salary</label><span id="vwExpectedSalary"></span></div>
                    <div class="vc-item"><label>Gender</label><span id="vwGender"></span></div>
                </div>
            </div>
            <div class="form-section">
                <div class="form-section-title">Proposal Info</div>
                <div class="form-row-3">
                    <div class="vc-item"><label>Proposed Salary</label><span id="vwProposedSalary"></span></div>
                    <div class="vc-item"><label>Version</label><span id="vwVersion"></span></div>
                    <div class="vc-item"><label>Status</label><span id="vwStatus"></span></div>
                    <div class="vc-item"><label>Submitted</label><span id="vwSubmitted"></span></div>
                    <div class="vc-item"><label>Finalized</label><span id="vwFinalized"></span></div>
                </div>
            </div>
            <div class="form-section" id="vwBreakdownSection">
                <div class="form-section-title">Salary Breakdown</div>
                <div class="form-row-3">
                    <div class="vc-item"><label>Gross Salary</label><span id="vwGross" style="font-weight:600"></span></div>
                    <div class="vc-item"><label>Basic Salary</label><span id="vwBasic"></span></div>
                    <div class="vc-item"><label>House Rent</label><span id="vwHouseRent"></span></div>
                    <div class="vc-item"><label>Medical Allowance</label><span id="vwMedical"></span></div>
                    <div class="vc-item"><label>Conveyance</label><span id="vwConveyance"></span></div>
                    <div class="vc-item"><label>Total Salary</label><span id="vwTotalSalary" style="font-weight:600;color:var(--primary)"></span></div>
                </div>
                <hr style="margin:16px 0;border-color:var(--gray-200)">
                <p style="font-size:13px;font-weight:600;margin:0 0 10px;color:var(--gray-600)">Deductions</p>
                <div class="form-row-3">
                    <div class="vc-item"><label>Personal PF</label><span id="vwPersonalPf"></span></div>
                    <div class="vc-item"><label>Company PF</label><span id="vwCompanyPf"></span></div>
                    <div class="vc-item"><label>PF Deductible</label><span id="vwPfDeductible"></span></div>
                    <div class="vc-item"><label>Monthly Tax</label><span id="vwMonthlyTax"></span></div>
                    <div class="vc-item"><label>Total Payable</label><span id="vwTotalPayable" style="font-weight:700;color:var(--success);font-size:15px"></span></div>
                </div>
                <hr style="margin:16px 0;border-color:var(--gray-200)">
                <p style="font-size:13px;font-weight:600;margin:0 0 10px;color:var(--gray-600)">Overtime &amp; PF Settings</p>
                <div class="form-row-3">
                    <div class="vc-item"><label>PF Applicable</label><span id="vwPfApplicable"></span></div>
                    <div class="vc-item"><label>OT (Out)</label><span id="vwOtOut"></span></div>
                    <div class="vc-item"><label>OT Holiday</label><span id="vwOtHoliday"></span></div>
                    <div class="vc-item"><label>OT Weekend</label><span id="vwOtWeekend"></span></div>
                </div>
                <hr style="margin:16px 0;border-color:var(--gray-200)">
                <p style="font-size:13px;font-weight:600;margin:0 0 10px;color:var(--gray-600)">Bank &amp; Payment</p>
                <div class="form-row-3">
                    <div class="vc-item"><label>Bank Name</label><span id="vwBankName"></span></div>
                    <div class="vc-item"><label>Account No</label><span id="vwAccountNo"></span></div>
                    <div class="vc-item"><label>Bank Code</label><span id="vwBankCode"></span></div>
                    <div class="vc-item"><label>Receiving Bank</label><span id="vwReceivingBank"></span></div>
                    <div class="vc-item"><label>Salary By</label><span id="vwSalaryGivenBy"></span></div>
                    <div class="vc-item"><label>Cash Paid</label><span id="vwCashPaid"></span></div>
                    <div class="vc-item"><label>Bank Paid</label><span id="vwBankPaid"></span></div>
                </div>
            </div>
            <div class="form-section">
                <div class="form-section-title">Negotiation Notes</div>
                <p id="vwNegotiationNotes" style="font-size:13px;color:var(--gray-700);margin:0;padding:8px 12px;background:var(--gray-50);border-radius:6px"></p>
            </div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('viewProposalModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
