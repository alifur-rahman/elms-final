<?php
$basePath   = '..';
$pageTitle  = 'Confirmation - Eligible Employees';
$activePage = 'confirmation_list';
$breadcrumb = 'Home / Confirmation / Eligible Employees';
$pageCss = 'css/page_css/confirmation-list.css';
$pageJs = 'js/page_js/confirmation-list.js';;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-total">
        <div class="cc-num" id="cntTotal">0</div>
        <div class="cc-label">Total Eligible</div>
    </div>
    <div class="cc-card cc-pending">
        <div class="cc-num" id="cntPending">0</div>
        <div class="cc-label">Pending</div>
    </div>
    <div class="cc-card cc-generated">
        <div class="cc-num" id="cntGenerated">0</div>
        <div class="cc-label">Generated</div>
    </div>
    <div class="cc-card cc-sent">
        <div class="cc-num" id="cntSent">0</div>
        <div class="cc-label">Sent</div>
    </div>
</div>

<div class="rp-top">
    <h1>Eligible Employees <small>Probation completed, awaiting confirmation</small></h1>
    <div class="rp-search">
        <span class="rp-search-icon">&#128269;</span>
        <input type="text" id="searchInput" placeholder="Search employee, department..." onkeyup="pgPage=1;renderView()">
    </div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" data-tab="all" onclick="switchTab('all')">All <span class="scr-tab-count" data-count="all">0</span></button>
    <button class="scr-tab" data-tab="pending" onclick="switchTab('pending')">Pending <span class="scr-tab-count" data-count="pending">0</span></button>
    <button class="scr-tab" data-tab="generated" onclick="switchTab('generated')">Generated <span class="scr-tab-count" data-count="generated">0</span></button>
    <button class="scr-tab" data-tab="sent" onclick="switchTab('sent')">Sent <span class="scr-tab-count" data-count="sent">0</span></button>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table">
            <thead><tr>
                <th class="rp-sortable" onclick="sortBy('employee')">Employee <span class="rp-sort-arrow" id="listSortArrow0" data-col="employee">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('job')">Job Title <span class="rp-sort-arrow" id="listSortArrow1" data-col="job">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('dept')">Department <span class="rp-sort-arrow" id="listSortArrow2" data-col="dept">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('designation')">Designation <span class="rp-sort-arrow" id="listSortArrow3" data-col="designation">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('type')">Type <span class="rp-sort-arrow" id="listSortArrow4" data-col="type">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('end')">Probation End <span class="rp-sort-arrow" id="listSortArrow5" data-col="end">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('score')">Score <span class="rp-sort-arrow" id="listSortArrow6" data-col="score">&#8597;</span></th>
                <th>Action</th>
            </tr></thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div class="rp-pagination" id="pgBar">
        <div class="rp-pg-info" id="pgInfo">Showing 0-0 of 0</div>
        <div class="rp-pg-center" id="pgCenter"></div>
        <div class="rp-pg-rows" id="pgRowsInfo"></div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
