<?php
$basePath    = '..';
$pageTitle   = 'Offer Management';
$activePage  = 'offer_overview';
$breadcrumb  = 'Home / Recruitment / Offer Management';
$pageStyles  = '
.stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:16px; margin-bottom:28px; }
.stat-card { background:#fff; border-radius:10px; padding:20px 24px; box-shadow:0 1px 4px rgba(0,0,0,.06); display:flex; align-items:center; gap:16px; cursor:pointer; transition:all .2s; border:1px solid var(--gray-200); }
.stat-card:hover { border-color:var(--primary); box-shadow:0 2px 8px rgba(37,99,235,.1); transform:translateY(-1px); }
.stat-card .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; }
.stat-card .stat-icon.blue { background:#e8f0fe; color:var(--primary); }
.stat-card .stat-icon.green { background:#e6f4ea; color:var(--success); }
.stat-card .stat-icon.orange { background:#fef3e2; color:#e67e22; }
.stat-card .stat-icon.purple { background:#f0e6ff; color:#7c3aed; }
.stat-card .stat-info h3 { font-size:26px; font-weight:700; line-height:1.2; margin:0; }
.stat-card .stat-info p { font-size:12px; color:var(--gray-500); margin:2px 0 0; }
.quick-links { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:16px; }
.quick-link { display:flex; align-items:center; gap:14px; padding:16px 20px; background:#fff; border:1px solid var(--gray-200); border-radius:8px; text-decoration:none; color:var(--gray-700); transition:all .2s; }
.quick-link:hover { border-color:var(--primary); color:var(--primary); box-shadow:0 1px 4px rgba(37,99,235,.08); }
.quick-link .ql-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.quick-link .ql-text h4 { margin:0; font-size:14px; font-weight:600; }
.quick-link .ql-text p { margin:2px 0 0; font-size:12px; color:var(--gray-500); }
';
$pageScripts = <<<'EOSCRIPT'
function loadStats() {
    apiGet("ajax/list_proposals.php", null, function(r1) {
        var proposals = r1.success && r1.data ? r1.data : [];
        var pendingProps = proposals.filter(function(c) { return c.proposal_status !== "Finalized"; });
        document.getElementById("statProposals").textContent = pendingProps.length;
    });
    apiGet("ajax/list_pending.php", null, function(r2) {
        var pending = r2.success && r2.data ? r2.data : [];
        document.getElementById("statApprovals").textContent = pending.length;
    });
    apiGet("ajax/list_offers.php?status=Approved&with_letter=1", null, function(r3) {
        var letters = r3.success && r3.data ? r3.data : [];
        var pendingLetters = letters.filter(function(o) { return o.letter_status !== "Sent" && o.letter_status !== "Generated"; });
        document.getElementById("statLetters").textContent = pendingLetters.length;
    });
    apiGet("ajax/list_offers.php?status=Approved&response_status=Pending", null, function(r4) {
        var responses = r4.success && r4.data ? r4.data : [];
        document.getElementById("statResponses").textContent = responses.length;
    });
}
document.addEventListener("DOMContentLoaded", loadStats);
EOSCRIPT;
ob_start();
?>
<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <h2>Offer Management Overview</h2>
    </div>
    <div class="card-body">
        <div class="stats-grid">
            <a class="stat-card" href="proposal.php">
                <div class="stat-icon blue">💰</div>
                <div class="stat-info">
                    <h3 id="statProposals">-</h3>
                    <p>Pending Salary Proposals</p>
                </div>
            </a>
            <a class="stat-card" href="approval.php">
                <div class="stat-icon orange">📋</div>
                <div class="stat-info">
                    <h3 id="statApprovals">-</h3>
                    <p>Pending Approvals</p>
                </div>
            </a>
            <a class="stat-card" href="letter.php">
                <div class="stat-icon purple">📄</div>
                <div class="stat-info">
                    <h3 id="statLetters">-</h3>
                    <p>Letters to Generate</p>
                </div>
            </a>
            <a class="stat-card" href="response.php">
                <div class="stat-icon green">📬</div>
                <div class="stat-info">
                    <h3 id="statResponses">-</h3>
                    <p>Awaiting Response</p>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Quick Actions</h2>
    </div>
    <div class="card-body">
        <div class="quick-links">
            <a class="quick-link" href="proposal.php">
                <div class="ql-icon" style="background:#e8f0fe;color:var(--primary)">💰</div>
                <div class="ql-text">
                    <h4>Salary Proposal</h4>
                    <p>Create and finalize salary proposals for selected candidates</p>
                </div>
            </a>
            <a class="quick-link" href="approval.php">
                <div class="ql-icon" style="background:#fef3e2;color:#e67e22">📋</div>
                <div class="ql-text">
                    <h4>Offer Approval</h4>
                    <p>Review and approve/reject pending offers</p>
                </div>
            </a>
            <a class="quick-link" href="letter.php">
                <div class="ql-icon" style="background:#f0e6ff;color:#7c3aed">📄</div>
                <div class="ql-text">
                    <h4>Offer Letter</h4>
                    <p>Generate, preview, and send offer letters via email</p>
                </div>
            </a>
            <a class="quick-link" href="response.php">
                <div class="ql-icon" style="background:#e6f4ea;color:var(--success)">📬</div>
                <div class="ql-text">
                    <h4>Candidate Response</h4>
                    <p>Record candidate accept/reject responses</p>
                </div>
            </a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
