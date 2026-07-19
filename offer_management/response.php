<?php
$basePath    = '..';
$pageTitle   = 'Candidate Response';
$activePage  = 'offer_response';
$breadcrumb  = 'Home / Recruitment / Offer Management / Candidate Response';
$pageStyles  = '
.tabs { display:flex; border-bottom:2px solid var(--gray-200); margin-bottom:0 }
.tab { padding:12px 24px; cursor:pointer; font-size:14px; font-weight:500; color:var(--gray-500); border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .2s }
.tab.active { color:var(--primary); border-bottom-color:var(--primary) }
.empty-state { text-align:center; padding:48px 24px; color:var(--gray-500) }
.empty-state .empty-icon { font-size:48px; margin-bottom:12px }
.empty-state p { font-size:14px }
';
$pageScripts = <<<'EOSCRIPT'
var responseOffers = [];
var respondedOffers = [];
var currentResponseTab = "pending";

function renderResponses() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=Pending&letter_status=Sent", null, function(res) {
        responseOffers = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("responseTableBody");
        if (responseOffers.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"empty-state\"><div class=\"empty-icon\">📬</div><p>No offers awaiting response.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < responseOffers.length; i++) {
            var o = responseOffers[i];
            html += "<tr>" +
                "<td><strong>" + (o.candidate_name || o.first_name + " " + o.last_name) + "</strong></td>" +
                "<td>" + (o.job_title || o.job || "N/A") + "</td>" +
                "<td>" + (o.offered_salary ? formatCurrency(o.offered_salary, "৳") : "-") + "</td>" +
                "<td>" + (o.letter_date || "-") + "</td>" +
                "<td><span class=\"badge badge-in-progress\">Pending</span></td>" +
                "<td><button class=\"btn btn-sm btn-primary\" onclick=\"openMarkResponse(" + o.id + ")\">Record Response</button></td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function renderResponded() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=Accepted,Rejected", null, function(res) {
        respondedOffers = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("respondedTableBody");
        if (respondedOffers.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No responded offers.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < respondedOffers.length; i++) {
            var o = respondedOffers[i];
            var rsClass = o.response_status === "Accepted" ? "badge badge-success" : "badge badge-danger";
            html += "<tr>" +
                "<td><strong>" + (o.candidate_name || o.first_name + " " + o.last_name) + "</strong></td>" +
                "<td>" + (o.job_title || o.job || "N/A") + "</td>" +
                "<td>" + (o.offered_salary ? formatCurrency(o.offered_salary, "৳") : "-") + "</td>" +
                "<td><span class=\"" + rsClass + "\">" + o.response_status + "</span></td>" +
                "<td>" + (o.response_date || "-") + "</td>" +
                "<td>" + (o.response_notes || "-") + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function switchResponseTab(tab) {
    currentResponseTab = tab;
    document.querySelectorAll("#responseTabContainer .tab").forEach(function(el) { el.classList.remove("active"); });
    document.getElementById("respTab" + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add("active");
    if (tab === "pending") { document.getElementById("pendingResponseSection").style.display = ""; document.getElementById("respondedSection").style.display = "none"; }
    else { document.getElementById("pendingResponseSection").style.display = "none"; document.getElementById("respondedSection").style.display = ""; renderResponded(); }
}

function openMarkResponse(offerId) {
    var o = null;
    for (var i = 0; i < responseOffers.length; i++) { if (responseOffers[i].id === offerId) { o = responseOffers[i]; break; } }
    if (!o) return;
    document.getElementById("respOfferId").value = o.id;
    document.getElementById("respCandidateName").value = o.candidate_name || o.first_name + " " + o.last_name;
    openModal("markResponseModal");
}

function submitMarkResponse() {
    var offerId = document.getElementById("respOfferId").value;
    var status = document.querySelector('input[name="respStatus"]:checked');
    var notes = document.getElementById("respNotes").value;
    if (!status) { showAlert("Please select a response status", "error"); return; }
    apiPost("ajax/respond.php", {
        offer_id: offerId,
        response: status.value,
        notes: notes
    }, function(res) {
        showAlert(res.message || "Response recorded!", "success");
        closeModal("markResponseModal");
        renderResponses();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to record response", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderResponses();
});
EOSCRIPT;
ob_start();
?>
<div class="card" id="pendingResponseSection">
    <div class="card-header">
        <h2>Candidate Response</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="responseSearch" placeholder="Search..." onkeyup="filterTable('responseSearch','responseTable',1)">
            </div>
        </div>
    </div>
    <div style="padding:0 16px">
        <div class="tabs" id="responseTabContainer">
            <div class="tab active" id="respTabPending" onclick="switchResponseTab('pending')">Pending Response</div>
            <div class="tab" id="respTabResponded" onclick="switchResponseTab('responded')">Responded</div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="responseTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Offered Salary</th>
                        <th>Letter Sent</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="responseTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="card" id="respondedSection" style="display:none">
    <div class="card-header">
        <h2>Responded Offers</h2>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="respondedTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Offered Salary</th>
                        <th>Response</th>
                        <th>Date</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="respondedTableBody"></tbody>
            </table>
        </div>
    </div>
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
            <button type="button" class="btn btn-outline" onclick="closeModal('markResponseModal')">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitMarkResponse()">Save Response</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
