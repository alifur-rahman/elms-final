<?php
$basePath    = '..';
$pageTitle   = 'Confirmation Letters';
$activePage  = 'confirmation_letter';
$breadcrumb  = 'Home / Confirmation / Confirmation Letters';
$pageStyles  = '
.preview-wide { max-width:900px !important; width:90% !important; max-height:90vh !important; height:auto !important; }
.preview-wide .modal-body { overflow-y:auto; flex:1; padding:20px 24px; }
.preview-content-wrap { padding:40px 48px; border:1px solid var(--gray-200); border-radius:8px; min-height:600px; background:#fff; line-height:1.8; font-size:15px; }
.preview-content-wrap h1 { text-align:center; color:#1a73e8; margin-bottom:4px; }
.preview-content-wrap h2 { color:#1a73e8; border-bottom:2px solid #1a73e8; padding-bottom:6px; margin:20px 0 10px; }
.preview-content-wrap table { width:100%; border-collapse:collapse; margin:10px 0; font-size:13px; }
.preview-content-wrap th { background:#f5f5f5; border:1px solid #ddd; padding:6px 10px; text-align:left; }
.preview-content-wrap td { border:1px solid #ddd; padding:6px 10px; }
';
$pageScripts = <<<'EOSCRIPT'
var allLetters = [];

function renderLetters(statusFilter) {
    var url = "ajax/list_letters.php";
    if (statusFilter) url += "?status=" + statusFilter;
    apiGet(url, null, function(res) {
        allLetters = res.success && res.data ? res.data : [];
        renderTable();
    });
}

function renderTable() {
    var tbody = document.getElementById("letterTableBody");
    if (allLetters.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"7\"><div class='empty-state'><div class='empty-icon'>&#128196;</div><p>No confirmation letters found.</p></div></td></tr>";
        return;
    }
    var html = "";
    for (var i = 0; i < allLetters.length; i++) {
        var l = allLetters[i];
        var lsClass = l.status === "Sent" ? "badge-success" : l.status === "Approved" ? "badge-in-progress" : l.status === "Rejected" ? "badge-danger" : "badge";
        var action = "";
        if (l.status === "Generated") {
            action = "<button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + l.id + ")\">Preview</button>";
            action += " <button class=\"btn btn-sm btn-primary\" onclick=\"approveLetter(" + l.id + ")\">Approve</button>";
        } else if (l.status === "Approved") {
            action = "<button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + l.id + ")\">Preview</button>";
            action += " <button class=\"btn btn-sm btn-success\" onclick=\"openSendEmail(" + l.id + ")\">Send</button>";
        } else if (l.status === "Sent") {
            action = "<button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + l.id + ")\">Preview</button>";
        }
        html += "<tr>" +
            "<td><strong>" + (l.employee_name || "N/A") + "</strong><br><small>" + (l.employee_code || "") + "</small></td>" +
            "<td>" + (l.job_title || "N/A") + "</td>" +
            "<td>" + (l.dept_name || "N/A") + "</td>" +
            "<td>" + (l.employment_type || "Probationer") + "</td>" +
            "<td>" + (l.salary ? formatCurrency(l.salary, "৳") : "-") + "</td>" +
            "<td><span class=\"badge " + lsClass + "\">" + l.status + "</span></td>" +
            "<td>" + action + "</td>" +
        "</tr>";
    }
    tbody.innerHTML = html;
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
        renderLetters();
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
        renderLetters();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to send", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() { renderLetters(); });
EOSCRIPT;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Confirmation Letters</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="letterSearch" placeholder="Search..." onkeyup="filterTable('letterSearch','letterTable',0)">
            </div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="letterTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="letterTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal-overlay" id="previewLetterModal" onclick="if(event.target===this)closeModal('previewLetterModal')">
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
<div class="modal-overlay" id="sendEmailModal" onclick="if(event.target===this)closeModal('sendEmailModal')">
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
