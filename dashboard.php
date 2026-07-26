<?php
$basePath    = '.';
$pageTitle   = 'Dashboard';
$activePage  = 'dashboard';
$breadcrumb  = 'Home / Dashboard';
$pageCss = 'css/page_css/dashboard.css';
$pageJs = 'js/page_js/dashboard.js';

ob_start();
?>
<div class="stats-grid" id="statsGrid">
    <div class="stat-card">
        <div class="stat-icon yellow">&#x1F4DD;</div>
        <div class="stat-info"><h3 id="statPending">0</h3><p>Pending Appraisals</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#x2705;</div>
        <div class="stat-info"><h3 id="statCompleted">0</h3><p>Completed</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">&#x1F4C5;</div>
        <div class="stat-info"><h3 id="statDueMonth">0</h3><p>Due This Month</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">&#x1F389;</div>
        <div class="stat-info"><h3 id="statConfirm">0</h3><p>Pending Confirmation</p></div>
    </div>
</div>

<div class="dashboard-layout">
    <div class="dashboard-main">

        <div class="card mb-24">
            <div class="card-header">
                <h2>Pending Probation Appraisals</h2>
                <div class="filter-bar">
                    <div class="search-box">
                        <span class="search-icon">&#x1F50D;</span>
                        <input type="text" id="searchProbation" placeholder="Search..." onkeyup="filterTable('searchProbation','probationTable')">
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding:0">
                <div class="table-container">
                    <table id="probationTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Joining Date</th>
                                <th>Probation End</th>
                                <th>Days Remaining</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="probationBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-24">
            <div class="card-header"><h2>Recent Activity</h2></div>
            <div class="card-body">
                <div class="timeline" id="recentTimeline"></div>
            </div>
        </div>

        <div class="card mb-24">
            <div class="card-header"><h2>Lifecycle Flow</h2></div>
            <div class="card-body">
                <div class="lifecycle-cards">
                    <a href="recruitment/list.html" class="lifecycle-card"><div class="lc-count" id="lcRecruitment">0</div><div class="lc-icon">&#x1F3AF;</div><div class="lc-label">Recruitment</div></a>
                    <a href="candidates/list.html" class="lifecycle-card"><div class="lc-count" id="lcCandidates">0</div><div class="lc-icon">&#x1F465;</div><div class="lc-label">Candidates</div></a>
                    <a href="screening/list.html" class="lifecycle-card"><div class="lc-count" id="lcScreening">0</div><div class="lc-icon">&#x1F50D;</div><div class="lc-label">Screening</div></a>
                    <a href="interview/list.html" class="lifecycle-card"><div class="lc-count" id="lcInterview">0</div><div class="lc-icon">&#x1F91D;</div><div class="lc-label">Interview</div></a>
                    <a href="offer_management/list.php" class="lifecycle-card"><div class="lc-count" id="lcOffer">0</div><div class="lc-icon">&#x2705;</div><div class="lc-label">Offer</div></a>
                    <a href="pre_onboarding/list.html" class="lifecycle-card"><div class="lc-count" id="lcPreOnboard">0</div><div class="lc-icon">&#x1F4CB;</div><div class="lc-label">Pre-Onboard</div></a>
                    <a href="probation/list.html" class="lifecycle-card"><div class="lc-count" id="lcProbation">0</div><div class="lc-icon">&#x1F4DD;</div><div class="lc-label">Probation</div></a>
                    <a href="confirmation/list.html" class="lifecycle-card"><div class="lc-count" id="lcConfirm">0</div><div class="lc-icon">&#x1F389;</div><div class="lc-label">Confirmation</div></a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2>Automatic Reminder Schedule</h2></div>
            <div class="card-body">
                <div class="reminder-grid">
                    <div class="reminder-card"><div class="rc-days">90</div><div class="rc-label">Days Before</div><div class="rc-action">Probation start reminder</div></div>
                    <div class="reminder-card"><div class="rc-days">60</div><div class="rc-label">Days Before</div><div class="rc-action">Mid-probation check-in</div></div>
                    <div class="reminder-card"><div class="rc-days">30</div><div class="rc-label">Days Before</div><div class="rc-action">Appraisal preparation</div></div>
                    <div class="reminder-card"><div class="rc-days">15</div><div class="rc-label">Days Before</div><div class="rc-action">Manager notification</div></div>
                    <div class="reminder-card"><div class="rc-days">7</div><div class="rc-label">Days Before</div><div class="rc-action">Urgent reminder</div></div>
                    <div class="reminder-card"><div class="rc-days">1</div><div class="rc-label">Day Before</div><div class="rc-action">Deadline alert</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-sidebar">
        <div class="dashboard-sidebar-section">
            <h3>Upcoming Confirmations</h3>
            <div id="upcomingConfirmations"></div>
        </div>
        <div class="dashboard-sidebar-section">
            <h3>Quick Actions</h3>
            <div style="display:flex;flex-direction:column;gap:8px">
                <button class="btn btn-primary btn-sm" onclick="window.location='recruitment/list.html'">Create Requisition</button>
                <button class="btn btn-outline btn-sm" onclick="window.location='probation/list.html'">Probation Appraisal</button>
                <button class="btn btn-outline btn-sm" onclick="window.location='confirmation/list.html'">Generate Letter</button>
            </div>
        </div>
        <div class="dashboard-sidebar-section">
            <h3>System Info</h3>
            <div style="font-size:12px;color:var(--gray-600)">
                <div style="display:flex;justify-content:space-between;padding:4px 0"><span>Version</span><span style="font-weight:500">2.0.0</span></div>
                <div style="display:flex;justify-content:space-between;padding:4px 0"><span>Last Sync</span><span style="font-weight:500">Today 09:30</span></div>
                <div style="display:flex;justify-content:space-between;padding:4px 0"><span>Employees</span><span style="font-weight:500" id="sysEmpCount">0</span></div>
                <div style="display:flex;justify-content:space-between;padding:4px 0;border:none"><span>Open Positions</span><span style="font-weight:500" id="sysJobCount">0</span></div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
