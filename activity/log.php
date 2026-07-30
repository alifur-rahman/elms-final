<?php
$basePath    = '..';
$pageTitle   = 'Activity Log';
$activePage  = 'activity_log';
$breadcrumb  = 'Home / Activity Log';
$pageCss = 'css/page_css/activity-log.css';

require_once __DIR__ . '/../includes/config.php';
$pdo = getDB();

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = max(1, (int)($_GET['perPage'] ?? 10));
if (!in_array($perPage, [10, 20, 50, 100])) $perPage = 10;
$actionFilter = $_GET['action'] ?? '';
$search = trim($_GET['search'] ?? '');
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$monthFilter = $_GET['month'] ?? '';
$yearFilter = $_GET['year'] ?? '';

$where = [];
$params = [];

if ($actionFilter) {
    $where[] = 'cl.action = :action';
    $params[':action'] = $actionFilter;
}
if ($search) {
    $where[] = '(cl.notes LIKE :search OR CONCAT(c.first_name," ",c.last_name) LIKE :fullname OR c.first_name LIKE :s2 OR c.last_name LIKE :s3 OR cl.created_by LIKE :s4 OR cl.action LIKE :s5)';
    $params[':search'] = "%$search%";
    $params[':fullname'] = "%$search%";
    $params[':s2'] = "%$search%";
    $params[':s3'] = "%$search%";
    $params[':s4'] = "%$search%";
    $params[':s5'] = "%$search%";
}
if ($dateFrom) {
    $where[] = 'DATE(cl.created_at) >= :date_from';
    $params[':date_from'] = $dateFrom;
}
if ($dateTo) {
    $where[] = 'DATE(cl.created_at) <= :date_to';
    $params[':date_to'] = $dateTo;
}
if ($monthFilter && $yearFilter) {
    $where[] = 'MONTH(cl.created_at) = :month AND YEAR(cl.created_at) = :year';
    $params[':month'] = (int)$monthFilter;
    $params[':year'] = (int)$yearFilter;
} elseif ($yearFilter) {
    $where[] = 'YEAR(cl.created_at) = :year';
    $params[':year'] = (int)$yearFilter;
}
$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM candidate_logs cl LEFT JOIN candidates c ON cl.candidate_id = c.id $whereClause");
$totalStmt->execute($params);
$total = (int)$totalStmt->fetchColumn();
$totalPages = max(1, ceil($total / $perPage));
$offset = ($page - 1) * $perPage;

