<?php
$basePath    = '..';
$pageTitle   = 'Recruitment Pipeline';
$activePage  = 'recruitment';
$breadcrumb  = 'Home / Recruitment / Pipeline';
$pageCss = 'css/page_css/recruitment-list.css';
$pageJs = 'js/page_js/recruitment-list.js';

ob_start();
?>
<div class="rp-top">
    <h1>Open Positions <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchJobs" placeholder="Search by title, department, location..." oninput="applyFilters()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
        <button class="btn btn-primary" onclick="resetRequisitionForm();openModal('createRequisitionModal')">+ New Requisition</button>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="jobsTable">
            <thead>
                <tr>
                    <th class="rp-sortable" onclick="sortBy(0)">Job Title <span class="rp-sort-arrow" id="sortArrow0"></span></th>
                    <th class="rp-sortable" onclick="sortBy(1)">Department <span class="rp-sort-arrow" id="sortArrow1"></span></th>
                    <th class="rp-sortable" onclick="sortBy(2)">Location <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Vacancies <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Filled <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th class="rp-sortable" onclick="sortBy(5)">Status <span class="rp-sort-arrow" id="sortArrow5"></span></th>
                    <th class="rp-sortable" onclick="sortBy(6)">Priority <span class="rp-sort-arrow" id="sortArrow6"></span></th>
                    <th class="rp-sortable" onclick="sortBy(7)">Deadline <span class="rp-sort-arrow" id="sortArrow7"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="jobsBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div id="paginationBar" class="rp-pagination"></div>
</div>

<div class="modal-overlay" id="viewJobModal" onclick="if(event.target===this)closeModal('viewJobModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="viewJobTitle">Job Details</h3>
            <button class="modal-close" onclick="closeModal('viewJobModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="view-job-details" id="viewJobDetails"></div>
            <div class="view-job-desc" id="viewJobDesc"></div>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline btn-sm" onclick="closeModal('viewJobModal')">Close</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="createRequisitionModal" onclick="if(event.target===this)closeModal('createRequisitionModal')">
    <div class="modal modal-requisition modal-sticky modal-xl">
        <div class="modal-header modal-header-navy">
            <h3 id="reqModalTitle">&#x1F4CB; Create New Requisition</h3>
            <button class="modal-close" onclick="closeModal('createRequisitionModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="requisitionForm" onsubmit="event.preventDefault();window.ajaxSubmitRequisition()">
                <div class="requisition-section">
                    <div class="section-title"><span class="section-step">1</span> Basic Information</div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Job Title <span class="required">*</span></label>
                            <input type="text" class="form-control" id="reqJobTitle" placeholder="e.g. Senior PHP Developer" required>
                        </div>
                        <div class="form-group">
                            <label>Department <span class="required">*</span></label>
                            <select class="form-control" id="reqDepartment" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" class="form-control" id="reqLocation" placeholder="e.g. Dhaka, Bangladesh" value="Dhaka, Bangladesh">
                        </div>
                        <div class="form-group">
                            <label>Employment Type <span class="required">*</span></label>
                            <select class="form-control" id="reqType" required>
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Number of Vacancies <span class="required">*</span></label>
                            <input type="number" class="form-control" id="reqVacancies" min="1" value="1" required>
                        </div>
                        <div class="form-group">
                            <label>Application Deadline <span class="required">*</span></label>
                            <input type="date" class="form-control" id="reqDeadline" required>
                        </div>
                    </div>
                </div>

                <div class="requisition-section">
                    <div class="section-title"><span class="section-step">2</span> Qualifications &amp; Salary</div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Minimum Education</label>
                            <select class="form-control" id="reqEducation">
                                <option value="High School">High School</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Bachelor" selected>Bachelor</option>
                                <option value="Master">Master</option>
                                <option value="PhD">PhD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Minimum Experience (years)</label>
                            <input type="number" class="form-control" id="reqExperience" min="0" step="0.5" value="2" placeholder="e.g. 3.5">
                        </div>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Salary Range (Min)</label>
                            <input type="number" class="form-control" id="reqSalaryMin" min="0" step="1000" placeholder="e.g. 45000">
                        </div>
                        <div class="form-group">
                            <label>Salary Range (Max)</label>
                            <input type="number" class="form-control" id="reqSalaryMax" min="0" step="1000" placeholder="e.g. 65000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Required Skills</label>
                        <div class="tag-input-wrapper" id="skillsWrapper" onclick="document.getElementById('skillsInput').focus()">
                            <div class="tags-container" id="skillsTags"></div>
                            <input type="text" class="tag-input" id="skillsInput" placeholder="Type skill and press Enter" onkeydown="if(event.key==='Enter'||event.key===','){event.preventDefault();window.addSkill()}">
                        </div>
                    </div>
                </div>

                <div class="requisition-section">
                    <div class="section-title"><span class="section-step">3</span> Details</div>
                    <div class="form-group">
                        <label>Job Description</label>
                        <textarea class="form-control textarea-full-lg" id="reqDescription" placeholder="Describe the role and responsibilities..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Responsibilities</label>
                        <textarea class="form-control textarea-full" id="reqResponsibilities" placeholder="List key responsibilities (one per line)"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Benefits</label>
                        <textarea class="form-control textarea-full" id="reqBenefits" placeholder="List benefits (one per line)"></textarea>
                    </div>
                </div>

                <div class="requisition-section">
                    <div class="section-title"><span class="section-step">4</span> Status &amp; Priority</div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Priority</label>
                            <select class="form-control" id="reqPriority">
                                <option value="High">High</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="reqStatus">
                                <option value="Active" selected>Active</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Closed">Closed</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('createRequisitionModal');resetRequisitionForm()">Cancel</button>
            <button class="btn btn-primary" id="reqSubmitBtn" onclick="window.ajaxSubmitRequisition()">&#x2728; Create Requisition</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
