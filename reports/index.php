<?php
$basePath   = '..';
$pageTitle  = 'Reports';
$activePage = 'reports';
$breadcrumb = 'Home / Reports';
$pageCss = 'css/page_css/activity-log.css';

require_once __DIR__ . '/../includes/config.php';
$pdo = getDB();

// === STATS ===
$totalEmployees = (int)$pdo->query("SELECT COUNT(*) FROM personnel_basic_info")->fetchColumn();
$totalProbations = (int)$pdo->query("SELECT COUNT(*) FROM probation_appraisals")->fetchColumn();
$completedProbations = (int)$pdo->query("SELECT COUNT(*) FROM probation_appraisals WHERE status = 'Completed'")->fetchColumn();
$confirmedCount = (int)$pdo->query("SELECT COUNT(*) FROM confirmation_letters WHERE status = 'Approved'")->fetchColumn();
$confirmationRate = $totalProbations ? round($confirmedCount / $totalProbations * 100) : 0;

$dueQuarter = (int)$pdo->query("
    SELECT COUNT(*) FROM probation_appraisals
    WHERE status = 'Pending'
    AND end_date BETWEEN CURDATE() AND DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 3 MONTH)
")->fetchColumn();
$overdue = (int)$pdo->query("
    SELECT COUNT(*) FROM probation_appraisals
    WHERE status = 'Pending' AND end_date < CURDATE()
")->fetchColumn();
$atRisk = (int)$pdo->query("
    SELECT COUNT(*) FROM probation_appraisals
    WHERE status = 'Pending'
    AND end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
")->fetchColumn();

// === PROBATION SUMMARY ===
$probRows = $pdo->query("SELECT status, COUNT(*) cnt FROM probation_appraisals GROUP BY status")->fetchAll();
$probSummary = [];
foreach ($probRows as $r) $probSummary[$r['status']] = (int)$r['cnt'];

$avgScore = $pdo->query("SELECT ROUND(AVG(overall_score),1) FROM probation_appraisals WHERE overall_score IS NOT NULL")->fetchColumn();
$avgScore = $avgScore ? (float)$avgScore : 0;

// === SCORE DISTRIBUTION ===
$scoreRanges = $pdo->query("
    SELECT
        COALESCE(SUM(CASE WHEN overall_score < 2.0 THEN 1 ELSE 0 END),0) as range_0,
        COALESCE(SUM(CASE WHEN overall_score >= 2.0 AND overall_score < 3.0 THEN 1 ELSE 0 END),0) as range_1,
        COALESCE(SUM(CASE WHEN overall_score >= 3.0 AND overall_score < 4.0 THEN 1 ELSE 0 END),0) as range_2,
        COALESCE(SUM(CASE WHEN overall_score >= 4.0 AND overall_score < 4.5 THEN 1 ELSE 0 END),0) as range_3,
        COALESCE(SUM(CASE WHEN overall_score >= 4.5 THEN 1 ELSE 0 END),0) as range_4
    FROM probation_appraisals WHERE overall_score IS NOT NULL
")->fetch(PDO::FETCH_ASSOC);
if (!$scoreRanges) $scoreRanges = ['range_0'=>0,'range_1'=>0,'range_2'=>0,'range_3'=>0,'range_4'=>0];

// === MONTHLY ACTIVITY ===
$monthlyActivity = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total
    FROM candidate_logs
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY ym ORDER BY ym
")->fetchAll();
$monthlyMap = [];
foreach ($monthlyActivity as $m) $monthlyMap[$m['ym']] = (int)$m['total'];
$maxAct = $monthlyMap ? max($monthlyMap) : 1;

// === DEPARTMENT HEADCOUNT ===
$deptHeadcount = $pdo->query("
    SELECT COALESCE(d.name, 'Unassigned') as dept, COUNT(*) cnt
    FROM personnel_basic_info pi
    LEFT JOIN departments d ON pi.DEPT_ID = d.id
    GROUP BY d.name ORDER BY cnt DESC
")->fetchAll();
$deptCounts = array_column($deptHeadcount, 'cnt');
$maxDept = $deptCounts ? max($deptCounts) : 1;

// === RECRUITMENT PIPELINE ===
$pipelineReport = $pdo->query("
    SELECT cs.name, COUNT(*) cnt
    FROM candidates c JOIN candidate_statuses cs ON c.status_id = cs.id
    GROUP BY cs.id, cs.name ORDER BY cs.sort_order
")->fetchAll();
$totalPipeline = array_sum(array_column($pipelineReport, 'cnt'));

// === OFFER REPORT ===
$offerStats = $pdo->query("
    SELECT
        COUNT(*) as total,
        COALESCE(SUM(CASE WHEN response_status = 'Accepted' THEN 1 ELSE 0 END),0) as accepted,
        COALESCE(SUM(CASE WHEN response_status = 'Pending' THEN 1 ELSE 0 END),0) as pending,
        COALESCE(SUM(CASE WHEN response_status = 'Rejected' THEN 1 ELSE 0 END),0) as rejected
    FROM offers
")->fetch(PDO::FETCH_ASSOC);
$acceptRate = $offerStats['total'] ? round($offerStats['accepted'] / $offerStats['total'] * 100) : 0;

// === GENDER DISTRIBUTION ===
$genderDist = $pdo->query("
    SELECT COALESCE(PBI_SEX, 'Not Specified') as gender, COUNT(*) cnt
    FROM personnel_basic_info GROUP BY PBI_SEX
")->fetchAll();

$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

ob_start();
?>
<style>
    .rp-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
    .rp-stat { flex:1; min-width:180px; }
    .rp-bar { height:6px; border-radius:3px; background:#e8eaed; overflow:hidden; }
    .rp-bar-fill { height:100%; border-radius:3px; transition:width .4s ease; }
    .rp-score-dist { display:flex; flex-direction:column; gap:8px; }
    .rp-score-row { display:flex; align-items:center; gap:10px; font-size:13px; }
    .rp-score-label { width:80px; flex-shrink:0; color:#5f6368; }
    .rp-score-bar { flex:1; height:20px; background:#f1f3f4; border-radius:4px; overflow:hidden; }
    .rp-score-fill { height:100%; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#fff; font-weight:600; min-width:24px; }
    .rp-monthly { display:grid; grid-template-columns:repeat(12,1fr); gap:6px; align-items:end; padding:10px 0; }
    .rp-month { text-align:center; }
    .rp-month-bar { width:100%; border-radius:3px 3px 0 0; min-height:4px; margin:0 auto; }
    .rp-month-label { font-size:9px; color:#9aa0a6; margin-top:4px; }
    .rp-month-val { font-size:10px; font-weight:600; color:#5f6368; }
    .rp-table { width:100%; border-collapse:collapse; }
    .rp-table th { padding:10px 16px; font-size:11px; font-weight:600; color:#9aa0a6; text-transform:uppercase; letter-spacing:.5px; text-align:left; border-bottom:1px solid #e8eaed; background:#fafbfc; }
    .rp-table td { padding:10px 16px; font-size:13px; color:#3c4043; border-bottom:1px solid #f1f3f4; }
    .rp-table tr:hover td { background:#f8f9fa; }
    @media (max-width:768px){ .rp-grid { grid-template-columns:1fr; } }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:#202124;margin:0">Reports</h1>
        <p style="font-size:13px;color:#80868b;margin:4px 0 0">Comprehensive HR analytics and insights</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <span style="padding:4px 14px;background:#e8f0fe;border-radius:20px;font-size:12px;color:#1a73e8">&#x1F4C5; <?=date('F Y')?></span>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card rp-stat">
        <div class="stat-icon blue">&#x1F4CA;</div>
        <div class="stat-info"><h3><?=$totalProbations?></h3><p>Total Probation Reports</p></div>
    </div>
    <div class="stat-card rp-stat">
        <div class="stat-icon green">&#x1F4C8;</div>
        <div class="stat-info"><h3><?=$confirmationRate?>%</h3><p>Confirmation Rate</p></div>
    </div>
    <div class="stat-card rp-stat">
        <div class="stat-icon yellow">&#x23F3;</div>
        <div class="stat-info"><h3><?=$dueQuarter?></h3><p>Due This Quarter</p></div>
    </div>
    <div class="stat-card rp-stat">
        <div class="stat-icon red">&#x26A0;&#xFE0F;</div>
        <div class="stat-info"><h3><?=$overdue?></h3><p>Overdue Reviews</p></div>
    </div>
</div>

<div class="rp-grid">

<div class="card">
    <div class="card-header"><h2>&#x1F4DD; Probation Summary</h2></div>
    <div class="card-body">
        <div style="display:flex;flex-direction:column;gap:12px">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#f8f9fa;border-radius:8px;border-left:4px solid #1a73e8">
                <div><strong>Probation Completion Rate</strong><br><span style="font-size:12px;color:#80868b">Completed / Total</span></div>
                <div style="font-size:22px;font-weight:700;color:#1a73e8"><?=$completedProbations?>/<?=$totalProbations?></div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#f8f9fa;border-radius:8px;border-left:4px solid #1e8e3e">
                <div><strong>Confirmed Employees</strong><br><span style="font-size:12px;color:#80868b">Approved confirmation letters</span></div>
                <div style="font-size:22px;font-weight:700;color:#1e8e3e"><?=$confirmedCount?></div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#f8f9fa;border-radius:8px;border-left:4px solid #f9ab00">
                <div><strong>Pending Reviews</strong><br><span style="font-size:12px;color:#80868b">Awaiting appraisal</span></div>
                <div style="font-size:22px;font-weight:700;color:#f9ab00"><?=$probSummary['Pending']??0?></div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#f8f9fa;border-radius:8px;border-left:4px solid #d93025">
                <div><strong>At Risk</strong><br><span style="font-size:12px;color:#80868b">Ending within 30 days, no review</span></div>
                <div style="font-size:22px;font-weight:700;color:#d93025"><?=$atRisk?></div>
            </div>
            <?php if ($avgScore > 0): ?>
            <div style="padding:12px 16px;background:#f3e8fd;border-radius:8px;text-align:center">
                <div style="font-size:11px;color:#7c3aed;font-weight:600">Average Score</div>
                <div style="font-size:28px;font-weight:700;color:#7c3aed"><?=$avgScore?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>&#x1F4CA; Score Distribution</h2></div>
    <div class="card-body">
        <div class="rp-score-dist">
            <?php
            $ranges = [
                ['label'=>'Below 2.0', 'key'=>'range_0', 'color'=>'#d93025'],
                ['label'=>'2.0 - 3.0', 'key'=>'range_1', 'color'=>'#f9ab00'],
                ['label'=>'3.0 - 4.0', 'key'=>'range_2', 'color'=>'#1a73e8'],
                ['label'=>'4.0 - 4.5', 'key'=>'range_3', 'color'=>'#1e8e3e'],
                ['label'=>'4.5 - 5.0', 'key'=>'range_4', 'color'=>'#188038'],
            ];
            $scoreVals = array_map(function($r) use ($scoreRanges) { return (int)($scoreRanges[$r['key']]??0); }, $ranges);
            $maxScore = max(max($scoreVals), 1);
            foreach ($ranges as $i => $r):
                $val = $scoreVals[$i];
                $pct = round($val / $maxScore * 100);
            ?>
            <div class="rp-score-row">
                <span class="rp-score-label"><?=$r['label']?></span>
                <div class="rp-score-bar">
                    <div class="rp-score-fill" style="width:<?=max($pct,5)?>%;background:<?=$r['color']?>"><?=$val?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h2>&#x1F4C8; Recruitment Pipeline</h2></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(120px,1fr));gap:12px">
            <?php
            $pipeColors = ['Applied'=>'#1a73e8','Shortlisted'=>'#1e8e3e','Interview'=>'#f9ab00','Selected'=>'#9334ea','Offered'=>'#188038','Hold'=>'#e37400','Rejected'=>'#d93025'];
            foreach ($pipelineReport as $p):
                $pct = $totalPipeline ? round($p['cnt']/$totalPipeline*100) : 0;
                $c = $pipeColors[$p['name']]??'#9aa0a6';
            ?>
            <div style="text-align:center;padding:16px 8px;background:#f8f9fa;border-radius:8px;border:1px solid #e8eaed">
                <div style="font-size:24px;font-weight:700;color:<?=$c?>"><?=$p['cnt']?></div>
                <div class="rp-bar" style="margin:8px 12px">
                    <div class="rp-bar-fill" style="width:<?=$pct?>%;background:<?=$c?>"></div>
                </div>
                <div style="font-size:10px;font-weight:600;color:#80868b;text-transform:uppercase"><?=$p['name']?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="rp-grid" style="margin-top:24px">

<div class="card">
    <div class="card-header"><h2>&#x1F4B0; Offer Report</h2></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px">
            <div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:8px">
                <div style="font-size:20px;font-weight:700;color:#1a73e8"><?=$offerStats['total']?></div>
                <div style="font-size:10px;color:#80868b;text-transform:uppercase">Total</div>
            </div>
            <div style="text-align:center;padding:12px;background:#e6f4ea;border-radius:8px">
                <div style="font-size:20px;font-weight:700;color:#1e8e3e"><?=$offerStats['accepted']?></div>
                <div style="font-size:10px;color:#1e8e3e;text-transform:uppercase">Accepted</div>
            </div>
            <div style="text-align:center;padding:12px;background:#fef7e0;border-radius:8px">
                <div style="font-size:20px;font-weight:700;color:#e37400"><?=$offerStats['pending']?></div>
                <div style="font-size:10px;color:#e37400;text-transform:uppercase">Pending</div>
            </div>
            <div style="text-align:center;padding:12px;background:#fce8e6;border-radius:8px">
                <div style="font-size:20px;font-weight:700;color:#d93025"><?=$offerStats['rejected']?></div>
                <div style="font-size:10px;color:#d93025;text-transform:uppercase">Rejected</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#e8f0fe;border-radius:8px">
            <span style="font-size:32px">&#x1F4C8;</span>
            <div>
                <div style="font-size:13px;color:#5f6368">Offer Acceptance Rate</div>
                <div style="font-size:24px;font-weight:700;color:#1a73e8"><?=$acceptRate?>%</div>
            </div>
            <div class="rp-bar" style="flex:1;height:10px">
                <div class="rp-bar-fill" style="width:<?=$acceptRate?>%;background:#1a73e8"></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>&#x1F465; Employee Overview</h2></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div>
                <div style="font-size:11px;font-weight:600;color:#9aa0a6;text-transform:uppercase;margin-bottom:8px">Gender</div>
                <?php foreach ($genderDist as $g): ?>
                <div style="display:flex;justify-content:space-between;font-size:13px;padding:4px 0;border-bottom:1px solid #f1f3f4">
                    <span style="color:#5f6368"><?=htmlspecialchars($g['gender'])?></span>
                    <span style="font-weight:600"><?=$g['cnt']?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;color:#9aa0a6;text-transform:uppercase;margin-bottom:8px">Headcount</div>
                <?php $shown = array_slice($deptHeadcount, 0, 5); foreach ($shown as $d): ?>
                <div style="display:flex;justify-content:space-between;font-size:13px;padding:4px 0;border-bottom:1px solid #f1f3f4">
                    <span style="color:#5f6368"><?=htmlspecialchars($d['dept'])?></span>
                    <span style="font-weight:600"><?=$d['cnt']?></span>
                </div>
                <?php endforeach; ?>
                <?php if (count($deptHeadcount) > 5): ?>
                <div style="font-size:11px;color:#9aa0a6;padding-top:4px">+<?=count($deptHeadcount)-5?> more depts</div>
                <?php endif; ?>
                <div style="margin-top:8px;padding:8px 12px;background:#f8f9fa;border-radius:6px;text-align:center">
                    <div style="font-size:20px;font-weight:700;color:#1a73e8"><?=$totalEmployees?></div>
                    <div style="font-size:10px;color:#80868b;text-transform:uppercase">Total Employees</div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h2>&#x1F4C5; Monthly Activity (Last 12 Months)</h2></div>
    <div class="card-body">
        <div class="rp-monthly">
            <?php for ($i = 11; $i >= 0; $i--):
                $ym = date('Y-m', strtotime("-$i months"));
                $val = $monthlyMap[$ym] ?? 0;
                $pct = max($val / $maxAct * 100, 4);
                $label = $months[(int)date('m', strtotime($ym))-1];
            ?>
            <div class="rp-month">
                <div class="rp-month-val"><?=$val?></div>
                <div class="rp-month-bar" style="height:<?=$pct?>px;background:<?=$val>0?'#1a73e8':'#e8eaed'?>"></div>
                <div class="rp-month-label"><?=$label?></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h2>&#x1F4CB; Department Headcount</h2></div>
    <div class="card-body" style="padding:0">
        <table class="rp-table">
            <thead><tr><th>Department</th><th>Employees</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($deptHeadcount as $d):
                    $barPct = $d['cnt'] ? round($d['cnt']/$maxDept*100) : 0;
                ?>
                <tr>
                    <td><strong><?=htmlspecialchars($d['dept'])?></strong></td>
                    <td style="font-weight:600"><?=$d['cnt']?></td>
                    <td style="width:40%"><div class="rp-bar"><div class="rp-bar-fill" style="width:<?=$barPct?>%;background:#1a73e8"></div></div></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top:24px">
    <div class="card-header"><h2>&#x1F4E5; Export Reports</h2></div>
    <div class="card-body">
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <button class="btn btn-primary" onclick="alert('PDF export coming soon')" style="display:flex;align-items:center;gap:8px;padding:10px 20px">&#x1F4C4; Export Probation Summary (PDF)</button>
            <button class="btn btn-success" onclick="alert('Excel export coming soon')" style="display:flex;align-items:center;gap:8px;padding:10px 20px">&#x1F4CA; Export Score Analysis (Excel)</button>
            <button class="btn btn-warning" onclick="alert('CSV export coming soon')" style="display:flex;align-items:center;gap:8px;padding:10px 20px">&#x1F4CB; Export Full Report (CSV)</button>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
