<?php
$basePath    = '.';
$pageTitle   = 'Dashboard';
$activePage  = 'dashboard';
$breadcrumb  = 'Home / Dashboard';

require_once __DIR__ . '/includes/config.php';
$pdo = getDB();

$totalCandidates   = (int)$pdo->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
$totalOffers       = (int)$pdo->query("SELECT COUNT(*) FROM offers")->fetchColumn();
$pendingResponses  = (int)$pdo->query("SELECT COUNT(*) FROM offers WHERE response_status = 'Pending'")->fetchColumn();
$totalRequisitions = (int)$pdo->query("SELECT COUNT(*) FROM requisitions WHERE status = 'Active'")->fetchColumn();
$pendingProbation  = (int)$pdo->query("SELECT COUNT(*) FROM probation_appraisals WHERE status = 'Pending'")->fetchColumn();
$totalEmployees    = (int)$pdo->query("SELECT COUNT(*) FROM personnel_basic_info")->fetchColumn();

$pipeline = [];
$rs = $pdo->query("SELECT cs.name, COUNT(*) cnt FROM candidates c JOIN candidate_statuses cs ON c.status_id = cs.id GROUP BY cs.id ORDER BY cs.sort_order");
foreach ($rs as $r) $pipeline[$r['name']] = (int)$r['cnt'];

