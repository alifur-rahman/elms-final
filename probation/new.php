<?php
$basePath   = '..';
$pageTitle  = 'New Probation Appraisal';
$activePage = 'probation';
$breadcrumb = 'Home / Probation / New Appraisal';
$pageCss = 'css/page_css/new.css';
$pageJs = 'js/page_js/new.js';;
ob_start();
?>

<div class="employee-selector">
    <label>Select Employee</label>
    <select class="form-control" id="empSelect" onchange="onEmployeeChange()">
        <option value="">-- Choose an employee --</option>
    </select>
    <span style="font-size:12px;color:var(--gray-500)">Only employees on probation or eligible for appraisal are shown</span>
</div>

<div id="existingWarning" class="alert alert-warning mb-24" style="display:none">
    <strong></strong>
    <span style="display:block;margin-top:4px;font-size:13px">An appraisal already exists for this employee. Only one appraisal per employee is allowed.</span>
</div>

<div id="appraisalForm" style="display:none">
    <div class="profile-card" id="profileCard"></div>

    <div class="alert alert-info mb-24" id="integratedDataAlert">
        <div>
            <strong>&#128202; Integrated Data</strong> &mdash; Attendance, leave, and KPI data pulled automatically from Employee Master, Attendance &amp; Leave modules.
            <div class="integrated-stats">
                <span>&#10003; Attendance: <strong id="attPct">94</strong>%</span>
                <span>&#10003; Late Marks: <strong id="lateMarks">2</strong></span>
                <span>&#10003; Leave Balance: <strong id="leaveBal">11.5</strong> days</span>
                <span>&#10003; Training: <strong id="trainPct">85</strong>% Complete</span>
            </div>
        </div>
    </div>

    <div class="workflow-steps">
        <div class="workflow-step active" data-step="1">
            <div class="step-circle">1</div>
            <div class="step-info"><span class="step-label">Self Assessment</span><span class="step-user">Employee</span></div>
        </div>
        <div class="workflow-connector"></div>
        <div class="workflow-step" data-step="2">
            <div class="step-circle">2</div>
            <div class="step-info"><span class="step-label">Supervisor</span><span class="step-user">Pending</span></div>
        </div>
        <div class="workflow-connector"></div>
        <div class="workflow-step" data-step="3">
            <div class="step-circle">3</div>
            <div class="step-info"><span class="step-label">Dept. Head</span><span class="step-user">Pending</span></div>
        </div>
        <div class="workflow-connector"></div>
        <div class="workflow-step" data-step="4">
            <div class="step-circle">4</div>
            <div class="step-info"><span class="step-label">HR Review</span><span class="step-user">Pending</span></div>
        </div>
        <div class="workflow-connector"></div>
        <div class="workflow-step" data-step="5">
            <div class="step-circle">5</div>
            <div class="step-info"><span class="step-label">GM</span><span class="step-user">Pending</span></div>
        </div>
        <div class="workflow-connector"></div>
        <div class="workflow-step" data-step="6">
            <div class="step-circle">6</div>
            <div class="step-info"><span class="step-label">Managing Director</span><span class="step-user">Pending</span></div>
        </div>
    </div>

    <div class="card mb-24">
        <div class="card-header">
            <h2>&#128202; Overall Score</h2>
            <span style="font-size:24px;font-weight:700;color:var(--primary)" id="overallScore">0.00</span>
        </div>
        <div class="card-body">
            <div class="meter-bar">
                <div class="bar-track">
                    <div class="bar-fill" id="scoreBar" style="width:0%"></div>
                </div>
                <span class="bar-label" id="scoreLabel">0 / 5</span>
            </div>
            <div class="score-labels">
                <span>Poor</span><span>Average</span><span>Good</span><span>Excellent</span>
            </div>
        </div>
    </div>

    <div id="categoryContainer"></div>

    <div class="card mb-24">
        <div class="card-header"><h2>&#128221; Supervisor Comments</h2></div>
        <div class="card-body">
            <div class="form-group">
                <textarea id="supervisorComments" class="form-control textarea-full" placeholder="Enter supervisor's evaluation and feedback..."></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-24">
        <div class="card-header"><h2>&#128221; Department Head Comments</h2></div>
        <div class="card-body">
            <div class="form-group">
                <textarea id="deptHeadComments" class="form-control textarea-full" placeholder="Enter department head's review..."></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-24">
        <div class="card-header"><h2>&#127977; HR Review</h2></div>
        <div class="card-body">
            <div class="form-group">
                <textarea id="hrReview" class="form-control textarea-full-lg" placeholder="Enter HR review, overall assessment, and any additional notes..."></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-24">
        <div class="card-header"><h2>&#128203; Recommendation</h2></div>
        <div class="card-body">
            <div class="form-row-4" style="margin-bottom:20px">
                <label class="rec-option" data-rec="Confirm" onclick="selectRecommendation(this)">
                    <input type="radio" name="recommendation" value="Confirm">
                    <div>
                        <div style="font-weight:600;color:var(--success)">&#10004; Confirm</div>
                        <div style="font-size:11px;color:var(--gray-500)">Employee passes probation</div>
                    </div>
                </label>
                <label class="rec-option" data-rec="Extend" onclick="selectRecommendation(this)">
                    <input type="radio" name="recommendation" value="Extend">
                    <div>
                        <div style="font-weight:600;color:var(--warning)">&#8987; Extend Probation</div>
                        <div style="font-size:11px;color:var(--gray-500)">Needs more time</div>
                    </div>
                </label>
                <label class="rec-option" data-rec="Transfer" onclick="selectRecommendation(this)">
                    <input type="radio" name="recommendation" value="Transfer">
                    <div>
                        <div style="font-weight:600;color:var(--primary)">&#128260; Transfer</div>
                        <div style="font-size:11px;color:var(--gray-500)">Better fit elsewhere</div>
                    </div>
                </label>
                <label class="rec-option" data-rec="Terminate" onclick="selectRecommendation(this)">
                    <input type="radio" name="recommendation" value="Terminate">
                    <div>
                        <div style="font-weight:600;color:var(--danger)">&#10008; Terminate</div>
                        <div style="font-size:11px;color:var(--gray-500)">Does not meet requirements</div>
                    </div>
                </label>
            </div>

            <div id="extendSection" style="display:none" class="alert alert-warning mb-16">
                <div>
                    <strong>Extend Probation</strong>
                    <div style="margin-top:8px">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Extension Period (Months)</label>
                                <select id="extensionMonths" class="form-control" onchange="updateExtendEndDate()">
                                    <option>1</option><option>2</option><option selected>3</option><option>6</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>New Probation End Date</label>
                                <input type="date" id="newProbationEnd" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Reason for Extension</label>
                            <textarea id="extensionReason" class="form-control textarea-full" placeholder="Explain why probation needs to be extended..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div id="transferSection" style="display:none" class="alert alert-info mb-16">
                <div>
                    <strong>Transfer Employee</strong>
                    <div style="margin-top:8px">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Transfer to Department</label>
                                <select id="transferDept" class="form-control">
                                    <option>Engineering</option><option>Marketing</option><option>Finance</option><option>HR</option><option>Operations</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>New Designation</label>
                                <input type="text" id="transferDesignation" class="form-control" placeholder="e.g., Junior Engineer">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Reason for Transfer</label>
                            <textarea id="transferReason" class="form-control textarea-full" placeholder="Explain why transfer is recommended..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Overall Remarks</label>
                <textarea id="overallRemarks" class="form-control textarea-full-lg" placeholder="Additional remarks or justification for the recommendation..."></textarea>
            </div>
        </div>
    </div>

    <div class="bottom-actions">
        <div>
            <a href="list.php" class="btn btn-outline">&#8592; Back to List</a>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-outline" onclick="saveAppraisal('draft')">Save Draft</button>
            <button type="button" class="btn btn-primary btn-lg" onclick="saveAppraisal('submit')">Submit Appraisal</button>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
