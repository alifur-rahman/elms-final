<?php
$basePath    = '..';
$pageTitle   = 'Employee Management';
$activePage  = 'create_employee';
$breadcrumb  = 'Home / Employee / Employee Management';
$pageCss = 'css/page_css/employee-list.css';
$pageJs = 'js/page_js/employee-list.js';;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-total"><div><div class="cc-num" id="ccTotal">0</div><div class="cc-label">Total Accepted Offers</div></div></div>
    <div class="cc-card cc-created"><div><div class="cc-num" id="ccCreated">0</div><div class="cc-label">Employees Created</div></div></div>
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Pending Creation</div></div></div>
</div>

<div class="rp-top">
    <h1>Employee Management <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchInput" placeholder="Search by name, job, department..." oninput="applyFilter()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="empTable">
            <thead>
                <tr>
                    <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>
                    <th class="rp-sortable" onclick="sortBy(1)">Job Title <span class="rp-sort-arrow" id="sortArrow1"></span></th>
                    <th class="rp-sortable" onclick="sortBy(2)">Department <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Designation <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Salary <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th class="rp-sortable" onclick="sortBy(5)">Status <span class="rp-sort-arrow" id="sortArrow5"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="empTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div id="paginationBar" class="rp-pagination"></div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
