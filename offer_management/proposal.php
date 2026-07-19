<?php
$basePath    = '..';
$pageTitle   = 'Salary Proposal';
$activePage  = 'offer_proposal';
$breadcrumb  = 'Home / Recruitment / Offer Management / Salary Proposal';
$pageStyles  = '
.empty-state { text-align: center; padding: 48px 24px; color: var(--gray-500); }
.empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; }
.empty-state p { font-size: 14px; }
';
$pageScripts = <<<'EOSCRIPT'
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
            var statusClass = proposalStatus === "Finalized" ? "badge badge-success" : proposalStatus === "Pending_Approval" ? "badge badge-in-progress" : proposalStatus === "Proposed" ? "badge badge-in-progress" : "badge";
            var action = "";
            if (proposalStatus === "None" || proposalStatus === "Draft") {
                action = "<a class=\"btn btn-sm btn-primary\" href=\"salary_breakdown.php?candidate_id=" + c.id + "\">Propose Salary</a>";
            } else if (proposalStatus === "Proposed") {
                action = "<button class=\"btn btn-sm btn-outline\" onclick=\"submitForApproval(" + c.id + ")\">Submit for Approval</button> <a class=\"btn btn-sm btn-outline\" href=\"salary_breakdown.php?candidate_id=" + c.id + "&proposal_id=" + (c.proposal_id || "") + "\">Revise</a>";
            } else if (proposalStatus === "Pending_Approval") {
                action = "<span class=\"badge badge-in-progress\">Pending Approval</span>";
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

function submitForApproval(candidateId) {
    if (!confirm("Submit this proposal for approval?")) return;
    apiPost("ajax/proposal_submit.php", {
        candidate_id: candidateId
    }, function(res) {
        showAlert(res.message || "Proposal submitted for approval!", "success");
        renderProposals();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to submit", "error");
    });
}

document.addEventListener("DOMContentLoaded", renderProposals);
EOSCRIPT;
ob_start();
?>
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
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
