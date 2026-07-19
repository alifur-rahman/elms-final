<?php
$basePath    = '..';
$pageTitle   = 'Offer Letter';
$activePage  = 'offer_letter';
$breadcrumb  = 'Home / Recruitment / Offer Management / Offer Letter';
$pageStyles  = '
.empty-state { text-align:center; padding:48px 24px; color:var(--gray-500) }
.empty-state .empty-icon { font-size:48px; margin-bottom:12px }
.empty-state p { font-size:14px }
#quillEditor { height:300px }
.merge-tags { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px }
.merge-tag-btn { display:inline-block; padding:4px 10px; font-size:12px; background:#f0f4ff; color:var(--primary); border:1px dashed var(--primary); border-radius:4px; cursor:pointer; user-select:none }
.merge-tag-btn:hover { background:#e0e8ff }
';
$pageScripts = <<<'EOSCRIPT'
var lettersData = [];
var quill = null;

function initLetterEditor() {
    if (typeof Quill === "undefined") {
        var checkQuill = setInterval(function() {
            if (typeof Quill !== "undefined") {
                clearInterval(checkQuill);
                initQuillInstance();
            }
        }, 200);
    } else {
        initQuillInstance();
    }
}

function initQuillInstance() {
    var editorEl = document.getElementById("quillEditor");
    if (!editorEl) return;
    quill = new Quill("#quillEditor", { theme: "snow", placeholder: "Compose offer letter...", modules: { toolbar: [["bold","italic","underline","strike"],[{list:"ordered"},{list:"bullet"}],["clean"]] } });
}

function insertMergeTag(tag) {
    if (!quill) return;
    var cursor = quill.getSelection(true);
    quill.insertText(cursor.index, "{{" + tag + "}}");
    quill.setSelection(cursor.index + tag.length + 4);
}

function loadTemplates() {
    apiGet("../../templates/ajax/templates_list.php?type=Offer", null, function(res) {
        var sel = document.getElementById("templateSelect");
        sel.innerHTML = "<option value=\"\">-- Select Template --</option>";
        if (res.success && res.data) {
            for (var i = 0; i < res.data.length; i++) {
                var t = res.data[i];
                var opt = document.createElement("option");
                opt.value = t.id;
                opt.textContent = t.name;
                opt.dataset.content = t.content;
                sel.appendChild(opt);
            }
        }
    });
}

function applyTemplate() {
    var sel = document.getElementById("templateSelect");
    var option = sel.options[sel.selectedIndex];
    if (!option || !option.value) return;
    var content = option.dataset.content;
    if (content && quill) {
        quill.setText("");
        quill.clipboard.dangerouslyPasteHTML(0, content);
        showAlert("Template loaded: " + option.textContent, "success");
    }
}

function resolveMergeTags() {
    var offerId = document.getElementById("letterOfferId").value;
    if (!offerId || !quill) { showAlert("Please select a candidate first", "error"); return; }
    var content = quill.root.innerHTML;
    if (!content || content === "<p><br></p>") { showAlert("Editor is empty", "error"); return; }
    apiPost("ajax/render_template.php", {
        offer_id: offerId,
        content: content
    }, function(res) {
        if (res.success && res.data && res.data.rendered_content) {
            quill.setText("");
            quill.clipboard.dangerouslyPasteHTML(0, res.data.rendered_content);
            showAlert("Merge tags resolved!", "success");
        } else {
            showAlert("No tags resolved or render failed", "info");
        }
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to resolve tags", "error");
    });
}

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
            action = "<button class=\"btn btn-sm btn-primary\" onclick=\"openGenerateLetter(" + o.id + ")\">Generate</button>";
            if (ls === "Generated") {
                action += " <button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
                action += " <button class=\"btn btn-sm btn-success\" onclick=\"openSendEmail(" + o.id + ")\">Send</button>";
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

function openGenerateLetter(offerId) {
    var o = null;
    for (var i = 0; i < lettersData.length; i++) { if (lettersData[i].id === offerId) { o = lettersData[i]; break; } }
    if (!o) return;
    document.getElementById("letterOfferId").value = o.id;
    document.getElementById("letterCandidateName").value = o.candidate_name || "";
    document.getElementById("letterPosition").value = o.job_title || "";
    document.getElementById("letterSalary").value = o.salary || "";
    document.getElementById("templateSelect").value = "";
    if (quill) {
        quill.setText("");
    }
    openModal("generateLetterModal");
    setTimeout(function() {
        if (quill) quill.setSelection(0, 0);
    }, 100);
}

function submitGenerateLetter() {
    var offerId = document.getElementById("letterOfferId").value;
    var letterContent = quill ? quill.root.innerHTML : "";
    if (!letterContent || letterContent === "<p><br></p>") { showAlert("Please write letter content", "error"); return; }
    apiPost("ajax/letter_generate.php", {
        offer_id: offerId,
        letter_content: letterContent
    }, function(res) {
        showAlert(res.message || "Letter generated!", "success");
        closeModal("generateLetterModal");
        renderLetters();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to generate letter", "error");
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
    initLetterEditor();
    loadTemplates();
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

<!-- Generate Letter Modal -->
<div class="modal-overlay" id="generateLetterModal" onclick="if(event.target===this)closeModal('generateLetterModal')">
    <div class="modal modal-wide">
        <div class="modal-header modal-header-navy">
            <h3>Generate Offer Letter</h3>
            <button class="modal-close" onclick="closeModal('generateLetterModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="generateLetterForm">
                <input type="hidden" id="letterOfferId">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px">
                    <div class="form-group">
                        <label>Candidate</label>
                        <input type="text" class="form-control" id="letterCandidateName" disabled>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" class="form-control" id="letterPosition" disabled>
                    </div>
                    <div class="form-group">
                        <label>Offered Salary</label>
                        <input type="text" class="form-control" id="letterSalary" disabled>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:12px">
                    <label>Template</label>
                    <div style="display:flex;gap:8px">
                        <select class="form-control" id="templateSelect" onchange="applyTemplate()" style="flex:1"></select>
                        <button type="button" class="btn btn-sm btn-outline" onclick="resolveMergeTags()" title="Resolve {{TAGS}} with actual values">Resolve Tags</button>
                    </div>
                </div>
                <div class="merge-tags">
                    <span class="merge-tag-btn" onclick="insertMergeTag('CANDIDATE_NAME')">{{CANDIDATE_NAME}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('DESIGNATION')">{{DESIGNATION}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('JOB_TITLE')">{{JOB_TITLE}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('DEPARTMENT')">{{DEPARTMENT}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('SALARY')">{{SALARY}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('JOINING_DATE')">{{JOINING_DATE}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('COMPANY_NAME')">{{COMPANY_NAME}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('HR_NAME')">{{HR_NAME}}</span>
                    <span class="merge-tag-btn" onclick="insertMergeTag('DATE')">{{DATE}}</span>
                </div>
                <div id="quillEditor"></div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('generateLetterModal')">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitGenerateLetter()">Generate Letter</button>
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
    <div class="modal modal-wide">
        <div class="modal-header modal-header-navy">
            <h3>Preview Offer Letter</h3>
            <button class="modal-close" onclick="closeModal('previewLetterModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="previewContent" style="padding:24px;border:1px solid var(--gray-200);border-radius:6px;min-height:200px;background:#fff"></div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('previewLetterModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
