<?php
$activePage = $activePage ?? 'dashboard';

$sidebarItems = [
    'Lifecycle' => [
        ['id' => 'dashboard', 'text' => 'Dashboard', 'href' => 'dashboard.php'],
        ['id' => 'lifecycle', 'text' => 'Lifecycle Timeline', 'href' => 'lifecycle/view.php'],
    ],
    'Recruitment' => [
        ['id' => 'recruitment',    'text' => 'Pipeline',         'href' => 'recruitment/list.php',          'badge' => 3],
        ['id' => 'candidates',     'text' => 'Candidates',       'href' => 'candidates/list.php',           'badge' => 2],
        ['id' => 'screening',      'text' => 'Screening',        'href' => 'screening/list.php',            'badge' => 1],
        ['id' => 'interview',      'text' => 'Interview',        'href' => 'interview/list.php',            'badge' => 2],
        ['id' => 'offer_management', 'text' => 'Offer Management', 'href' => 'offer_management/list.php', 'badge' => 1],
    ],
    'Onboarding' => [
        ['id' => 'pre_onboarding', 'text' => 'Pre-Onboarding',   'href' => 'pre_onboarding/list.html',      'badge' => 1],
        ['id' => 'joining',        'text' => 'Joining',          'href' => 'joining/list.html',             'badge' => 0],
    ],
    'Probation' => [
        ['id' => 'probation',         'text' => 'Probation Appraisal', 'href' => 'probation/list.html',     'badge' => 1],
        ['id' => 'probation_approve', 'text' => 'Pending Approval',    'href' => 'probation/approve.html',  'badge' => 1],
    ],
    'Confirmation' => [
        ['id' => 'confirmation',          'text' => 'Confirmation',    'href' => 'confirmation/list.html'],
        ['id' => 'confirmation_generate', 'text' => 'Generate Letter', 'href' => 'confirmation/generate.html'],
    ],
    'Employee' => [
        ['id' => 'employee_master', 'text' => 'Employee Master', 'href' => 'employee_master/list.html'],
    ],
    'Settings' => [
        ['id' => 'templates', 'text' => 'Letter Templates', 'href' => 'templates/list.php'],
        ['id' => 'reports',   'text' => 'Reports',          'href' => 'reports/probation_summary.html'],
    ],
];
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo">ERP</div>
        <div>
            <span><?= APP_SHORT ?></span>
            <small>Employee Lifecycle</small>
        </div>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($sidebarItems as $label => $items): ?>
        <div class="nav-label"><?= $label ?></div>
        <?php foreach ($items as $item): ?>
        <a href="<?= ($basePath ?? '.') ?>/<?= $item['href'] ?>" class="<?= isActive($item['id'], $activePage) ?>">
            <span class="nav-dot"></span>
            <span><?= $item['text'] ?></span>
            <?php if (!empty($item['badge']) && $item['badge'] > 0): ?>
            <span class="badge"><?= $item['badge'] ?></span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer">
        <a href="#"><span class="nav-dot"></span><span>Settings</span></a>
        <a href="#"><span class="nav-dot"></span><span>Logout</span></a>
    </div>
</aside>
