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
    ],
    'Offer Management' => [
        ['id' => 'offer_overview',              'text' => 'Overview',             'href' => 'offer_management/index.php'],
        ['id' => 'offer_proposal',              'text' => 'Salary Proposal',      'href' => 'offer_management/proposal.php'],
        ['id' => 'offer_proposal_approval',     'text' => 'Proposal Approval',    'href' => 'offer_management/proposal_approval.php'],
        ['id' => 'offer_letter',                'text' => 'Offer Letter',         'href' => 'offer_management/letter.php'],
        ['id' => 'offer_response',              'text' => 'Response',             'href' => 'offer_management/response.php'],
    ],
    'Probation' => [
        ['id' => 'probation',         'text' => 'Probation Appraisal', 'href' => 'probation/list.php',     'badge' => 1],
        ['id' => 'probation_approve', 'text' => 'Pending Approval',    'href' => 'probation/approve.php',  'badge' => 1],
    ],
    'Confirmation' => [
        ['id' => 'confirmation',          'text' => 'Confirmation',    'href' => 'confirmation/list.php',          'badge' => 1],
        ['id' => 'confirmation_approve',  'text' => 'Pending Approval','href' => 'confirmation/approve.php',  'badge' => 1],
    ],
    'Employee' => [
        ['id' => 'employee_master', 'text' => 'Employee Master', 'href' => 'employee_master/list.html'],
    ],
    'Template Management' => [
        ['id' => 'templates',      'text' => 'Templates',       'href' => 'templates/list.php'],
        ['id' => 'template_tags',  'text' => 'Registered Tags', 'href' => 'templates/tags.php'],
    ],
    'Settings' => [
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
