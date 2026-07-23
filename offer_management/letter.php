<?php
$basePath    = '..';
$pageTitle   = 'Offer Letter';
$activePage  = 'offer_letter';
$breadcrumb  = 'Home / Recruitment / Offer Management / Offer Letter';
$pageStyles  = '
.empty-state { text-align:center; padding:48px 24px; color:var(--gray-500) }
.empty-state .empty-icon { font-size:48px; margin-bottom:12px }
.empty-state p { font-size:14px }
.preview-wide { max-width:900px !important; width:90% !important; max-height:90vh !important; height:auto !important; }
.preview-wide .modal-body { overflow-y:auto; flex:1; padding:20px 24px; }
.preview-content-wrap { padding:40px 48px; border:1px solid var(--gray-200); border-radius:8px; min-height:600px; background:#fff; line-height:1.8; font-size:15px; }
.preview-content-wrap h1 { text-align:center; color:#1a73e8; margin-bottom:4px; }
.preview-content-wrap h2 { color:#1a73e8; border-bottom:2px solid #1a73e8; padding-bottom:6px; margin:20px 0 10px; }
.preview-content-wrap h3 { color:#3c4043; margin:16px 0 8px; }
.preview-content-wrap hr { border:none; border-top:1px solid var(--gray-200); margin:16px 0; }
.preview-content-wrap blockquote { border-left:4px solid #1a73e8; padding:10px 16px; background:#f1f3f4; margin:16px 0; color:#5f6368; }
.preview-content-wrap table { width:100%; border-collapse:collapse; margin:10px 0; font-size:13px; }
.preview-content-wrap th { background:#f5f5f5; border:1px solid #ddd; padding:6px 10px; text-align:left; }
.preview-content-wrap td { border:1px solid #ddd; padding:6px 10px; }
.preview-content-wrap tr:nth-child(even) { background:#fafafa; }
.preview-content-wrap tr:last-child { background:#f0f7ff; font-weight:600; }
';
$pageScripts = <<<'EOSCRIPT'
var lettersData = [];

function renderLetters() {
    apiGet("ajax/list_offers.php?status=Approved", null, function(res) {
        lettersData = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("letterTableBody");
        if (lettersData.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">📄</div><p>No candidates ready for offer letter. Approve a salary proposal first.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < lettersData.length; i++) {
            var o = lettersData[i];
            var ls = o.letter_status || "Not Generated";
            var lsClass = ls === "Sent" ? "badge badge-success" : ls === "Generated" ? "badge badge-in-progress" : "badge";
            var action = "";
            if (ls === "Generated") {
                action += "<a class=\"btn btn-sm btn-primary\" href=\"generate_letter.php?offer_id=" + o.id + "\">Edit</a>";
                action += " <button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
                action += " <button class=\"btn btn-sm btn-success\" onclick=\"openSendEmail(" + o.id + ")\">Send</button>";
            }else if(ls === "Sent") {
                action += " <button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
            }else{
                action += "<a class=\"btn btn-sm btn-primary\" href=\"generate_letter.php?offer_id=" + o.id + "\">Generate</a>";
            }
            html += "<tr>" +
                "<td><strong>" + (o.candidate_name || o.first_name + " " + o.last_name) + "</strong></td>" +
                "<td>" + (o.job_title || o.job || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "৳") : "-") + "</td>" +
                "<td><span class=\"" + lsClass + "\">" + ls + "</span></td>" +
                "<td>" + (o.letter_generated_at || "-") + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function openSendEmail(offerId) {
    var o = null;
    for (var i = 0; i < lettersData.length; i++) { if (lettersData[i].id === offerId) { o = lettersData[i]; break; } }
    if (!o) return;
    document.getElementById("sendOfferId").value = o.id;
    document.getElementById("sendCandidateName").value = o.candidate_name || "";
    document.getElementById("sendEmail").value = o.email || "";
    document.getElementById("sendSubject").value = "Offer Letter - " + (o.job_title || "Position");
    document.getElementById("sendBody").value = "";
    openModal("sendEmailModal");
}

function submitSendEmail() {
    var offerId = document.getElementById("sendOfferId").value;
    var email = document.getElementById("sendEmail").value;
    var subject = document.getElementById("sendSubject").value;
    var body = document.getElementById("sendBody").value;
    if (!email) { showAlert("Please enter recipient email", "error"); return; }
    apiPost("ajax/letter_send.php", {
        offer_id: offerId,
        recipient: email,
        subject: subject,
        body: body
    }, function(res) {
        showAlert(res.message || "Email sent!", "success");
        closeModal("sendEmailModal");
        renderLetters();
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

document.addEventListener("DOMContentLoaded", function() {
    renderLetters();
});
EOSCRIPT;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Offer Letter</h2>
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="letterSearch" placeholder="Search..." onkeyup="filterTable('letterSearch','letterTable',1)">
            </div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="letterTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Salary</th>
                        <th>Letter Status</th>
                        <th>Generated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="letterTableBody"></tbody>
            </table>
        </div>
    </div>
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
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="sendCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Recipient Email <span class="required">*</span></label>
                    <input type="email" class="form-control" id="sendEmail" placeholder="recipient@example.com" required>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" class="form-control" id="sendSubject" placeholder="Email subject">
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
            <button type="button" class="btn btn-outline" onclick="closeModal('previewLetterModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
