<?php
$basePath   = '..';
$pageTitle  = 'Probation Appraisal';
$activePage = 'probation';
$breadcrumb = 'Home / Probation / Probation Appraisal';
$pageCss = 'css/page_css/probation-list.css';
$pageJs = 'js/page_js/probation-list.js';
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-active"><div><div class="cc-num" id="ccActive">0</div><div class="cc-label">Active Reviews</div></div></div>
    <div class="cc-card cc-completed"><div><div class="cc-num" id="ccCompleted">0</div><div class="cc-label">Completed</div></div></div>
    <div class="cc-card cc-extended"><div><div class="cc-num" id="ccExtended">0</div><div class="cc-label">Extended</div></div></div>
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Confirm Pending</div></div></div>
</div>

<div class="rp-top">
    <h1>Probation Appraisals <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <select class="form-control" id="deptFilter" onchange="applyFilters()" style="width:180px">
            <option value="0">All Departments</option>
        </select>
        <select class="form-control" id="statusFilter" onchange="applyFilters()" style="width:160px">
            <option value="">All Status</option>
            <option value="Draft">Draft</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchInput" placeholder="Search employees..." oninput="applyFilters()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
        <a class="btn btn-primary" href="new.php">+ New Appraisal</a>
    </div>
</div>

<div class="scr-tabs">
    <button class="scr-tab" id="tabBtnappraisals" onclick="switchTab('appraisals')">Appraisals <span class="scr-tab-count" id="tabCountAppraisals">0</span></button>
    <button class="scr-tab active" id="tabBtneligible" onclick="switchTab('eligible')">Eligible Employees <span class="scr-tab-count" id="tabCountEligible">0</span></button>
</div>

<div class="tab-panel" id="tabappraisals">
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Employee <span class="rp-sort-arrow" id="appraisalsSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Department <span class="rp-sort-arrow" id="appraisalsSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Joining Date <span class="rp-sort-arrow" id="appraisalsSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Probation End <span class="rp-sort-arrow" id="appraisalsSortArrow3"></span></th>
                        <th class="rp-sortable" onclick="sortBy(4)">Days Left <span class="rp-sort-arrow" id="appraisalsSortArrow4"></span></th>
                        <th class="rp-sortable" onclick="sortBy(5)">Score <span class="rp-sort-arrow" id="appraisalsSortArrow5"></span></th>
                        <th class="rp-sortable" onclick="sortBy(6)">Status <span class="rp-sort-arrow" id="appraisalsSortArrow6"></span></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="appraisalsTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="appraisalsMobileList"></div>
        <div id="appraisalsPagination" class="rp-pagination"></div>
    </div>
</div>

<div class="tab-panel active" id="tabeligible">
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Employee <span class="rp-sort-arrow" id="eligibleSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Designation <span class="rp-sort-arrow" id="eligibleSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Joining Date <span class="rp-sort-arrow" id="eligibleSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Days Left <span class="rp-sort-arrow" id="eligibleSortArrow3"></span></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="eligibleTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="eligibleMobileList"></div>
        <div id="eligiblePagination" class="rp-pagination"></div>
    </div>
</div>

<!-- View Detail Modal -->
<div class="modal-overlay" id="viewDetailModal" onclick="if(event.target===this)closeModal('viewDetailModal')" style="display:none">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Appraisal Details - <span id="mdTitleName">Employee</span></h3>
            <button class="modal-close" onclick="closeModal('viewDetailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon blue">&#x1F4CB;</span> Appraisal Information</div>
                <div class="info-grid">
                    <div class="info-item"><label>Joining Date</label><span id="mdJoining">-</span></div>
                    <div class="info-item"><label>Probation End</label><span id="mdEnd">-</span></div>
                    <div class="info-item"><label>Probation Period</label><span id="mdProbation">-</span></div>
                    <div class="info-item"><label>Days Left</label><span id="mdDaysLeft">-</span></div>
                    <div class="info-item"><label>Department</label><span id="mdDept">-</span></div>
                    <div class="info-item"><label>Designation</label><span id="mdDesignation">-</span></div>
                    <div class="info-item"><label>Status</label><span id="mdStatus" class="badge">-</span></div>
                    <div class="info-item"><label>Recommendation</label><span id="mdRecommendation">-</span></div>
                </div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon green">&#x1F4CA;</span> Overall Score</div>
                <div class="score-meter-bar">
                    <div class="score-bar-track">
                        <div class="score-bar-fill" id="mdScoreBar" style="width:0%"></div>
                    </div>
                    <span class="score-bar-label" id="mdScoreLabel">0 / 5</span>
                </div>
                <div class="score-labels"><span>Poor</span><span>Average</span><span>Good</span><span>Excellent</span></div>
                <div style="text-align:right;font-size:28px;font-weight:700;color:var(--primary)" id="mdScoreVal">0.00</div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon orange">&#x1F4DD;</span> Category Scores</div>
                <table class="rp-table">
                    <thead><tr><th>Category</th><th>Weight</th><th>Score</th><th>Comments</th></tr></thead>
                    <tbody id="mdScoresBody"></tbody>
                </table>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon purple">&#x1F504;</span> Workflow Progress</div>
                <div class="wf-container" id="mdWorkflow"></div>
            </div>

            <div class="sb-section">
                <div class="sb-section-title"><span class="sb-icon teal">&#x1F4AC;</span> Comments & Reviews</div>
                <div class="comment-section">
                    <label>Supervisor Comments</label>
                    <div class="comment-box" id="mdSupervisorCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>Department Head Comments</label>
                    <div class="comment-box" id="mdDeptCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>HR Review</label>
                    <div class="comment-box" id="mdHRCmt">No comments</div>
                </div>
                <div class="comment-section">
                    <label>Overall Remarks</label>
                    <div class="comment-box" id="mdRemarks">No remarks</div>
                </div>
            </div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn" onclick="closeModal('viewDetailModal')">Close</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
