<?php
$basePath    = '..';
$pageTitle   = 'Confirmation Letters';
$activePage  = 'confirmation_letter';
$breadcrumb  = 'Home / Confirmation / Confirmation Letters';
$pageCss = 'css/page_css/confirmation-letters.css';
$pageJs = 'js/page_js/confirmation-letters.js';;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-total">
        <div class="cc-num" id="cntTotal">0</div>
        <div class="cc-label">Total Letters</div>
    </div>
    <div class="cc-card cc-generated">
        <div class="cc-num" id="cntGenerated">0</div>
        <div class="cc-label">Generated</div>
    </div>
    <div class="cc-card cc-approved">
        <div class="cc-num" id="cntApproved">0</div>
        <div class="cc-label">Approved</div>
    </div>
    <div class="cc-card cc-sent">
        <div class="cc-num" id="cntSent">0</div>
        <div class="cc-label">Sent</div>
    </div>
</div>

<div class="rp-top">
    <h1>Confirmation Letters <small>Manage and send confirmation letters</small></h1>
    <div class="rp-search">
        <span class="rp-search-icon">&#128269;</span>
        <input type="text" id="searchInput" placeholder="Search employee, department..." onkeyup="pgPage=1;renderView()">
    </div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" data-tab="all" onclick="switchTab('all')">All <span class="scr-tab-count" data-count="all">0</span></button>
    <button class="scr-tab" data-tab="Generated" onclick="switchTab('Generated')">Generated <span class="scr-tab-count" data-count="Generated">0</span></button>
    <button class="scr-tab" data-tab="Approved" onclick="switchTab('Approved')">Approved <span class="scr-tab-count" data-count="Approved">0</span></button>
    <button class="scr-tab" data-tab="Sent" onclick="switchTab('Sent')">Sent <span class="scr-tab-count" data-count="Sent">0</span></button>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table">
            <thead><tr>
                <th class="rp-sortable" onclick="sortBy('employee')">Employee <span class="rp-sort-arrow" id="listSortArrow0" data-col="employee">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('job')">Job Title <span class="rp-sort-arrow" id="listSortArrow1" data-col="job">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('dept')">Department <span class="rp-sort-arrow" id="listSortArrow2" data-col="dept">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('type')">Type <span class="rp-sort-arrow" id="listSortArrow3" data-col="type">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('salary')">Salary <span class="rp-sort-arrow" id="listSortArrow4" data-col="salary">&#8597;</span></th>
                <th class="rp-sortable" onclick="sortBy('status')">Status <span class="rp-sort-arrow" id="listSortArrow5" data-col="status">&#8597;</span></th>
                <th>Action</th>
            </tr></thead>
            <tbody id="letterTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div class="rp-pagination" id="pgBar">
        <div class="rp-pg-info" id="pgInfo">Showing 0-0 of 0</div>
        <div class="rp-pg-center" id="pgCenter"></div>
        <div class="rp-pg-rows" id="pgRowsInfo"></div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal-overlay" id="previewLetterModal" onclick="if(event.target===this)closeModal('previewLetterModal')" style="display:none">
    <div class="modal modal-sticky preview-wide">
        <div class="modal-header modal-header-navy">
            <h3>Preview Confirmation Letter</h3>
            <button class="modal-close" onclick="closeModal('previewLetterModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="previewContent" class="preview-content-wrap"></div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('previewLetterModal')">Close</button>
        </div>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal-overlay" id="sendEmailModal" onclick="if(event.target===this)closeModal('sendEmailModal')" style="display:none">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Send Confirmation Letter</h3>
            <button class="modal-close" onclick="closeModal('sendEmailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="sendEmailForm">
                <input type="hidden" id="sendLetterId">
                <div class="form-group">
                    <label>Employee</label>
                    <input type="text" class="form-control" id="sendCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Recipient Email <span class="required">*</span></label>
                    <input type="email" class="form-control" id="sendEmail" placeholder="recipient@example.com" required>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" class="form-control" id="sendSubject">
                </div>
                <div class="form-group">
                    <label>Additional Message</label>
                    <textarea class="form-control" id="sendBody" rows="4" placeholder="Optional message..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('sendEmailModal')">Cancel</button>
            <button type="button" class="btn btn-success" onclick="submitSendEmail()">Send Email</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
