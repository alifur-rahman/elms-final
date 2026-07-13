<?php
$basePath    = '..';
$pageTitle   = 'Offer Approval';
$activePage  = 'offer_approval';
$breadcrumb  = 'Home / Recruitment / Offer Approval';
$pageStyles  = '
.tab-bar { display: flex; gap: 0; border-bottom: 2px solid var(--gray-200); margin-bottom: 24px; }
.tab-btn { padding: 10px 24px; font-size: 13px; font-weight: 500; background: none; border: none; cursor: pointer; color: var(--gray-500); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: var(--transition); font-family: inherit; }
.tab-btn:hover { color: var(--gray-700); }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-content { display: none; }
.tab-content.active { display: block; }
.empty-state { text-align: center; padding: 48px 24px; color: var(--gray-500); }
.empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; }
.empty-state p { font-size: 14px; }
.approval-level { display: flex; align-items: center; gap: 8px; }
.approval-level .level-line { display: flex; flex-direction: column; align-items: center; }
.approval-level .level-dot { width: 10px; height: 10px; border-radius: 50%; }
.approval-level .level-dot.approved { background: var(--success); }
.approval-level .level-dot.pending { background: var(--warning); }
.approval-level .level-dot.rejected { background: var(--danger); }
.approval-level .level-label { font-size: 11px; color: var(--gray-500); }
.text-muted { color: var(--gray-500); font-size: 12px; }
@media (max-width:768px){ .tab-bar { overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; flex-wrap: nowrap; } .tab-btn { padding: 10px 14px; font-size: 12px; } }
@media (max-width:480px){ .approval-level { flex-wrap: wrap; gap: 4px; } }
';
$pageScripts = '
var selectedCandidates = [];
var pendingOffers = [];

function switchTab(tabId, btn) {
    document.querySelectorAll(".tab-content").forEach(function(t) { t.classList.remove("active"); });
    document.querySelectorAll(".tab-btn").forEach(function(b) { b.classList.remove("active"); });
    document.getElementById(tabId).classList.add("active");
    if (btn) btn.classList.add("active");
}

