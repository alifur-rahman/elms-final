<?php
$basePath    = '..';
$pageTitle   = 'Offer Approval';
$activePage  = 'offer_approval';
$breadcrumb  = 'Home / Recruitment / Offer Management / Offer Approval';
$pageCss = 'css/page_css/approval.css';
$pageJs = 'js/page_js/approval.js';;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Offer Approval</h2>
        <button class="btn btn-primary btn-sm" onclick="openCreateOffer()">+ Create Offer</button>
    </div>
    <div style="padding:0 16px">
        <div class="tabs">
            <div class="tab active" id="tabPending" onclick="switchTab('pending')">Pending Approval</div>
            <div class="tab" id="tabReady" onclick="switchTab('ready')">Ready for Letter</div>
            <div class="tab" id="tabHistory" onclick="switchTab('history')">History</div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="approvalTable">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Offered Salary</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="approvalTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Offer Modal -->
<div class="modal-overlay" id="createOfferModal" onclick="if(event.target===this)closeModal('createOfferModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Create Offer</h3>
            <button class="modal-close" onclick="closeModal('createOfferModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createOfferForm">
                <div class="form-group">
                    <label>Candidate <span class="required">*</span></label>
                    <select class="form-control" id="createOfferCandidate"></select>
                </div>
                <div class="form-group">
                    <label>Offered Salary <span class="required">*</span></label>
                    <input type="number" class="form-control" id="createOfferSalary" placeholder="Enter offered salary">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" id="createOfferNotes" rows="3" placeholder="Additional notes..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('createOfferModal')">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitCreateOffer()">Create Offer</button>
        </div>
    </div>
</div>

<!-- Approval Action Modal -->
<div class="modal-overlay" id="approvalActionModal" onclick="if(event.target===this)closeModal('approvalActionModal')">
    <div class="modal modal-small">
        <div class="modal-header modal-header-navy">
            <h3>Review Offer</h3>
            <button class="modal-close" onclick="closeModal('approvalActionModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="approvalActionForm">
                <input type="hidden" id="approvalOfferId">
                <div class="form-group">
                    <label>Candidate</label>
                    <input type="text" class="form-control" id="approvalCandidateName" disabled>
                </div>
                <div class="form-group">
                    <label>Salary</label>
                    <input type="text" class="form-control" id="approvalSalary" disabled>
                </div>
                <div class="form-group">
                    <label>Comments</label>
                    <textarea class="form-control" id="approvalComments" rows="3" placeholder="Approval/rejection comments..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button type="button" class="btn btn-outline" onclick="closeModal('approvalActionModal')">Cancel</button>
            <button type="button" class="btn btn-success" data-action="Approved">Approve</button>
            <button type="button" class="btn btn-danger" data-action="Rejected">Reject</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