$pendingOfferRows = $pdo->query("
    SELECT o.id, c.first_name, c.last_name
    FROM offers o
    JOIN candidates c ON o.candidate_id = c.id
    WHERE o.response_status = 'Pending'
    ORDER BY o.created_at DESC
")->fetchAll();

$pendingProbRows = $pdo->query("
    SELECT p.id, p.end_date, pi.PBI_NAME
    FROM probation_appraisals p
    JOIN personnel_basic_info pi ON p.employee_id = pi.PBI_ID
    WHERE p.status = 'Pending'
    ORDER BY p.end_date ASC
")->fetchAll();

$recentLogs = $pdo->query("
    SELECT cl.action, cl.notes, cl.created_at, c.first_name, c.last_name
    FROM candidate_logs cl
    LEFT JOIN candidates c ON cl.candidate_id = c.id
    ORDER BY cl.id DESC LIMIT 8
")->fetchAll();

ob_start();
?>
<div class="stats-grid">
    <a href="recruitment/list.php" class="stat-card" style="text-decoration:none;display:flex">
        <div class="stat-icon blue">&#x1F3AF;</div>
        <div class="stat-info"><h3><?= $totalRequisitions ?></h3><p>Active Requisitions</p></div>
    </a>
    <a href="candidates/list.php" class="stat-card" style="text-decoration:none;display:flex">
        <div class="stat-icon green">&#x1F465;</div>
        <div class="stat-info"><h3><?= $totalCandidates ?></h3><p>Total Candidates</p></div>
    </a>
    <a href="offer_management/response.php" class="stat-card" style="text-decoration:none;display:flex">
        <div class="stat-icon" style="background:#f3e8fd;color:#9334ea;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px">&#x1F4E8;</div>
        <div class="stat-info"><h3><?= $pendingResponses ?></h3><p>Pending Responses</p></div>
    </a>
    <a href="probation/list.php" class="stat-card" style="text-decoration:none;display:flex">
        <div class="stat-icon yellow">&#x1F4DD;</div>
        <div class="stat-info"><h3><?= $pendingProbation ?></h3><p>Pending Probation</p></div>
    </a>
    <a href="employee/list.php" class="stat-card" style="text-decoration:none;display:flex">
        <div class="stat-icon" style="background:#e6f4ea;color:#1e7e34;width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px">&#x1F464;</div>
        <div class="stat-info"><h3><?= $totalEmployees ?></h3><p>Employees</p></div>
    </a>
</div>

<div class="db-grid">
<div style="min-width:0">

<div class="card mb-24">
    <div class="card-header"><h2>Recruitment Pipeline</h2></div>
    <div class="card-body">
        <div class="db-pipeline">
            <?php $order = ['Applied','Shortlisted','Interview','Selected','Offered'];
            foreach ($order as $stage): $count = $pipeline[$stage]??0; $pct = $totalCandidates ? round($count/$totalCandidates*100) : 0; ?>
            <div style="text-align:center;padding:16px 8px;background:#f8f9fa;border-radius:8px;border:1px solid #e8eaed">
                <div style="font-size:26px;font-weight:700;color:#1a73e8;line-height:1"><?= $count ?></div>
                <div style="height:4px;background:#e8eaed;border-radius:2px;margin:8px 8px 10px;overflow:hidden">
                    <div style="height:100%;background:#1a73e8;border-radius:2px;width:<?= $pct ?>%"></div>
                </div>
                <div style="font-size:10px;font-weight:600;color:#80868b;text-transform:uppercase;letter-spacing:.5px"><?= $stage ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card mb-24">
    <div class="card-header"><h2>Pending Actions</h2></div>
    <div class="card-body" style="padding:0">
        <?php if ($pendingOfferRows || $pendingProbRows): ?>
        <div>
            <?php if ($pendingOfferRows): ?>
            <div style="border-bottom:1px solid #e8eaed">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;background:#f8f9fa;font-size:13px;font-weight:600;color:#5f6368">
                    <span>&#x1F4E8; Pending Offer Responses</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="background:#1a73e8;color:#fff;padding:0 8px;border-radius:10px;font-size:11px;font-weight:600;min-width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center"><?= count($pendingOfferRows) ?></span>
                        <a href="offer_management/response.php" style="font-size:11px;color:#1a73e8;text-decoration:none;font-weight:500">View All &rarr;</a>
                    </div>
                </div>
                <div class="db-table-wrap">
                <table style="width:100%;border-collapse:collapse;min-width:360px">
                    <thead><tr><?php foreach (['Candidate',''] as $h): ?><th style="padding:10px 20px;font-size:11px;font-weight:600;color:#9aa0a6;text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:1px solid #e8eaed;background:#fff"><?= $h ?></th><?php endforeach; ?></tr></thead>
                    <tbody><?php foreach ($pendingOfferRows as $r): ?><tr><td style="padding:10px 20px;font-size:13px;color:#3c4043;border-bottom:1px solid #f1f3f4"><strong><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></strong></td><td style="padding:10px 20px;text-align:right;border-bottom:1px solid #f1f3f4"><a href="offer_management/response.php" style="display:inline-block;padding:4px 12px;font-size:11px;font-weight:500;color:#1a73e8;background:#e8f0fe;border-radius:6px;text-decoration:none">Record</a></td></tr><?php endforeach; ?></tbody>
                </table>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($pendingProbRows): ?>
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;background:#f8f9fa;font-size:13px;font-weight:600;color:#5f6368">
                    <span>&#x1F4DD; Pending Probation Appraisals</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="background:#1a73e8;color:#fff;padding:0 8px;border-radius:10px;font-size:11px;font-weight:600;min-width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center"><?= count($pendingProbRows) ?></span>
                        <a href="probation/list.php" style="font-size:11px;color:#1a73e8;text-decoration:none;font-weight:500">View All &rarr;</a>
                    </div>
                </div>
                <div class="db-table-wrap">
                <table style="width:100%;border-collapse:collapse;min-width:480px">
                    <thead><tr><?php foreach (['Employee','End Date','Days Left',''] as $h): ?><th style="padding:10px 20px;font-size:11px;font-weight:600;color:#9aa0a6;text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:1px solid #e8eaed;background:#fff"><?= $h ?></th><?php endforeach; ?></tr></thead>
                    <tbody><?php foreach ($pendingProbRows as $r): $days = max(0, ceil((strtotime($r['end_date'])-time())/86400)); ?><tr><td style="padding:10px 20px;font-size:13px;color:#3c4043;border-bottom:1px solid #f1f3f4"><strong><?= htmlspecialchars($r['PBI_NAME']) ?></strong></td><td style="padding:10px 20px;font-size:13px;color:#3c4043;border-bottom:1px solid #f1f3f4"><?= date('M j, Y', strtotime($r['end_date'])) ?></td><td style="padding:10px 20px;font-size:13px;color:#3c4043;border-bottom:1px solid #f1f3f4"><?= $days ?> days</td><td style="padding:10px 20px;text-align:right;border-bottom:1px solid #f1f3f4"><a href="probation/list.php" style="display:inline-block;padding:4px 12px;font-size:11px;font-weight:500;color:#1a73e8;background:#e8f0fe;border-radius:6px;text-decoration:none">Review</a></td></tr><?php endforeach; ?></tbody>
                </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div style="padding:40px 20px;text-align:center;font-size:13px;color:#9aa0a6">No pending actions. All caught up!</div>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-24">
    <div class="card-header">
        <h2>Recent Activity</h2>
        <a href="activity/log.php" style="font-size:12px;color:#1a73e8;text-decoration:none;font-weight:500">View All &rarr;</a>
    </div>
    <div class="card-body" style="padding:0">
        <?php if ($recentLogs): ?>
        <div style="padding:12px 16px">
            <?php
            $actCfg = [
                'candidate_applied'       => ['c'=>'#1a73e8','i'=>'&#x1F4E5;','t'=>'Applied'],
                'screening_shortlist'     => ['c'=>'#1e8e3e','i'=>'&#x1F50D;','t'=>'Shortlisted'],
                'screening_reject'        => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Rejected'],
                'screening_hold'          => ['c'=>'#f9ab00','i'=>'&#x23F3;','t'=>'On Hold'],
                'interview_scheduled'     => ['c'=>'#1a73e8','i'=>'&#x1F91D;','t'=>'Scheduled'],
                'interview_pass'          => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Passed'],
                'interview_fail'          => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Failed'],
                'interview_hold'          => ['c'=>'#f9ab00','i'=>'&#x23F3;','t'=>'Hold'],
                'interview_round_pass'    => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Round Passed'],
                'salary_proposed'         => ['c'=>'#9334ea','i'=>'&#x1F4B0;','t'=>'Proposed'],
                'salary_finalized'        => ['c'=>'#9334ea','i'=>'&#x2705;','t'=>'Finalized'],
                'proposal_submitted_approval' => ['c'=>'#f9ab00','i'=>'&#x1F4E8;','t'=>'Pending Approval'],
                'proposal_approved'       => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Approved'],
                'proposal_rejected'       => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Rejected'],
                'offer_created'           => ['c'=>'#1a73e8','i'=>'&#x1F4C4;','t'=>'Created'],
                'offer_approved'          => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Approved'],
                'offer_sent'              => ['c'=>'#1a73e8','i'=>'&#x1F4E8;','t'=>'Sent'],
                'offer_rejected_candidate'=> ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Rejected by Candidate'],
                'offer_letter_generated'  => ['c'=>'#1a73e8','i'=>'&#x1F4C4;','t'=>'Generated'],
                'probation_submitted'     => ['c'=>'#f9ab00','i'=>'&#x1F4DD;','t'=>'Submitted'],
                'probation_updated'       => ['c'=>'#f9ab00','i'=>'&#x1F504;','t'=>'Updated'],
                'probation_approved'      => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Approved'],
                'probation_rejected'      => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Rejected'],
                'probation_completed'     => ['c'=>'#1e8e3e','i'=>'&#x1F389;','t'=>'Completed'],
                'confirmed'               => ['c'=>'#1e8e3e','i'=>'&#x1F389;','t'=>'Confirmed'],
                'employee_created'        => ['c'=>'#1a73e8','i'=>'&#x1F464;','t'=>'Created'],
            ];
            foreach ($recentLogs as $log):
                $cfg = $actCfg[$log['action']] ?? ['c'=>'#9aa0a6','i'=>'&#x2022;','t'=>ucfirst(str_replace('_',' ',$log['action']))];
            ?>
            <div style="display:flex;gap:10px;padding:8px 8px;border-bottom:1px solid #f1f3f4">
                <div style="width:32px;height:32px;border-radius:8px;background:<?=$cfg['c']?>15;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0"><?=$cfg['i']?></div>
                <div style="flex:1;min-width:0;display:flex;align-items:center;justify-content:space-between;gap:8px">
                    <div style="min-width:0">
                        <div style="font-size:12px;font-weight:500;color:#202124"><?=$cfg['t']?></div>
                        <div style="font-size:11px;color:#80868b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:280px"><?=htmlspecialchars($log['notes']??'')?></div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div style="font-size:11px;font-weight:500;color:#5f6368"><?=date('M j',strtotime($log['created_at']))?></div>
                        <div style="font-size:10px;color:#bdc1c6"><?=date('g:i A',strtotime($log['created_at']))?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="padding:40px 20px;text-align:center;font-size:13px;color:#9aa0a6">No recent activity recorded.</div>
        <?php endif; ?>
    </div>
</div>

</div>

<div>
    <div class="card mb-24">
        <div class="card-header"><h2>Quick Actions</h2></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:6px">
            <a href="recruitment/list.php" class="btn btn-outline btn-sm" style="text-align:left">&#x1F3AF; New Requisition</a>
            <a href="candidates/list.php" class="btn btn-outline btn-sm" style="text-align:left">&#x1F465; Manage Candidates</a>
            <a href="offer_management/response.php" class="btn btn-outline btn-sm" style="text-align:left">&#x1F4E8; Record Response</a>
            <a href="probation/list.php" class="btn btn-outline btn-sm" style="text-align:left">&#x1F4DD; Probation Appraisal</a>
            <a href="confirmation/list.php" class="btn btn-outline btn-sm" style="text-align:left">&#x1F389; Confirmation</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2>Pipeline Summary</h2></div>
        <div class="card-body" style="padding:0">
            <div style="padding:12px 20px">
                <?php foreach ($pipeline as $name => $count): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px;border-bottom:1px solid #f1f3f4">
                    <span style="color:#80868b"><?= $name ?></span>
                    <span style="font-weight:600;color:#3c4043"><?= $count ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
