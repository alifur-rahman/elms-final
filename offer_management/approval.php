<?php
$basePath    = '..';
$pageTitle   = 'Offer Approval';
$activePage  = 'offer_approval';
$breadcrumb  = 'Home / Recruitment / Offer Management / Offer Approval';
$pageStyles  = '
.tabs { display:flex; border-bottom:2px solid var(--gray-200); margin-bottom:0 }
.tab { padding:12px 24px; cursor:pointer; font-size:14px; font-weight:500; color:var(--gray-500); border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .2s }
.tab.active { color:var(--primary); border-bottom-color:var(--primary) }
.empty-state { text-align:center; padding:48px 24px; color:var(--gray-500) }
.empty-state .empty-icon { font-size:48px; margin-bottom:12px }
.empty-state p { font-size:14px }
';
$pageScripts = <<<'EOSCRIPT'
var offersData = [];
var currentTab = "pending";

function renderApprovalTable() {
    var url = "ajax/list_pending.php";
    if (currentTab === "ready") url = "ajax/list_offers.php?status=Approved&with_letter=1&letter_status_not=Sent";
    else if (currentTab === "history") url = "ajax/list_offers.php?status=All&not_status=Pending";

    apiGet(url, null, function(res) {
        offersData = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("approvalTableBody");
        if (offersData.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No offers found in this section.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < offersData.length; i++) {
            var o = offersData[i];
            var statusClass = o.offer_status === "Approved" ? "badge badge-success" : o.offer_status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
            var action = "";
            if (currentTab === "pending" && o.offer_status === "Pending") {
                action = "<button class=\"btn btn-sm btn-primary\" onclick=\"openApprovalAction(" + o.id + ")\">Review</button>";
            }
            html += "<tr>" +
                "<td><strong>" + (o.candidate_name || o.first_name + " " + o.last_name) + "</strong></td>" +
                "<td>" + (o.job_title || o.job || "N/A") + "</td>" +
                "<td>" + (o.offered_salary || o.proposed_amount ? formatCurrency(o.offered_salary || o.proposed_amount, "৳") : "-") + "</td>" +
                "<td><span class=\"" + statusClass + "\">" + (o.offer_status || "Pending") + "</span></td>" +
                "<td>" + (o.created_at || "-") + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".tab").forEach(function(el) { el.classList.remove("active"); });
    document.getElementById("tab" + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add("active");
    renderApprovalTable();
}

function openCreateOffer() {
    apiGet("ajax/list_proposals.php?status=Finalized", null, function(res) {
        var candidates = res.success && res.data ? res.data : [];
        var select = document.getElementById("createOfferCandidate");
        select.innerHTML = "<option value=\"\">-- Select --</option>";
        for (var i = 0; i < candidates.length; i++) {
            var c = candidates[i];
            var opt = document.createElement("option");
            opt.value = c.id;
            opt.textContent = c.first_name + " " + c.last_name + " (" + (c.job_title || "N/A") + ")";
            select.appendChild(opt);
        }
        if (candidates.length === 0) {
            select.innerHTML = "<option value=\"\">No finalized candidates available</option>";
        }
        openModal("createOfferModal");
    });
}

function submitCreateOffer() {
    var candidateId = document.getElementById("createOfferCandidate").value;
    var salary = document.getElementById("createOfferSalary").value;
    var notes = document.getElementById("createOfferNotes").value;
    if (!candidateId || !salary) { showAlert("Please select a candidate and enter salary", "error"); return; }
    apiPost("ajax/offer_create.php", {
        candidate_id: candidateId,
        offered_salary: salary,
        notes: notes
    }, function(res) {
        showAlert(res.message || "Offer created!", "success");
        closeModal("createOfferModal");
        renderApprovalTable();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to create offer", "error");
    });
}

function openApprovalAction(offerId) {
    var o = null;
    for (var i = 0; i < offersData.length; i++) { if (offersData[i].id === offerId) { o = offersData[i]; break; } }
    if (!o) return;
    document.getElementById("approvalOfferId").value = o.id;
    document.getElementById("approvalCandidateName").value = o.candidate_name || o.first_name + " " + o.last_name;
    document.getElementById("approvalSalary").value = o.offered_salary || o.proposed_amount || "";
    openModal("approvalActionModal");
}

function submitApprovalAction(action) {
    var offerId = document.getElementById("approvalOfferId").value;
    var comments = document.getElementById("approvalComments").value;
    apiPost("ajax/offer_approve.php", {
        offer_id: offerId,
        action: action,
        comments: comments
    }, function(res) {
        showAlert(res.message || "Offer " + action + "d!", "success");
        closeModal("approvalActionModal");
        renderApprovalTable();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to process", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderApprovalTable();
    document.getElementById("approvalActionModal").querySelectorAll("[data-action]").forEach(function(btn) {
        btn.addEventListener("click", function() { submitApprovalAction(this.dataset.action); });
    });
});
EOSCRIPT;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Offer Approval</h2>
        <button class="btn btn-primary btn-sm" onclick="openCreateOffer()">+ Create Offer</button>
    </div>
    <div style="padding:0 16px">
        <div class="tabs">
            <div class="tab active" id="tabPending" onclick="switchTab('pending')">Pending Approval</div>
            <div class="tab" id="tabReady" onclick="switchTab('ready')">Ready for Letter</div>
            <div class="tab" id="tabHistory" onclick="switchTab('history')">History</div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="approvalTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Offered Salary</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="approvalTableBody"></tbody>
            </table>
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
                <div class="form-group">
                    <label>Candidate <span class="required">*</span></label>
                    <select class="form-control" id="createOfferCandidate"></select>
                </div>
                <div class="form-group">
                    <label>Offered Salary <span class="required">*</span></label>
                    <input type="number" class="form-control" id="createOfferSalary" placeholder="Enter offered salary">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" id="createOfferNotes" rows="3" placeholder="Additional notes..."></textarea>
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
    <div class="modal modal-small">
        <div class="modal-header modal-header-navy">
            <h3>Review Offer</h3>
            <button class="modal-close" onclick="closeModal('approvalActionModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="approvalActionForm">
                <input type="hidden" id="approvalOfferId">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="approvalCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Salary</label>
                    <input type="text" class="form-control" id="approvalSalary" disabled>
                </div>
                <div class="form-group">
                    <label>Comments</label>
                    <textarea class="form-control" id="approvalComments" rows="3" placeholder="Approval/rejection comments..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('approvalActionModal')">Cancel</button>
            <button type="button" class="btn btn-success" data-action="Approved">Approve</button>
            <button type="button" class="btn btn-danger" data-action="Rejected">Reject</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
