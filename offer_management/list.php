<?php
$basePath    = '..';
$pageTitle   = 'Offer Management';
$activePage  = 'offer_management';
$breadcrumb  = 'Home / Recruitment / Offer Management';
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
.section-title { font-size: 13px; font-weight: 600; color: var(--gray-600); padding: 12px 16px; background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
.text-green { color: var(--success); } .text-red { color: var(--danger); } .text-warning { color: #e67e22; }
@media (max-width:768px){ .tab-bar { overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; flex-wrap: nowrap; } .tab-btn { padding: 10px 14px; font-size: 12px; } }
@media (max-width:480px){ .approval-level { flex-wrap: wrap; gap: 4px; } }
';
$pageScripts = <<<'EOSCRIPT'
var selectedCandidates = [], pendingOffers = [], approvedOffers = [];

function switchTab(tabId, btn) {
    document.querySelectorAll(".tab-content").forEach(function(t) { t.classList.remove("active"); });
    document.querySelectorAll(".tab-btn").forEach(function(b) { b.classList.remove("active"); });
    document.getElementById(tabId).classList.add("active");
    if (btn) btn.classList.add("active");
}

// ============================================================
// Tab 1: Salary Proposal
// ============================================================
var proposalCandidates = [];

function renderProposals() {
    apiGet("ajax/list_proposals.php", null, function(res) {
        proposalCandidates = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("proposalTableBody");
        if (proposalCandidates.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">💰</div><p>No candidates ready for salary proposal.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < proposalCandidates.length; i++) {
            var c = proposalCandidates[i];
            var salary = c.expected_salary ? formatCurrency(c.expected_salary, "৳") : "-";
            var proposalStatus = c.proposal_status || "None";
            var statusClass = proposalStatus === "Finalized" ? "badge badge-success" : proposalStatus === "Proposed" ? "badge badge-in-progress" : "badge";
            var action = "";
            if (proposalStatus === "None" || proposalStatus === "Draft") {
                action = "<button class=\"btn btn-sm btn-primary\" onclick=\"openCreateProposal(" + c.id + ")\">Propose Salary</button>";
            } else if (proposalStatus === "Proposed") {
                action = "<button class=\"btn btn-sm btn-success\" onclick=\"openFinalizeProposal(" + c.id + ")\">Finalize</button> <button class=\"btn btn-sm btn-outline\" onclick=\"openCreateProposal(" + c.id + ")\">Revise</button>";
            } else if (proposalStatus === "Finalized") {
                action = "<span class=\"badge badge-success\">Ready for Offer</span>";
            }
            html += "<tr>" +
                "<td><strong>" + c.first_name + " " + c.last_name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td>" + salary + "</td>" +
                "<td>" + (c.proposed_amount ? formatCurrency(c.proposed_amount, "৳") : "-") + "</td>" +
                "<td><span class=\"" + statusClass + "\">" + proposalStatus + "</span></td>" +
                "<td>" + (c.version ? "v" + c.version : "-") + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function openCreateProposal(candidateId) {
    var c = null;
    for (var i = 0; i < proposalCandidates.length; i++) { if (proposalCandidates[i].id === candidateId) { c = proposalCandidates[i]; break; } }
    if (!c) return;
    document.getElementById("propCandidateId").value = c.id;
    document.getElementById("propCandidateName").value = c.first_name + " " + c.last_name;
    document.getElementById("propExpectedSalary").value = c.expected_salary || "";
    document.getElementById("propProposedSalary").value = c.expected_salary || "";
    document.getElementById("propNegotiationNotes").value = "";
    document.getElementById("propTitle").textContent = c.proposal_id ? "Revise Salary Proposal" : "Create Salary Proposal";
    openModal("createProposalModal");
}

function submitCreateProposal() {
    var candidateId = document.getElementById("propCandidateId").value;
    var proposedSalary = document.getElementById("propProposedSalary").value;
    var notes = document.getElementById("propNegotiationNotes").value;
    if (!proposedSalary) { showAlert("Please enter proposed salary", "error"); return; }
    apiPost("ajax/proposal_create.php", {
        candidate_id: candidateId,
        proposed_salary: proposedSalary,
        negotiation_notes: notes
    }, function(res) {
        showAlert(res.message || "Proposal saved!", "success");
        closeModal("createProposalModal");
        renderProposals();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save proposal", "error");
    });
}

function openFinalizeProposal(candidateId) {
    var c = null;
    for (var i = 0; i < proposalCandidates.length; i++) { if (proposalCandidates[i].id === candidateId) { c = proposalCandidates[i]; break; } }
    if (!c) return;
    document.getElementById("finalPropCandidateId").value = c.id;
    document.getElementById("finalPropName").value = c.first_name + " " + c.last_name;
    document.getElementById("finalPropAmount").value = c.proposed_amount || c.expected_salary || "";
    openModal("finalizeProposalModal");
}

function submitFinalizeProposal() {
    var candidateId = document.getElementById("finalPropCandidateId").value;
    var amount = document.getElementById("finalPropAmount").value;
    if (!amount) { showAlert("Please enter final offered amount", "error"); return; }
    apiPost("ajax/proposal_finalize.php", {
        candidate_id: candidateId,
        offered_salary: amount
    }, function(res) {
        showAlert(res.message || "Proposal finalized!", "success");
        closeModal("finalizeProposalModal");
        renderProposals();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to finalize", "error");
    });
}

// ============================================================
// Tab 2: Offer Approval (unified table)
// ============================================================
function renderApprovalTable() {
    var allData = { ready: [], pending: [], history: [] };
    var pending = 3;
    function checkDone() { if (--pending <= 0) buildApprovalTable(allData); }

    apiGet("ajax/list_ready.php", null, function(res) {
        allData.ready = res.success && res.data ? res.data : [];
        checkDone();
    });
    apiGet("ajax/list_pending.php", null, function(res) {
        allData.pending = res.success && res.data ? res.data : [];
        checkDone();
    });
    apiGet("ajax/list_offers.php?status=all", null, function(res) {
        allData.history = res.success && res.data ? res.data : [];
        checkDone();
    });
}

function buildApprovalTable(d) {
    selectedCandidates = d.ready;
    pendingOffers = d.pending;
    approvedOffers = d.history;

    var tbody = document.getElementById("approvalTableBody");
    var html = "";

    // Ready candidates
    for (var i = 0; i < d.ready.length; i++) {
        var c = d.ready[i];
        var action = c.status_name === "Selected"
            ? "<button class=\"btn btn-sm btn-success\" onclick=\"openCreateOffer(" + c.id + ")\">Create Offer</button>"
            : "<span class=\"badge badge-in-progress\">Pending</span>";
        html += "<tr>" +
            "<td><strong>" + c.first_name + " " + c.last_name + "</strong></td>" +
            "<td>" + (c.job_title || "N/A") + "</td>" +
            "<td>" + (c.expected_salary ? formatCurrency(c.expected_salary, "\u09F3") : "-") + "</td>" +
            "<td><span class=\"badge badge-success\">Selected</span></td>" +
            "<td>" + formatDateTime(c.applied_at) + "</td>" +
            "<td>-</td>" +
            "<td>" + action + "</td>" +
        "</tr>";
    }

    // Pending offers
    for (var i = 0; i < d.pending.length; i++) {
        var o = d.pending[i];
        var levelHtml = "";
        for (var j = 0; j < o.approvals.length; j++) {
            var a = o.approvals[j];
            var dotClass = a.status === "Approved" ? "approved" : a.status === "Rejected" ? "rejected" : "pending";
            levelHtml += "<div class=\"approval-level\"><div class=\"level-line\"><div class=\"level-dot " + dotClass + "\"></div></div><div class=\"level-label\">L" + a.level + ": " + a.status + "</div></div>";
        }
        if (!levelHtml) levelHtml = "<span class=\"text-muted\">-</span>";
        var action = "<div class=\"btn-group\">" +
            "<button class=\"btn btn-sm btn-success\" onclick=\"openApprovalAction(" + o.id + ")\">Approve</button>" +
            "<button class=\"btn btn-sm btn-danger\" onclick=\"openApprovalAction(" + o.id + ")\" style=\"margin-left:4px\">Reject</button>" +
        "</div>";
        html += "<tr>" +
            "<td><strong>" + o.candidate_name + "</strong></td>" +
            "<td>" + o.job_title + "</td>" +
            "<td>" + formatCurrency(o.salary, "\u09F3") + "</td>" +
            "<td><span class=\"badge " + getBadgeClass(o.status) + "\">" + o.status + "</span></td>" +
            "<td>" + formatDateTime(o.joining_date) + "</td>" +
            "<td>" + levelHtml + "</td>" +
            "<td>" + action + "</td>" +
        "</tr>";
    }

    // History (approved/rejected offers)
    for (var i = 0; i < d.history.length; i++) {
        var o = d.history[i];
        if (o.status === "Pending") continue;
        var action = o.status === "Approved"
            ? "<button class=\"btn btn-sm btn-primary\" onclick=\"openGenerateLetter(" + o.id + ")\">Generate Letter</button>"
            : "-";
        html += "<tr>" +
            "<td><strong>" + o.candidate_name + "</strong></td>" +
            "<td>" + o.job_title + "</td>" +
            "<td>" + formatCurrency(o.salary, "\u09F3") + "</td>" +
            "<td><span class=\"badge " + getBadgeClass(o.status) + "\">" + o.status + "</span></td>" +
            "<td>" + formatDateTime(o.joining_date) + "</td>" +
            "<td>-</td>" +
            "<td>" + action + "</td>" +
        "</tr>";
    }

    if (!html) {
        html = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No offers yet.</p></div></td></tr>";
    }
    tbody.innerHTML = html;
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
    if (!salary || !date || !des) { showAlert("Please fill all required fields", "error"); return; }
    apiPost("ajax/create.php", {
        candidate_id: candidateId, salary: salary, joining_date: date,
        designation_id: des, probation_months: prob || 6, notes: notes
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
    for (var i = 0; i < pendingOffers.length; i++) { if (pendingOffers[i].id === offerId) { o = pendingOffers[i]; break; } }
    if (!o) return;
    document.getElementById("approvalOfferId").value = offerId;
    document.getElementById("approvalCandidateName").value = o.candidate_name;
    var pendingApproval = null;
    for (var j = 0; j < o.approvals.length; j++) { if (o.approvals[j].status === "Pending") { pendingApproval = o.approvals[j]; break; } }
    document.getElementById("approvalId").value = pendingApproval ? pendingApproval.id : "";
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
        offer_id: offerId, approval_id: approvalId, action: action, comment: comment
    }, function(res) {
        showAlert(res.message || "Action completed!", "success");
        closeModal("approvalActionModal");
        renderApprovalTable();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
    });
}

// ============================================================
// Tab 3: Offer Letter
// ============================================================
var letterOffers = [];

function renderLetters() {
    apiGet("ajax/list_offers.php?status=Approved&with_letter=1", null, function(res) {
        letterOffers = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("letterTableBody");
        if (letterOffers.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">📄</div><p>No approved offers pending letter generation.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < letterOffers.length; i++) {
            var o = letterOffers[i];
            var salary = formatCurrency(o.salary, "৳");
            var letterStatus = o.letter_status || "Not Generated";
            var lsClass = letterStatus === "Sent" ? "badge badge-success" : letterStatus === "Generated" ? "badge badge-in-progress" : "badge";
            var action = "";
            if (!o.letter_id) {
                action = "<button class=\"btn btn-sm btn-primary\" onclick=\"openGenerateLetter(" + o.id + ")\">Generate Letter</button>";
            } else if (o.letter_status === "Draft" || o.letter_status === "Generated") {
                action = "<button class=\"btn btn-sm btn-success\" onclick=\"sendOfferEmail(" + o.id + ")\">Send Email</button> <button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
            } else if (o.letter_status === "Sent") {
                action = "<button class=\"btn btn-sm btn-outline\" onclick=\"previewLetter(" + o.id + ")\">View</button> <button class=\"btn btn-sm btn-outline\" onclick=\"sendOfferEmail(" + o.id + ")\">Resend</button>";
            }
            html += "<tr>" +
                "<td><strong>" + o.candidate_name + "</strong></td>" +
                "<td>" + o.job_title + "</td>" +
                "<td>" + salary + "</td>" +
                "<td>" + formatDateTime(o.joining_date) + "</td>" +
                "<td><span class=\"" + lsClass + "\">" + letterStatus + "</span></td>" +
                "<td>" + (o.letter_generated_at ? formatDateTime(o.letter_generated_at) : "-") + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

var letterTemplates = [];

function loadTemplateSelector(offerData) {
    apiGet("../templates/ajax/templates_list.php?type=Offer", null, function(res) {
        letterTemplates = res.success && res.data ? res.data : [];
        var sel = document.getElementById("letterTemplateSelect");
        var currentVal = sel.value;
        sel.innerHTML = "<option value=\"\">-- Select Template --</option>";
        for (var i = 0; i < letterTemplates.length; i++) {
            sel.innerHTML += "<option value=\"" + letterTemplates[i].id + "\">" + letterTemplates[i].name + "</option>";
        }
        if (currentVal) {
            sel.value = currentVal;
        } else if (letterTemplates.length > 0) {
            sel.value = letterTemplates[0].id;
        }
        if (sel.value) { applyTemplate(offerData); }
    });
}

function applyTemplate(offerData) {
    if (!offerData) return;
    var sel = document.getElementById("letterTemplateSelect");
    var templateId = parseInt(sel.value);
    if (!templateId) { document.getElementById("letterContent").value = ""; return; }
    var tpl = null;
    for (var i = 0; i < letterTemplates.length; i++) {
        if (letterTemplates[i].id === templateId) { tpl = letterTemplates[i]; break; }
    }
    if (!tpl) return;
    var content = tpl.content;
    var now = new Date();
    var dateStr = now.toLocaleDateString("en-US", {year:"numeric",month:"long",day:"numeric"});
    var respDate = new Date(now.getTime() + 14 * 24 * 60 * 60 * 1000);
    var respDateStr = respDate.toLocaleDateString("en-US", {year:"numeric",month:"long",day:"numeric"});
    var salary = formatCurrency(offerData.salary, "৳");
    var joinDate = offerData.joining_date ? new Date(offerData.joining_date).toLocaleDateString("en-US", {year:"numeric",month:"long",day:"numeric"}) : "";
    content = content.replace(/\{\{CANDIDATE_NAME\}\}/g, offerData.candidate_name || "");
    content = content.replace(/\{\{DESIGNATION\}\}/g, offerData.designation_name || offerData.job_title || "");
    content = content.replace(/\{\{JOB_TITLE\}\}/g, offerData.job_title || "");
    content = content.replace(/\{\{DEPARTMENT\}\}/g, offerData.dept_name || "");
    content = content.replace(/\{\{SALARY\}\}/g, salary);
    content = content.replace(/\{\{JOINING_DATE\}\}/g, joinDate);
    content = content.replace(/\{\{PROBATION_MONTHS\}\}/g, offerData.probation_months || 6);
    content = content.replace(/\{\{COMPANY_NAME\}\}/g, "TechCorp International");
    content = content.replace(/\{\{HR_NAME\}\}/g, "Admin User");
    content = content.replace(/\{\{DATE\}\}/g, dateStr);
    content = content.replace(/\{\{RESPONSE_DATE\}\}/g, respDateStr);
    document.getElementById("letterContent").value = content;
}

function getSelectedOffer() {
    var offerId = parseInt(document.getElementById("letterOfferId").value);
    var allOffers = approvedOffers.concat(letterOffers);
    for (var i = 0; i < allOffers.length; i++) { if (allOffers[i].id === offerId) return allOffers[i]; }
    return null;
}

function openGenerateLetter(offerId) {
    var o = null;
    var allOffers = approvedOffers.concat(letterOffers);
    for (var i = 0; i < allOffers.length; i++) { if (allOffers[i].id === offerId) { o = allOffers[i]; break; } }
    if (!o) return;
    document.getElementById("letterOfferId").value = offerId;
    document.getElementById("letterCandidateName").value = o.candidate_name || "";
    document.getElementById("letterSalary").value = o.salary || "";
    document.getElementById("letterJoiningDate").value = o.joining_date ? new Date(o.joining_date).toISOString().slice(0,10) : "";
    document.getElementById("letterProbationMonths").value = o.probation_months || 6;
    document.getElementById("letterDesignation").value = o.designation_name || o.job_title || "";
    loadTemplateSelector(o);
    openModal("generateLetterModal");
}

function submitGenerateLetter() {
    var offerId = document.getElementById("letterOfferId").value;
    var content = document.getElementById("letterContent").value;
    if (!content) { showAlert("Please review the letter content", "error"); return; }
    apiPost("ajax/letter_generate.php", {
        offer_id: offerId, letter_content: content
    }, function(res) {
        showAlert(res.message || "Letter generated!", "success");
        closeModal("generateLetterModal");
        renderLetters();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to generate letter", "error");
    });
}

function previewLetter(offerId) {
    apiGet("ajax/letter_view.php?offer_id=" + offerId, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("previewContent").innerHTML = res.data.letter_content || "<p>No content</p>";
            openModal("previewLetterModal");
        } else {
            showAlert("Letter not found", "error");
        }
    });
}

function sendOfferEmail(offerId) {
    var o = null;
    var allOffers = approvedOffers.concat(letterOffers);
    for (var i = 0; i < allOffers.length; i++) { if (allOffers[i].id === offerId) { o = allOffers[i]; break; } }
    if (!o) return;
    document.getElementById("emailOfferId").value = offerId;
    document.getElementById("emailRecipient").value = o.email || "";
    document.getElementById("emailSubject").value = "Offer of Employment - " + (o.candidate_name || "");
    apiGet("ajax/letter_view.php?offer_id=" + offerId, null, function(res) {
        if (res.success && res.data && res.data.letter_content) {
            document.getElementById("emailBody").value = res.data.letter_content;
        } else {
            document.getElementById("emailBody").value = "Dear " + (o.candidate_name || "Candidate") + ",\n\nWe are pleased to offer you the position of " + (o.job_title || "") + ".\n\nPlease find attached the formal offer letter.";
        }
    });
    openModal("sendEmailModal");
}

function submitSendEmail() {
    var offerId = document.getElementById("emailOfferId").value;
    var recipient = document.getElementById("emailRecipient").value;
    var subject = document.getElementById("emailSubject").value;
    var body = document.getElementById("emailBody").value;
    if (!recipient || !subject) { showAlert("Please fill recipient and subject", "error"); return; }
    apiPost("ajax/letter_send.php", {
        offer_id: offerId, recipient: recipient, subject: subject, body: body
    }, function(res) {
        showAlert(res.message || "Email sent!", "success");
        closeModal("sendEmailModal");
        renderLetters();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to send email", "error");
    });
}

// ============================================================
// Tab 4: Response
// ============================================================
var responseOffers = [];

function renderResponses() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=Pending", null, function(res) {
        responseOffers = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("responseTableBody");
        if (responseOffers.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"empty-state\"><div class=\"empty-icon\">📬</div><p>No offers awaiting response.</p></div></td></tr>";
        } else {
        var html = "";
        for (var i = 0; i < responseOffers.length; i++) {
            var o = responseOffers[i];
            var salary = formatCurrency(o.salary, "৳");
            var action = "<button class=\"btn btn-sm btn-success\" onclick=\"openMarkResponse(" + o.id + ", 'Accepted')\">Accept</button> " +
                "<button class=\"btn btn-sm btn-danger\" onclick=\"openMarkResponse(" + o.id + ", 'Rejected')\">Reject</button>";
            html += "<tr>" +
                "<td><strong>" + o.candidate_name + "</strong></td>" +
                "<td>" + o.job_title + "</td>" +
                "<td>" + salary + "</td>" +
                "<td>" + formatDateTime(o.sent_at) + "</td>" +
                "<td><span class=\"badge badge-in-progress\">Awaiting Response</span></td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
        renderResponded();
        }
    });
}

function renderResponded() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=all", null, function(res) {
        var responded = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("respondedTableBody");
        var pending = responded.filter(function(o) { return o.response_status === "Pending"; });
        var done = responded.filter(function(o) { return o.response_status !== "Pending"; });
        if (done.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"5\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No responded offers yet.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < done.length; i++) {
            var o = done[i];
            var badgeCls = o.response_status === "Accepted" ? "badge badge-success" : "badge badge-danger";
            var icon = o.response_status === "Accepted" ? "✅" : "❌";
            html += "<tr>" +
                "<td><strong>" + o.candidate_name + "</strong></td>" +
                "<td>" + o.job_title + "</td>" +
                "<td>" + formatCurrency(o.salary, "৳") + "</td>" +
                "<td><span class=\"" + badgeCls + "\">" + icon + " " + o.response_status + "</span></td>" +
                "<td>" + (o.responded_at ? formatDateTime(o.responded_at) : "-") + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function openMarkResponse(offerId, defaultAction) {
    var o = null;
    for (var i = 0; i < responseOffers.length; i++) { if (responseOffers[i].id === offerId) { o = responseOffers[i]; break; } }
    if (!o) return;
    document.getElementById("respOfferId").value = offerId;
    document.getElementById("respCandidateName").value = o.candidate_name || "";
    document.getElementById("respJobTitle").value = o.job_title || "";
    document.getElementById("respSalary").value = formatCurrency(o.salary, "৳") || "";
    var radios = document.querySelectorAll("input[name=\"responseAction\"]");
    for (var k = 0; k < radios.length; k++) { radios[k].checked = radios[k].value === defaultAction; }
    document.getElementById("respNotes").value = "";
    openModal("markResponseModal");
}

function submitMarkResponse() {
    var offerId = document.getElementById("respOfferId").value;
    var actionEl = document.querySelector("input[name=\"responseAction\"]:checked");
    var action = actionEl ? actionEl.value : "Accepted";
    var notes = document.getElementById("respNotes").value;
    apiPost("ajax/respond.php", {
        offer_id: offerId, response: action, notes: notes
    }, function(res) {
        showAlert(res.message || "Response recorded!", "success");
        closeModal("markResponseModal");
        renderResponses();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to record response", "error");
    });
}

// ============================================================
// Init
// ============================================================
document.addEventListener("DOMContentLoaded", function() {
    renderProposals();
    renderApprovalTable();
    renderLetters();
    renderResponses();
});
EOSCRIPT;

ob_start();
?>
<!-- Tab Bar -->
<div class="tab-bar">
    <button class="tab-btn active" onclick="switchTab('proposalTab',this)">1. Salary Proposal</button>
    <button class="tab-btn" onclick="switchTab('approvalTab',this);renderApprovalTable()">2. Offer Approval</button>
    <button class="tab-btn" onclick="switchTab('letterTab',this)">3. Offer Letter</button>
    <button class="tab-btn" onclick="switchTab('responseTab',this)">4. Response</button>
</div>

<!-- ============================================================ -->
<!-- Tab 1: Salary Proposal -->
<!-- ============================================================ -->
<div id="proposalTab" class="tab-content active">
    <div class="card">
        <div class="card-header">
            <h2>Salary Proposal</h2>
            <div class="filter-bar">
                <div class="search-box">
                    <span class="search-icon">&#128269;</span>
                    <input type="text" id="proposalSearch" placeholder="Search..." onkeyup="filterTable('proposalSearch','proposalTable',1)">
                </div>
            </div>
        </div>
        <div class="card-body" style="padding:0">
            <div class="table-container">
                <table id="proposalTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Expected Salary</th>
                            <th>Proposed Amount</th>
                            <th>Status</th>
                            <th>Version</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="proposalTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- Tab 2: Offer Approval -->
<!-- ============================================================ -->
<div id="approvalTab" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h2>Offer Approval</h2>
            <div class="filter-bar">
                <div class="search-box">
                    <span class="search-icon">&#128269;</span>
                    <input type="text" id="approvalSearch" placeholder="Search..." onkeyup="filterTable('approvalSearch','approvalTable',0)">
                </div>
            </div>
        </div>
        <div class="card-body" style="padding:0">
            <div class="table-container">
                <table id="approvalTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Approval</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="approvalTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- Tab 3: Offer Letter -->
<!-- ============================================================ -->
<div id="letterTab" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h2>Offer Letter Management</h2>
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
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Offered Salary</th>
                            <th>Joining Date</th>
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
</div>

<!-- ============================================================ -->
<!-- Tab 4: Response -->
<!-- ============================================================ -->
<div id="responseTab" class="tab-content">
    <div class="card" style="margin-bottom:24px">
        <div class="card-header">
            <h2>Awaiting Response</h2>
        </div>
        <div class="card-body" style="padding:0">
            <div class="table-container">
                <table id="responseTable">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Offered Salary</th>
                            <th>Sent Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="responseTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
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
                            <th>Salary</th>
                            <th>Response</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="respondedTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODALS -->
<!-- ============================================================ -->

<!-- Create / Revise Proposal Modal -->
<div class="modal-overlay" id="createProposalModal" onclick="if(event.target===this)closeModal('createProposalModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="propTitle">Create Salary Proposal</h3>
            <button class="modal-close" onclick="closeModal('createProposalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createProposalForm">
                <input type="hidden" id="propCandidateId">
                <input type="hidden" id="propProposalId">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="propCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Expected Salary</label>
                    <input type="text" class="form-control" id="propExpectedSalary" disabled>
                </div>
                <div class="form-group">
                    <label>Proposed Salary <span class="required">*</span></label>
                    <input type="number" class="form-control" id="propProposedSalary" placeholder="Enter proposed salary" required>
                </div>
                <div class="form-group">
                    <label>Negotiation Notes</label>
                    <textarea class="form-control" id="propNegotiationNotes" rows="3" placeholder="Any notes on negotiation..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('createProposalModal')">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitCreateProposal()">Save Proposal</button>
        </div>
    </div>
</div>

<!-- Finalize Proposal Modal -->
<div class="modal-overlay" id="finalizeProposalModal" onclick="if(event.target===this)closeModal('finalizeProposalModal')">
    <div class="modal modal-small">
        <div class="modal-header modal-header-navy">
            <h3>Finalize Salary Proposal</h3>
            <button class="modal-close" onclick="closeModal('finalizeProposalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="finalizeProposalForm">
                <input type="hidden" id="finalPropCandidateId">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="finalPropName" disabled>
                </div>
                <div class="form-group">
                    <label>Final Offered Amount <span class="required">*</span></label>
                    <input type="number" class="form-control" id="finalPropAmount" placeholder="Enter final offered salary" required>
                </div>
                <p style="font-size:12px;color:var(--gray-500);margin:0">Finalizing will mark the candidate as ready for offer creation.</p>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('finalizeProposalModal')">Cancel</button>
            <button type="button" class="btn btn-success" onclick="submitFinalizeProposal()">Finalize</button>
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

<!-- Generate Letter Modal -->
<div class="modal-overlay" id="generateLetterModal" onclick="if(event.target===this)closeModal('generateLetterModal')">
    <div class="modal modal-large">
        <div class="modal-header modal-header-navy">
            <h3>Generate Offer Letter</h3>
            <button class="modal-close" onclick="closeModal('generateLetterModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="generateLetterForm">
                <input type="hidden" id="letterOfferId">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                    <div class="form-group">
                        <label>Candidate</label>
                        <input type="text" class="form-control" id="letterCandidateName" disabled>
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <input type="text" class="form-control" id="letterDesignation" disabled>
                    </div>
                    <div class="form-group">
                        <label>Offered Salary</label>
                        <input type="text" class="form-control" id="letterSalary" disabled>
                    </div>
                    <div class="form-group">
                        <label>Joining Date</label>
                        <input type="text" class="form-control" id="letterJoiningDate" disabled>
                    </div>
                    <div class="form-group">
                        <label>Probation Period</label>
                        <input type="text" class="form-control" id="letterProbationMonths" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Template</label>
                    <select class="form-control" id="letterTemplateSelect" onchange="applyTemplate(getSelectedOffer())">
                        <option value="">-- Select Template --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Letter Content <span class="required">*</span></label>
                    <textarea class="form-control" id="letterContent" rows="14" style="font-family:monospace;font-size:13px" placeholder="Select a template above or type your letter content here..."></textarea>
                </div>
                <div style="font-size:12px;color:#888;margin-bottom:8px;padding:8px 12px;background:#f8f9fa;border-radius:6px">
                    <strong>Merge Tags:</strong>
                    {{CANDIDATE_NAME}}, {{DESIGNATION}}, {{DEPARTMENT}}, {{SALARY}}, {{JOINING_DATE}}, {{PROBATION_MONTHS}}, {{COMPANY_NAME}}, {{HR_NAME}}, {{DATE}}, {{RESPONSE_DATE}}
                </div>
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
    <div class="modal modal-large">
        <div class="modal-header modal-header-navy">
            <h3>Send Offer Email</h3>
            <button class="modal-close" onclick="closeModal('sendEmailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="sendEmailForm">
                <input type="hidden" id="emailOfferId">
                <div class="form-group">
                    <label>Recipient Email <span class="required">*</span></label>
                    <input type="email" class="form-control" id="emailRecipient" placeholder="candidate@email.com" required>
                </div>
                <div class="form-group">
                    <label>Subject <span class="required">*</span></label>
                    <input type="text" class="form-control" id="emailSubject" placeholder="Offer of Employment" required>
                </div>
                <div class="form-group">
                    <label>Email Body</label>
                    <textarea class="form-control" id="emailBody" rows="10" style="font-family:monospace;font-size:13px"></textarea>
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
    <div class="modal modal-large">
        <div class="modal-header modal-header-navy">
            <h3>Offer Letter Preview</h3>
            <button class="modal-close" onclick="closeModal('previewLetterModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="previewContent" style="padding:24px;background:#fff;border:1px solid var(--gray-200);border-radius:8px;min-height:300px"></div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('previewLetterModal')">Close</button>
        </div>
    </div>
</div>

<!-- Mark Response Modal -->
<div class="modal-overlay" id="markResponseModal" onclick="if(event.target===this)closeModal('markResponseModal')">
    <div class="modal modal-sticky">
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
                    <label>Position</label>
                    <input type="text" class="form-control" id="respJobTitle" disabled>
                </div>
                <div class="form-group">
                    <label>Offered Salary</label>
                    <input type="text" class="form-control" id="respSalary" disabled>
                </div>
                <div class="form-group">
                    <label>Response <span class="required">*</span></label>
                    <div class="btn-group" style="margin-top:4px">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border:1px solid var(--gray-300);border-radius:6px;background:var(--gray-50)">
                            <input type="radio" name="responseAction" value="Accepted" checked> Accepted ✅
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border:1px solid var(--gray-300);border-radius:6px;background:var(--gray-50)">
                            <input type="radio" name="responseAction" value="Rejected"> Rejected ❌
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Notes / Reason</label>
                    <textarea class="form-control" id="respNotes" rows="3" placeholder="Any notes about the response..."></textarea>
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
