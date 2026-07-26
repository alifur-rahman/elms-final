<?php
$basePath   = '..';
$pageTitle  = 'Approval Management';
$activePage = 'probation_approve';
$breadcrumb = 'Home / Probation / Approval Management';
$pageCss = 'css/page_css/approve.css';
$pageJs = 'js/page_js/approve.js';;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-active">
        <div class="cc-num" id="statPending">0</div>
        <div class="cc-label">Pending Approval</div>
    </div>
    <div class="cc-card cc-completed">
        <div class="cc-num" id="statApproved">0</div>
        <div class="cc-label">Completed</div>
    </div>
    <div class="cc-card cc-urgent">
        <div class="cc-num" id="statUrgent">0</div>
        <div class="cc-label">Urgent (&lt; 7 days)</div>
    </div>
</div>

<div class="rp-top">
    <h1>Approval Queue <small>Probation appraisal reviews</small></h1>
    <div class="rp-actions">
        <select class="rp-filter-select" id="levelFilter" onchange="pgPage=1;renderApprovals()">
            <option value="">All Levels</option>
            <option value="Supervisor">Supervisor</option>
            <option value="Dept Head">Dept Head</option>
            <option value="HR Review">HR Review</option>
            <option value="GM">GM</option>
            <option value="Managing Director">Managing Director</option>
        </select>
        <select class="rp-filter-select" id="deptFilter" onchange="pgPage=1;renderApprovals()"><option value="">All Departments</option></select>
        <div class="rp-search">
            <span class="rp-search-icon">&#128269;</span>
            <input type="text" id="searchInput" placeholder="Search employee..." onkeyup="pgPage=1;renderApprovals()">
        </div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="approvalTable">
            <thead><tr>
                <th class="rp-sortable" onclick="sortBy('employee')">Employee <span class="rp-sort-arrow" id="listSortArrow0" data-col="employee">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('department')">Department <span class="rp-sort-arrow" id="listSortArrow1" data-col="department">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('score')">Score <span class="rp-sort-arrow" id="listSortArrow2" data-col="score">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('level')">Current Level <span class="rp-sort-arrow" id="listSortArrow3" data-col="level">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('status')">Status <span class="rp-sort-arrow" id="listSortArrow4" data-col="status">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('days')">Days Left <span class="rp-sort-arrow" id="listSortArrow5" data-col="days">&#8597;</span></th>
                <th>Action</th>
            </tr></thead>
            <tbody id="approvalBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div class="rp-pagination">
        <div class="rp-pg-info" id="pgInfo">Showing 0-0 of 0</div>
        <div class="rp-pg-center" id="pgCenter"></div>
        <div class="rp-pg-rows" id="pgRowsInfo"></div>
    </div>
</div>

<!-- Approval Action Modal -->
<div class="modal-overlay" id="approvalModal" onclick="if(event.target===this)closeModal('approvalModal')" style="display:none">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Approval Action</h3>
            <button class="modal-close" onclick="closeModal('approvalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="actProbationId">

            <div id="actProfileCard" class="profile-card"></div>
            <div id="actLevelChain" class="level-chain"></div>

            <div class="accordion-section">
                <div class="accordion-header open" onclick="toggleAccordion(this)">
                    &#128100; Candidate Profile
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body open">
                    <h4 style="font-size:12px;color:var(--gray-500);margin:0 0 8px;text-transform:uppercase;letter-spacing:.5px">Personal Information</h4>
                    <div id="actPersonalInfo" class="candidate-info-grid"></div>
                    <hr class="section-divider">
                    <h4 style="font-size:12px;color:var(--gray-500);margin:0 0 8px;text-transform:uppercase;letter-spacing:.5px">Job &amp; Offer Details</h4>
                    <div id="actJobOfferInfo" class="candidate-info-grid"></div>
                </div>
            </div>

            <div class="accordion-section">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    &#128269; Screening Details
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body">
                    <div id="actScreeningInfo"></div>
                </div>
            </div>

            <div class="accordion-section">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    &#128172; Interview Rounds
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body">
                    <div id="actInterviewInfo"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128221; Appraisal Summary</h2></div>
                <div class="card-body" style="font-size:13px">
                    <div id="actSummary"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128202; Section Scores</h2></div>
                <div class="card-body">
                    <div id="actScoreCards" class="score-card-grid"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128220; Approval History</h2></div>
                <div class="card-body">
                    <div id="actHistory" class="approval-timeline"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Your Decision <span class="required">*</span></label>
                <div style="display:flex;gap:16px;margin-top:8px;flex-wrap:wrap">
                    <label class="decision-option" id="actApproveLabel" onclick="selectDecision('Approved')" style="border-color:var(--success);background:#e6f4ea">
                        <input type="radio" name="actDecision" value="Approved" checked>
                        <span style="font-weight:600;color:var(--success)">&#10004; Approve</span>
                    </label>
                    <label class="decision-option" id="actSendBackLabel" onclick="selectDecision('Send Back')">
                        <input type="radio" name="actDecision" value="Send Back">
                        <span style="font-weight:600;color:var(--warning)">&#8635; Send Back</span>
                    </label>
                    <label class="decision-option" id="actRejectLabel" onclick="selectDecision('Rejected')">
                        <input type="radio" name="actDecision" value="Rejected">
                        <span style="font-weight:600;color:var(--danger)">&#10008; Reject</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Comments <span class="required">*</span></label>
                <textarea id="actComments" class="form-control textarea-full" placeholder="Enter comments for this approval decision..."></textarea>
            </div>

            <div class="audit-warning mb-16">
                <span class="aw-icon">&#9888;</span>
                <div><strong>Audit Trail:</strong> All decisions are logged with timestamp and user identity. This action will be recorded in the employee's permanent lifecycle audit trail and cannot be undone.</div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px">
                <button class="btn btn-outline" onclick="closeModal('approvalModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitApproval()">Submit Decision</button>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
