<?php
$basePath    = '..';
$pageTitle   = 'Candidates';
$activePage  = 'candidates';
$breadcrumb  = 'Home / Recruitment / Candidates';
$pageCss = 'css/page_css/candidates-list.css';
$pageJs = 'js/page_js/candidates-list.js';

ob_start();
?>
<div class="cc-grid" id="statsGrid">
    <div class="cc-card cc-total"><div><div class="cc-num">0</div><div class="cc-label">Loading...</div></div></div>
</div>

<div class="rp-top">
    <h1>All Candidates <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <div class="rp-filter">
            <select id="deptFilter" onchange="applyFilters()">
                <option value="">All Departments</option>
            </select>
        </div>
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchInput" placeholder="Search by name, code, email, job..." oninput="applyFilters()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
        <button class="btn btn-primary" onclick="resetCandidateForm();openModal('addCandidateModal')">+ Add Candidate</button>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="candidateTable">
            <thead>
                <tr>
                    <th class="rp-sortable" onclick="sortBy(0)">Code <span class="rp-sort-arrow" id="sortArrow0"></span></th>
                    <th class="rp-sortable" onclick="sortBy(1)">Candidate Name <span class="rp-sort-arrow" id="sortArrow1"></span></th>
                    <th class="rp-sortable" onclick="sortBy(2)">Email <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Phone <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Job Applied <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th class="rp-sortable" onclick="sortBy(5)">Applied Date <span class="rp-sort-arrow" id="sortArrow5"></span></th>
                    <th class="rp-sortable" onclick="sortBy(6)">Status <span class="rp-sort-arrow" id="sortArrow6"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="candidateTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div id="paginationBar" class="rp-pagination"></div>
</div>

<!-- View Candidate Modal -->
<div class="modal-overlay" id="viewCandidateModal" onclick="if(event.target===this)closeModal('viewCandidateModal')">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Candidate Details</h3>
            <button class="modal-close" onclick="closeModal('viewCandidateModal')">&times;</button>
        </div>
        <div class="modal-body view-candidate-grid" id="viewCandidateBody"></div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline btn-sm" onclick="closeModal('viewCandidateModal')">Close</button>
        </div>
    </div>
</div>

<!-- Add / Edit Candidate Modal -->
<div class="modal-overlay" id="addCandidateModal" onclick="if(event.target===this)closeModal('addCandidateModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="candModalTitle">Add Candidate</h3>
            <button class="modal-close" onclick="closeModal('addCandidateModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="candidateForm" onsubmit="event.preventDefault();window.ajaxSaveCandidate()">
                <div class="form-group">
                    <label>Job Applied <span class="required">*</span></label>
                    <select class="form-control" id="acJobId" required>
                        <option value="">Select Job</option>
                    </select>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="acFirstName" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="acLastName" required>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" class="form-control" id="acEmail" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="acPhone">
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>NID</label>
                        <input type="text" class="form-control" id="acNid">
                    </div>
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select class="form-control" id="acBloodGroup">
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" id="acDob">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" id="acGender">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Present Address</label>
                    <textarea class="form-control" id="acPresentAddress" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Permanent Address</label>
                    <textarea class="form-control" id="acPermanentAddress" rows="2"></textarea>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Current Salary</label>
                        <input type="number" class="form-control" id="acCurrentSalary" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Expected Salary</label>
                        <input type="number" class="form-control" id="acExpectedSalary" min="0" step="0.01">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('addCandidateModal');resetCandidateForm()">Cancel</button>
            <button class="btn btn-primary" id="candSubmitBtn" onclick="window.ajaxSaveCandidate()">Save Candidate</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