function renderReadyForOffer() {
    apiGet("ajax/list_ready.php", null, function(res) {
        selectedCandidates = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("readyTableBody");
        if (selectedCandidates.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No candidates ready for offer at this time.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < selectedCandidates.length; i++) {
            var c = selectedCandidates[i];
            var badgeClass = getBadgeClass(c.status_name);
            var salary = c.expected_salary ? formatCurrency(c.expected_salary, "৳") : "-";
            var applied = formatDateTime(c.applied_at);
            var action = "";
            if (c.status_name === "Selected") {
                action = "<button class=\"btn btn-sm btn-success\" onclick=\"openCreateOffer(" + c.id + ")\">Create Offer</button>";
            } else {
                action = "<span class=\"badge badge-in-progress\">Pending Approval</span>";
            }
            html += "<tr>" +
                "<td><strong>" + c.first_name + " " + c.last_name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td><span class=\"badge " + badgeClass + "\">" + c.status_name + "</span></td>" +
                "<td>" + salary + "</td>" +
                "<td>" + applied + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function renderPendingApproval() {
    apiGet("ajax/list_pending.php", null, function(res) {
        pendingOffers = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("pendingTableBody");
        if (pendingOffers.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">✅</div><p>No pending approvals.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < pendingOffers.length; i++) {
            var o = pendingOffers[i];
            var salary = formatCurrency(o.salary, "৳");
            var jDate = formatDateTime(o.joining_date);
            var levelHtml = "";
            for (var j = 0; j < o.approvals.length; j++) {
                var a = o.approvals[j];
                var dotClass = a.status === "Approved" ? "approved" : a.status === "Rejected" ? "rejected" : "pending";
                levelHtml += "<div class=\"approval-level\"><div class=\"level-line\"><div class=\"level-dot " + dotClass + "\"></div></div><div class=\"level-label\">Level " + a.level + ": " + a.status + "</div></div>";
            }
            if (!levelHtml) levelHtml = "<span class=\"text-muted\">No approval levels</span>";
            var stHtml = "<span class=\"badge " + getBadgeClass(o.status) + "\">" + o.status + "</span>";
            var action = "<div class=\"btn-group\">" +
                "<button class=\"btn btn-sm btn-success\" onclick=\"openApprovalAction(" + o.id + ")\">Approve</button>" +
                "<button class=\"btn btn-sm btn-danger\" onclick=\"openApprovalAction(" + o.id + ")\" style=\"margin-left:4px\">Reject</button>" +
            "</div>";
            html += "<tr>" +
                "<td><strong>" + o.candidate_name + "</strong></td>" +
                "<td>" + o.job_title + "</td>" +
                "<td>" + salary + "</td>" +
                "<td>" + jDate + "</td>" +
                "<td>" + levelHtml + "</td>" +
                "<td>" + stHtml + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function openCreateOffer(candidateId) {
    var c = null;
    for (var i = 0; i < selectedCandidates.length; i++) { if (selectedCandidates[i].id === candidateId) { c = selectedCandidates[i]; break; } }
    if (!c) return;

    document.getElementById("offerCandidateId").value = c.id;
    document.getElementById("offerCandidateName").value = c.first_name + " " + c.last_name;
    document.getElementById("offeredSalary").value = c.expected_salary || "";
    document.getElementById("joiningDate").value = "";
    document.getElementById("probationMonths").value = 6;
    document.getElementById("offerNotes").value = "";
    document.getElementById("offerDept").value = c.job_title ? c.job_title + " Dept" : "";

    var desSel = document.getElementById("offerDesignation");
    desSel.innerHTML = "<option value=\"\">Select designation</option>";

    apiGet("ajax/designations.php?dept_id=" + c.dept_id, null, function(res2) {
        if (res2.success && res2.data) {
            for (var i = 0; i < res2.data.length; i++) {
                var opt = document.createElement("option");
                opt.value = res2.data[i].id;
                opt.textContent = res2.data[i].name;
                desSel.appendChild(opt);
            }
        }
    });

    openModal("createOfferModal");
}

function submitCreateOffer() {
    var candidateId = document.getElementById("offerCandidateId").value;
    var salary = document.getElementById("offeredSalary").value;
    var date = document.getElementById("joiningDate").value;
    var des = document.getElementById("offerDesignation").value;
    var prob = document.getElementById("probationMonths").value;
    var notes = document.getElementById("offerNotes").value;

    if (!salary || !date || !des) {
        showAlert("Please fill all required fields", "error");
        return;
    }

    apiPost("ajax/create.php", {
        candidate_id: candidateId,
        salary: salary,
        joining_date: date,
        designation_id: des,
        probation_months: prob || 6,
        notes: notes
    }, function(res) {
        showAlert(res.message || "Offer created!", "success");
        closeModal("createOfferModal");
        renderReadyForOffer();
        renderPendingApproval();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to create offer", "error");
    });
}

function openApprovalAction(offerId) {
    var o = null;
    for (var i = 0; i < pendingOffers.length; i++) { if (pendingOffers[i].id === offerId) { o = pendingOffers[i]; break; } }
    if (!o) return;

    document.getElementById("approvalOfferId").value = offerId;
    document.getElementById("approvalCandidateName").value = o.candidate_name;

    var pendingApproval = null;
    for (var j = 0; j < o.approvals.length; j++) { if (o.approvals[j].status === "Pending") { pendingApproval = o.approvals[j]; break; } }
    if (pendingApproval) {
        document.getElementById("approvalId").value = pendingApproval.id;
    } else {
        document.getElementById("approvalId").value = "";
    }
    document.getElementById("approvalComment").value = "";
    var radios = document.querySelectorAll("input[name=\"approvalAction\"]");
    for (var k = 0; k < radios.length; k++) { if (radios[k].value === "Approved") radios[k].checked = true; }
    openModal("approvalActionModal");
}

function submitApprovalAction() {
    var offerId = document.getElementById("approvalOfferId").value;
    var approvalId = document.getElementById("approvalId").value;
    var comment = document.getElementById("approvalComment").value;
    var actionEl = document.querySelector("input[name=\"approvalAction\"]:checked");
    var action = actionEl ? actionEl.value : "Approved";

    if (!approvalId) { showAlert("No pending approval found", "error"); return; }

    apiPost("ajax/approve.php", {
        offer_id: offerId,
        approval_id: approvalId,
        action: action,
        comment: comment
    }, function(res) {
        showAlert(res.message || "Action completed!", "success");
        closeModal("approvalActionModal");
        renderReadyForOffer();
        renderPendingApproval();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderReadyForOffer();
    renderPendingApproval();
});
';

ob_start();
?>
<div class="tab-bar">
    <button class="tab-btn active" onclick="switchTab('readyTab',this)">Ready for Offer</button>
    <button class="tab-btn" onclick="switchTab('pendingTab',this)">Pending Approval</button>
</div>

<!-- Section 1: Ready for Offer -->
<div id="readyTab" class="tab-content active">
    <div class="card">
        <div class="card-header">
            <h2>Ready for Offer</h2>
            <div class="filter-bar">
                <div class="search-box">
                    <span class="search-icon">&#128269;</span>
                    <input type="text" id="readySearch" placeholder="Search candidates..." onkeyup="filterTable('readySearch','readyTable',1)">
                </div>
            </div>
        </div>
        <div class="card-body" style="padding:0">
            <div class="table-container">
                <table id="readyTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job Applied</th>
                            <th>Status</th>
                            <th>Expected Salary</th>
                            <th>Applied Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="readyTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Pending Approval -->
<div id="pendingTab" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h2>Pending Approval</h2>
            <div class="filter-bar">
                <div class="search-box">
                    <span class="search-icon">&#128269;</span>
                    <input type="text" id="pendingSearch" placeholder="Search..." onkeyup="filterTable('pendingSearch','pendingTable',0)">
                </div>
            </div>
        </div>
        <div class="card-body" style="padding:0">
            <div class="table-container">
                <table id="pendingTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Offered Salary</th>
                            <th>Joining Date</th>
                            <th>Approval Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="pendingTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Offer Modal -->
<div class="modal-overlay" id="createOfferModal" onclick="if(event.target===this)closeModal('createOfferModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Create Offer</h3>
            <button class="modal-close" onclick="closeModal('createOfferModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createOfferForm">
                <input type="hidden" id="offerCandidateId" name="candidate_id">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="offerCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Offered Salary <span class="required">*</span></label>
                    <input type="number" class="form-control" id="offeredSalary" name="offered_salary" placeholder="Enter offered salary" required>
                </div>
                <div class="form-group">
                    <label>Joining Date <span class="required">*</span></label>
                    <input type="date" class="form-control" id="joiningDate" name="joining_date" required>
                </div>
                <div class="form-group">
                    <label>Designation <span class="required">*</span></label>
                    <select class="form-control" id="offerDesignation" name="designation_id" required>
                        <option value="">Select designation</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" class="form-control" id="offerDept" disabled>
                </div>
                <div class="form-group">
                    <label>Probation (months)</label>
                    <input type="number" class="form-control" id="probationMonths" name="probation_months" value="6" min="1">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" id="offerNotes" name="notes" rows="3" placeholder="Any additional notes..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('createOfferModal')">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitCreateOffer()">Create Offer</button>
        </div>
    </div>
</div>

<!-- Approval Action Modal -->
<div class="modal-overlay" id="approvalActionModal" onclick="if(event.target===this)closeModal('approvalActionModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Approval Action</h3>
            <button class="modal-close" onclick="closeModal('approvalActionModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="approvalActionForm">
                <input type="hidden" id="approvalOfferId" name="offer_id">
                <input type="hidden" id="approvalId" name="approval_id">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="approvalCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Action <span class="required">*</span></label>
                    <div class="btn-group" style="margin-top:4px">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border:1px solid var(--gray-300);border-radius:6px;background:var(--gray-50)">
                            <input type="radio" name="approvalAction" value="Approved" checked> Approve
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border:1px solid var(--gray-300);border-radius:6px;background:var(--gray-50)">
                            <input type="radio" name="approvalAction" value="Rejected"> Reject
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <textarea class="form-control" id="approvalComment" name="comment" rows="3" placeholder="Add a comment..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('approvalActionModal')">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitApprovalAction()">Submit</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
