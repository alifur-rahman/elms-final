<?php
$basePath    = '..';
$pageTitle   = 'Interview';
$activePage  = 'interview';
$breadcrumb  = 'Home / Recruitment / Interview';
$pageCss = 'css/page_css/interview-list.css';

$pageJs = 'js/page_js/interview-list.js';

ob_start();
?>
<div class="cc-grid">
    <div class="cc-card cc-schedule"><div><div class="cc-num" id="ccSchedule">0</div><div class="cc-label">Needs Scheduling</div></div></div>
    <div class="cc-card cc-feedback"><div><div class="cc-num" id="ccFeedback">0</div><div class="cc-label">Waiting Feedback</div></div></div>
    <div class="cc-card cc-next"><div><div class="cc-num" id="ccNext">0</div><div class="cc-label">Next Round</div></div></div>
    <div class="cc-card cc-done"><div><div class="cc-num" id="ccDone">0</div><div class="cc-label">Completed</div></div></div>
</div>

<div class="rp-top">
    <h1>Interview Candidates <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchInput" placeholder="Search by candidate, job, interviewer..." oninput="applyFilters()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
        <button class="btn btn-primary" onclick="window.openScheduleModal()">+ Schedule Interview</button>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="interviewTable">
            <thead>
                <tr>
                    <th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>
                    <th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="sortArrow1"></span></th>
                    <th class="rp-sortable" onclick="sortBy(2)">Round <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Type <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Interviewer <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th class="rp-sortable" onclick="sortBy(5)">Date / Time <span class="rp-sort-arrow" id="sortArrow5"></span></th>
                    <th class="rp-sortable" onclick="sortBy(6)">Status <span class="rp-sort-arrow" id="sortArrow6"></span></th>
                    <th class="rp-sortable" onclick="sortBy(7)">Decision <span class="rp-sort-arrow" id="sortArrow7"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ivTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div id="paginationBar" class="rp-pagination"></div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal-overlay" id="scheduleModal" onclick="if(event.target===this)closeModal('scheduleModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="scheduleModalTitle">Schedule Interview</h3>
            <button class="modal-close" onclick="closeModal('scheduleModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="scheduleForm" onsubmit="event.preventDefault();window.ajaxScheduleInterview()">
                <input type="hidden" id="siRescheduleId" value="">
                <div class="form-group">
                    <label>Candidate <span class="required">*</span></label>
                    <select class="form-control" id="siCandidateId" required onchange="onCandidateSelect()"></select>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Interview Date & Time <span class="required">*</span></label>
                        <input type="datetime-local" class="form-control" id="siDate" required>
                    </div>
                    <div class="form-group">
                        <label>Interview Type <span class="required">*</span></label>
                        <select class="form-control" id="siType" required>
                            <option value="">Select</option>
                            <option value="Phone">Phone</option>
                            <option value="Video">Video</option>
                            <option value="In-Person">In-Person</option>
                            <option value="Technical">Technical</option>
                            <option value="Panel">Panel</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Interviewer Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="siInterviewer" required>
                    </div>
                    <div class="form-group">
                        <label>Round Name</label>
                        <input type="text" class="form-control" id="siRoundName" placeholder="e.g. Technical Screening">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="checkbox-label" style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" id="siIsFinalRound" style="width:16px;height:16px;cursor:pointer">
                        <span style="font-size:13px;font-weight:500">This is the final round (passing will mark candidate as Selected)</span>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('scheduleModal')">Cancel</button>
            <button class="btn btn-primary" id="siSubmitBtn" onclick="window.ajaxScheduleInterview()">Schedule Interview</button>
        </div>
    </div>
</div>

<!-- Record Feedback Modal -->
<div class="modal-overlay" id="feedbackModal" onclick="if(event.target===this)closeModal('feedbackModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="feedbackModalTitle">Record Feedback</h3>
            <button class="modal-close" onclick="closeModal('feedbackModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="feedbackForm" onsubmit="event.preventDefault();window.ajaxSaveFeedback()">
                <input type="hidden" id="fbInterviewId">

                <div class="form-section-title">Category Scores (1-5)</div>
                <div class="score-grid">
                    <div class="form-group">
                        <label>Technical Skill <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbTechnical" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                    <div class="form-group">
                        <label>Communication <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbCommunication" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                    <div class="form-group">
                        <label>Problem Solving <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbProblemSolving" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                    <div class="form-group">
                        <label>Attitude <span class="required">*</span></label>
                        <input type="number" class="form-control" id="fbAttitude" min="1" max="5" step="0.5" oninput="calcOverall()" required>
                    </div>
                </div>

                <div class="overall-rating">
                    <div class="or-value" id="overallRatingValue">-</div>
                    <div class="or-label">Overall Rating</div>
                </div>

                <div class="form-group">
                    <label>Decision <span class="required">*</span></label>
                    <select class="form-control" id="fbDecision" onchange="onDecisionChange()" required>
                        <option value="">Select</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                    </select>
                </div>

                <div class="form-group" id="fbRejectReason" style="display:none">
                    <label>Reject Reason <span class="required">*</span></label>
                    <select class="form-control" id="fbRejectReasonText">
                        <option value="">Select</option>
                        <option value="Lack of technical skills">Lack of technical skills</option>
                        <option value="Poor communication">Poor communication</option>
                        <option value="Experience mismatch">Experience mismatch</option>
                        <option value="Cultural fit">Cultural fit</option>
                        <option value="Salary expectation mismatch">Salary expectation mismatch</option>
                        <option value="Not interested">Not interested</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Feedback Notes</label>
                    <textarea class="form-control" id="fbFeedbackText" rows="3" placeholder="Enter detailed feedback..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="checkbox-label" style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" id="fbIsFinalRound" style="width:16px;height:16px;cursor:pointer">
                        <span style="font-size:13px;font-weight:500">Mark as final round</span>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('feedbackModal')">Cancel</button>
            <button class="btn btn-primary" id="fbSubmitBtn" onclick="window.ajaxSaveFeedback()">Save Feedback</button>
        </div>
    </div>
</div>

<!-- Combined View Modal -->
<div class="modal-overlay" id="combinedViewModal" onclick="if(event.target===this)closeModal('combinedViewModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="combinedViewTitle">View</h3>
            <button class="modal-close" onclick="closeModal('combinedViewModal')">&times;</button>
        </div>
        <div class="modal-body" id="combinedViewBody"></div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline btn-sm" onclick="closeModal('combinedViewModal')">Close</button>
        </div>
    </div>
</div>

<style>
.iv-logs { display:flex; flex-direction:column; gap:16px; }
.iv-card { background:#fff; border:1px solid var(--gray-100); border-radius:10px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
.iv-card-header { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#f9fafb; border-bottom:1px solid var(--gray-100); }
.iv-card-header-left { display:flex; align-items:center; gap:8px; }
.iv-round { font-size:13px; font-weight:700; color:var(--gray-800); background:var(--gray-100); padding:2px 10px; border-radius:20px; }
.iv-round-name { font-size:14px; font-weight:600; color:var(--gray-700); }
.iv-card-header-right { display:flex; align-items:center; gap:10px; }
.iv-date { font-size:12px; color:var(--gray-400); white-space:nowrap; }
.iv-card-body { padding:14px 16px; }
.iv-meta { display:flex; gap:24px; font-size:13px; color:var(--gray-600); margin-bottom:12px; }
.iv-scores { display:grid; grid-template-columns:1fr 1fr; gap:8px 20px; margin-bottom:12px; }
.iv-score-item { display:flex; align-items:center; gap:8px; }
.iv-score-item label { font-size:12px; color:var(--gray-500); width:85px; flex-shrink:0; }
.iv-score-item span { font-size:12px; font-weight:600; color:var(--gray-700); width:30px; text-align:right; flex-shrink:0; }
.iv-score-bar { flex:1; height:6px; background:var(--gray-100); border-radius:3px; overflow:hidden; }
.iv-score-fill { height:100%; background:linear-gradient(90deg,#3b82f6,#6366f1); border-radius:3px; transition:width 0.3s; }
.iv-score-fill-blue { background:linear-gradient(90deg,#60a5fa,#3b82f6); }
.iv-footer { display:flex; align-items:center; gap:16px; margin-bottom:8px; }
.iv-overall { display:flex; align-items:baseline; gap:4px; background:var(--gray-50); padding:6px 14px; border-radius:8px; }
.iv-overall-value { font-size:18px; font-weight:700; color:var(--gray-800); }
.iv-overall-label { font-size:11px; color:var(--gray-400); text-transform:uppercase; letter-spacing:0.3px; }
.iv-decision { }
.iv-reject-reason { font-size:13px; color:#dc2626; margin-bottom:6px; padding:6px 10px; background:#fef2f2; border-radius:6px; }
.iv-feedback { font-size:13px; color:var(--gray-700); line-height:1.5; padding:8px 10px; background:#f8f9fa; border-radius:6px; border:1px solid var(--gray-100); margin-top:4px; }
.iv-empty-state { text-align:center; padding:16px; color:var(--gray-400); font-size:13px; }
</style>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
