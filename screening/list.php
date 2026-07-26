<?php
$basePath    = '..';
$pageTitle   = 'Screening';
$activePage  = 'screening';
$breadcrumb  = 'Home / Recruitment / Screening';
$pageCss = 'css/page_css/screening-list.css';
$pageJs = 'js/page_js/screening-list.js';

ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Pending</div></div></div>
    <div class="cc-card cc-shortlisted"><div><div class="cc-num" id="ccShortlisted">0</div><div class="cc-label">Shortlisted</div></div></div>
    <div class="cc-card cc-rejected"><div><div class="cc-num" id="ccRejected">0</div><div class="cc-label">Rejected</div></div></div>
    <div class="cc-card cc-hold"><div><div class="cc-num" id="ccHold">0</div><div class="cc-label">On Hold</div></div></div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" id="tabBtnUpcoming" onclick="switchTab('Upcoming')">Upcoming <span class="scr-tab-count" id="tabCountUpcoming">0</span></button>
    <button class="scr-tab" id="tabBtnComplete" onclick="switchTab('Complete')">Complete <span class="scr-tab-count" id="tabCountComplete">0</span></button>
</div>

<div class="scr-tab-content active" id="tabUpcoming">
    <div class="rp-top">
        <h1>Upcoming Screenings</h1>
        <div class="rp-actions">
            <div class="rp-search">
                <span class="rp-search-icon">&#x1F50D;</span>
                <input type="text" id="searchUpcoming" placeholder="Search by candidate or job..." oninput="applyUpcomingFilter()" autocomplete="off">
                <button class="rp-search-clear" id="searchUpcomingClear" onclick="clearUpcomingSearch()">&times;</button>
            </div>
        </div>
    </div>
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table" id="upcomingTable">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="upcomingSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="upcomingSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Applied Date <span class="rp-sort-arrow" id="upcomingSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Status <span class="rp-sort-arrow" id="upcomingSortArrow3"></span></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="upcomingTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="upcomingMobileList"></div>
        <div id="paginationUpcoming" class="rp-pagination"></div>
    </div>
</div>

<div class="scr-tab-content" id="tabComplete">
    <div class="rp-top">
        <h1>Completed Screenings</h1>
        <div class="rp-actions">
            <div class="rp-search">
                <span class="rp-search-icon">&#x1F50D;</span>
                <input type="text" id="searchComplete" placeholder="Search by candidate or job..." oninput="applyCompleteFilter()" autocomplete="off">
                <button class="rp-search-clear" id="searchCompleteClear" onclick="clearCompleteSearch()">&times;</button>
            </div>
        </div>
    </div>
    <div class="rp-card">
        <div class="rp-table-wrap">
            <table class="rp-table" id="completeTable">
                <thead>
                    <tr>
                        <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="completeSortArrow0"></span></th>
                        <th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="completeSortArrow1"></span></th>
                        <th class="rp-sortable" onclick="sortBy(2)">Applied Date <span class="rp-sort-arrow" id="completeSortArrow2"></span></th>
                        <th class="rp-sortable" onclick="sortBy(3)">Result <span class="rp-sort-arrow" id="completeSortArrow3"></span></th>
                        <th class="rp-sortable" onclick="sortBy(4)">Screened On <span class="rp-sort-arrow" id="completeSortArrow4"></span></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="completeTableBody"></tbody>
            </table>
        </div>
        <div class="rp-mobile-list" id="completeMobileList"></div>
        <div id="paginationComplete" class="rp-pagination"></div>
    </div>
</div>

<!-- Screening Form Modal -->
<div class="modal-overlay" id="screeningModal" onclick="if(event.target===this)closeModal('screeningModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="screeningModalTitle">Screen Candidate</h3>
            <button class="modal-close" onclick="closeModal('screeningModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="screeningForm" onsubmit="event.preventDefault();window.ajaxSaveScreening()">
                <input type="hidden" id="scCandidateId">
                <input type="hidden" id="scScreeningId">

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Rating (1-5) <span class="required">*</span></label>
                        <input type="number" class="form-control" id="scRating" min="1" max="5" step="0.5" required>
                    </div>
                    <div class="form-group">
                        <label>Communication Score (1-5) <span class="required">*</span></label>
                        <input type="number" class="form-control" id="scCommunication" min="1" max="5" step="0.5" required>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Experience Match <span class="required">*</span></label>
                        <select class="form-control" id="scExperience" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Skill Match <span class="required">*</span></label>
                        <select class="form-control" id="scSkill" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Education Match <span class="required">*</span></label>
                        <select class="form-control" id="scEducation" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Result <span class="required">*</span></label>
                        <select class="form-control" id="scResult" required>
                            <option value="">Select</option>
                            <option value="Shortlist">Shortlist</option>
                            <option value="Reject">Reject</option>
                            <option value="Hold">Hold</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Overall Remarks</label>
                    <textarea class="form-control" id="scRemarks" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('screeningModal')">Cancel</button>
            <button class="btn btn-primary" id="scSubmitBtn" onclick="window.ajaxSaveScreening()">Save Screening</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
