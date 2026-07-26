<?php
$basePath    = '..';
$pageTitle   = 'Candidate Response';
$activePage  = 'offer_response';
$breadcrumb  = 'Home / Recruitment / Offer Management / Candidate Response';
$pageCss = 'css/page_css/response.css';
$pageJs = 'js/page_js/response.js';;
ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-pending"><div><div class="cc-num" id="ccPending">0</div><div class="cc-label">Awaiting Response</div></div></div>
    <div class="cc-card cc-accepted"><div><div class="cc-num" id="ccAccepted">0</div><div class="cc-label">Accepted</div></div></div>
    <div class="cc-card cc-rejected"><div><div class="cc-num" id="ccRejected">0</div><div class="cc-label">Rejected</div></div></div>
</div>

<div class="scr-tabs">
    <button class="scr-tab active" id="tabBtnpending" onclick="switchTab('pending')">Pending <span class="scr-tab-count" id="tabCountPending">0</span></button>
    <button class="scr-tab" id="tabBtnresponded" onclick="switchTab('responded')">Responded <span class="scr-tab-count" id="tabCountResponded">0</span></button>
</div>

<div class="rp-top">
    <h1 id="tableTitle">Awaiting Response</h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchResponse" placeholder="Search by candidate or job..." oninput="applyFilter()" autocomplete="off">
            <button class="rp-search-clear" id="searchResponseClear" onclick="clearSearch()">&times;</button>
        </div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table">
            <thead><tr id="tableHeadRow"></tr></thead>
            <tbody id="responseTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="responseMobileList"></div>
    <div id="paginationResponse" class="rp-pagination"></div>
</div>

<!-- Mark Response Modal -->
<div class="modal-overlay" id="markResponseModal" onclick="if(event.target===this)closeModal('markResponseModal')">
    <div class="modal modal-small">
        <div class="modal-header modal-header-navy">
            <h3>Record Candidate Response</h3>
            <button class="modal-close" onclick="closeModal('markResponseModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="markResponseForm">
                <input type="hidden" id="respOfferId">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="respCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Response <span class="required">*</span></label>
                    <div style="display:flex;gap:16px;padding:8px 0">
                        <label style="display:flex;align-items:center;gap:6px">
                            <input type="radio" name="respStatus" value="Accepted"> Accepted
                        </label>
                        <label style="display:flex;align-items:center;gap:6px">
                            <input type="radio" name="respStatus" value="Rejected"> Rejected
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" id="respNotes" rows="3" placeholder="Any notes..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('markResponseModal')">Cancel</button>
            <button class="btn btn-primary" onclick="submitMarkResponse()">Save Response</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