$logs = $pdo->prepare("
    SELECT cl.*, c.first_name, c.last_name
    FROM candidate_logs cl
    LEFT JOIN candidates c ON cl.candidate_id = c.id
    $whereClause
    ORDER BY cl.id DESC
    LIMIT $perPage OFFSET $offset
");
$logs->execute($params);
$logs = $logs->fetchAll();

$allActions = $pdo->query("SELECT DISTINCT action FROM candidate_logs ORDER BY action")->fetchAll(PDO::FETCH_COLUMN);
$years = $pdo->query("SELECT DISTINCT YEAR(created_at) AS y FROM candidate_logs ORDER BY y DESC")->fetchAll(PDO::FETCH_COLUMN);

$actCfg = [
    'candidate_applied'       => ['c'=>'#1a73e8','i'=>'&#x1F4E5;','t'=>'Applied'],
    'screening_shortlist'     => ['c'=>'#1e8e3e','i'=>'&#x1F50D;','t'=>'Shortlisted'],
    'screening_reject'        => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Rejected'],
    'screening_hold'          => ['c'=>'#f9ab00','i'=>'&#x23F3;','t'=>'Hold'],
    'interview_scheduled'     => ['c'=>'#1a73e8','i'=>'&#x1F91D;','t'=>'Scheduled'],
    'interview_pass'          => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Passed'],
    'interview_fail'          => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Failed'],
    'interview_hold'          => ['c'=>'#f9ab00','i'=>'&#x23F3;','t'=>'Hold'],
    'interview_round_pass'    => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Round Passed'],
    'salary_proposed'         => ['c'=>'#9334ea','i'=>'&#x1F4B0;','t'=>'Salary Proposed'],
    'salary_finalized'        => ['c'=>'#9334ea','i'=>'&#x2705;','t'=>'Salary Finalized'],
    'proposal_submitted_approval' => ['c'=>'#f9ab00','i'=>'&#x1F4E8;','t'=>'Submitted for Approval'],
    'proposal_approved'       => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Proposal Approved'],
    'proposal_rejected'       => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Proposal Rejected'],
    'offer_created'           => ['c'=>'#1a73e8','i'=>'&#x1F4C4;','t'=>'Offer Created'],
    'offer_approved'          => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Offer Approved'],
    'offer_sent'              => ['c'=>'#1a73e8','i'=>'&#x1F4E8;','t'=>'Offer Sent'],
    'offer_rejected_candidate'=> ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Rejected by Candidate'],
    'offer_letter_generated'  => ['c'=>'#1a73e8','i'=>'&#x1F4C4;','t'=>'Letter Generated'],
    'probation_submitted'     => ['c'=>'#f9ab00','i'=>'&#x1F4DD;','t'=>'Probation Submitted'],
    'probation_updated'       => ['c'=>'#f9ab00','i'=>'&#x1F504;','t'=>'Probation Updated'],
    'probation_approved'      => ['c'=>'#1e8e3e','i'=>'&#x2705;','t'=>'Probation Approved'],
    'probation_rejected'      => ['c'=>'#d93025','i'=>'&#x274C;','t'=>'Probation Rejected'],
    'probation_completed'     => ['c'=>'#1e8e3e','i'=>'&#x1F389;','t'=>'Probation Completed'],
    'confirmed'               => ['c'=>'#1e8e3e','i'=>'&#x1F389;','t'=>'Confirmed'],
    'employee_created'        => ['c'=>'#1a73e8','i'=>'&#x1F464;','t'=>'Employee Created'],
];

$months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

function qs($exclude = []) {
    $p = $_GET;
    foreach ($exclude as $k) unset($p[$k]);
    return http_build_query($p);
}
function hasFilters() {
    return !empty($_GET['action']) || !empty($_GET['search']) || !empty($_GET['date_from']) || !empty($_GET['date_to']) || !empty($_GET['month']) || !empty($_GET['year']);
}

ob_start();
?>
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:#202124;margin:0">Activity Log</h1>
        <p style="font-size:13px;color:#80868b;margin:4px 0 0">Track all actions across the employee lifecycle</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
        <span style="display:flex;align-items:center;gap:4px;padding:4px 12px;background:#f8f9fa;border-radius:20px;font-size:12px;color:#5f6368">&#x1F4CB; <strong><?=$total?></strong> entries</span>
        <?php if (hasFilters()): ?>
        <a href="log.php" style="padding:4px 12px;border:1px solid #dadce0;border-radius:20px;font-size:11px;color:#5f6368;text-decoration:none;background:#fff">&#x2716; Clear All</a>
        <?php endif; ?>
    </div>
</div>

<?php if (hasFilters()): ?>
<div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px">
    <?php if ($actionFilter): ?>
    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#e8f0fe;border-radius:16px;font-size:11px;color:#1a73e8;font-weight:500">Action: <?=htmlspecialchars(ucfirst(str_replace('_',' ',$actionFilter)))?> <a href="?<?=qs(['action','page'])?>" style="text-decoration:none;color:#1a73e8;font-weight:700;margin-left:2px">&times;</a></span>
    <?php endif; ?>
    <?php if ($search): ?>
    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#f1f3f4;border-radius:16px;font-size:11px;color:#5f6368;font-weight:500">Search: "<?=htmlspecialchars($search)?>" <a href="?<?=qs(['search','page'])?>" style="text-decoration:none;color:#5f6368;font-weight:700;margin-left:2px">&times;</a></span>
    <?php endif; ?>
    <?php if ($dateFrom || $dateTo): ?>
    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#fef7e0;border-radius:16px;font-size:11px;color:#e37400;font-weight:500">Date: <?=$dateFrom?:'...'?> &rarr; <?=$dateTo?:'...'?> <a href="?<?=qs(['date_from','date_to','page'])?>" style="text-decoration:none;color:#e37400;font-weight:700;margin-left:2px">&times;</a></span>
    <?php endif; ?>
    <?php if ($monthFilter && $yearFilter): ?>
    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#f3e8fd;border-radius:16px;font-size:11px;color:#7c3aed;font-weight:500"><?=$months[(int)$monthFilter]?> <?=$yearFilter?> <a href="?<?=qs(['month','year','page'])?>" style="text-decoration:none;color:#7c3aed;font-weight:700;margin-left:2px">&times;</a></span>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="al-card">
    <div class="al-toolbar">
        <form method="GET" action="log.php">
            <div class="al-filter-row">
                <div class="al-filter-group">
                    <select name="action" class="al-select">
                        <option value="">All Actions</option>
                        <?php foreach ($allActions as $a): ?>
                        <option value="<?=htmlspecialchars($a)?>" <?=$a===$actionFilter?'selected':''?>><?=htmlspecialchars(ucfirst(str_replace('_',' ',$a)))?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="al-search-wrap">
                        <span class="al-search-icon">&#x1F50D;</span>
                        <input type="text" name="search" class="al-input" placeholder="Search anything..." value="<?=htmlspecialchars($search)?>" autocomplete="off">
                        <?php if ($search): ?>
                        <a href="?<?=qs(['search','page'])?>" class="al-search-clear">&times;</a>
                        <?php endif; ?>
                    </div>

                    <select name="month" class="al-select" style="min-width:120px">
                        <option value="">All Months</option>
                        <?php foreach ($months as $num => $name): ?>
                        <option value="<?=$num?>" <?=$monthFilter===(string)$num?'selected':''?>><?=$name?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="year" class="al-select" style="min-width:90px">
                        <option value="">All Years</option>
                        <?php foreach ($years as $y): ?>
                        <option value="<?=$y?>" <?=$yearFilter===(string)$y?'selected':''?>><?=$y?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="al-filter-actions">
                    <div class="al-date-range">
                        <input type="date" name="date_from" class="al-date" value="<?=htmlspecialchars($dateFrom)?>" title="From date">
                        <span style="color:#9aa0a6;font-size:12px">&rarr;</span>
                        <input type="date" name="date_to" class="al-date" value="<?=htmlspecialchars($dateTo)?>" title="To date">
                    </div>
                    <button type="submit" class="al-filter-btn">&#x1F50D; Filter</button>
                </div>
            </div>

            <div class="al-presets">
                <span style="font-size:11px;color:#9aa0a6;margin-right:4px">Quick:</span>
                <a href="?<?=qs(['date_from','date_to','month','year','page'])?>&date_from=<?=date('Y-m-d')?>&date_to=<?=date('Y-m-d')?>" class="al-preset <?=$dateFrom===date('Y-m-d')&&$dateTo===date('Y-m-d')&&!$monthFilter?'al-preset-active':''?>">Today</a>
                <a href="?<?=qs(['date_from','date_to','month','year','page'])?>&date_from=<?=date('Y-m-d',strtotime('monday this week'))?>&date_to=<?=date('Y-m-d',strtotime('sunday this week'))?>" class="al-preset">This Week</a>
                <a href="?<?=qs(['date_from','date_to','month','year','page'])?>&month=<?=date('n')?>&year=<?=date('Y')?>" class="al-preset <?=$monthFilter===date('n')&&$yearFilter===date('Y')?'al-preset-active':''?>">This Month</a>
                <a href="?<?=qs(['date_from','date_to','month','year','page'])?>&year=<?=date('Y')?>" class="al-preset <?=$yearFilter===date('Y')&&!$monthFilter?'al-preset-active':''?>">This Year</a>
            </div>
        </form>
    </div>

    <?php if ($logs): ?>
    <div class="al-list">
        <?php foreach ($logs as $log):
            $name = trim(($log['first_name']??'').' '.($log['last_name']??''));
            $cfg = $actCfg[$log['action']] ?? ['c'=>'#9aa0a6','i'=>'&#x2022;','t'=>ucfirst(str_replace('_',' ',$log['action']))];
        ?>
        <div class="al-row">
            <div class="al-dot-wrap">
                <div class="al-dot" style="background:<?=$cfg['c']?>"></div>
            </div>
            <div class="al-icon-box" style="background:<?=$cfg['c']?>12">
                <span><?=$cfg['i']?></span>
            </div>
            <div class="al-body">
                <div class="al-top">
                    <span class="al-action" style="color:<?=$cfg['c']?>"><?=$cfg['t']?></span>
                    <span class="al-time"><?=date('M j, Y',strtotime($log['created_at']))?> at <?=date('g:i A',strtotime($log['created_at']))?></span>
                </div>
                <div class="al-notes"><?=htmlspecialchars($log['notes']??'')?></div>
                <div class="al-meta">
                    <?php if ($name): ?>
                    <span class="al-tag al-tag-person">&#x1F464; <?=htmlspecialchars($name)?></span>
                    <?php endif; ?>
                    <span class="al-tag al-tag-user">&#x1F464; <?=htmlspecialchars($log['created_by']??'System')?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="al-pagination">
        <div class="al-pg-info">Page <?=$page?> of <?=$totalPages?> &middot; <?=$total?> entries</div>
        <div class="al-pg-center">
            <a href="?<?=qs(['page'])?>&page=1" class="al-pg-btn <?=$page<=1?'al-pg-disabled':''?>">&laquo;</a>
            <a href="?<?=qs(['page'])?>&page=<?=max(1,$page-1)?>" class="al-pg-btn <?=$page<=1?'al-pg-disabled':''?>">&lsaquo;</a>
            <?php for ($p=max(1,$page-2); $p<=min($totalPages,$page+2); $p++): ?>
            <a href="?<?=qs(['page'])?>&page=<?=$p?>" class="al-pg-btn <?=$p===$page?'al-pg-active':''?>"><?=$p?></a>
            <?php endfor; ?>
            <a href="?<?=qs(['page'])?>&page=<?=min($totalPages,$page+1)?>" class="al-pg-btn <?=$page>=$totalPages?'al-pg-disabled':''?>">&rsaquo;</a>
            <a href="?<?=qs(['page'])?>&page=<?=$totalPages?>" class="al-pg-btn <?=$page>=$totalPages?'al-pg-disabled':''?>">&raquo;</a>
        </div>
        <div class="al-pg-rows">
            Show <select onchange="location.href='?<?=qs(['page','perPage'])?>&perPage='+this.value">
                <?php foreach ([10,20,50,100] as $n): ?>
                <option value="<?=$n?>" <?=$n===$perPage?'selected':''?>><?=$n?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="al-empty">
        <div style="font-size:48px;margin-bottom:12px">&#x1F4ED;</div>
        <h3>No Activity Found</h3>
        <p>No activity logs match your current filters. Try adjusting your search or clearing the filters.</p>
        <?php if (hasFilters()): ?>
        <a href="log.php" class="btn btn-primary btn-sm" style="margin-top:12px">Clear All Filters</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
