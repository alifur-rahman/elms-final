<?php
$basePath   = '..';
$pageTitle  = 'Edit Probation Appraisal';
$activePage = 'probation';
$breadcrumb = 'Home / Probation / Edit Appraisal';
$pageCss = 'css/page_css/edit.css';
$pageJs = 'js/page_js/edit.js';;
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Edit Probation Appraisal</h2>
        <a class="btn btn-outline" href="list.php">← Back to List</a>
    </div>
    <div class="card-body">
        <div id="profileSection" style="display:none">
            <div class="profile-summary">
                <div class="item"><label>Name</label><span id="profName">-</span></div>
                <div class="item"><label>Department</label><span id="profDept">-</span></div>
                <div class="item"><label>Designation</label><span id="profDesignation">-</span></div>
                <div class="item"><label>Joining Date</label><span id="profJoining">-</span></div>
                <div class="item"><label>Probation</label><span id="profProbation">-</span></div>
            </div>

            <input type="hidden" id="joinDate">
            <input type="hidden" id="probationMonths">

            <h4 style="margin-bottom:8px">Appraisal Categories</h4>
            <div style="font-size:13px;color:#888;margin-bottom:8px">Rate each category from 0 to 5 (0.25 increments)</div>
            <div id="categoriesContainer"></div>

            <div class="score-meter" style="margin-top:16px">
                <div class="fill" id="scoreFill" style="width:0%"></div>
            </div>
            <div style="text-align:right;font-size:18px;font-weight:700">Overall Score: <span id="overallScore">0.00</span> / 5.00</div>

            <div style="margin-top:20px">
                <h4>Recommendation <span class="required">*</span></h4>
                <div style="display:flex;gap:16px;margin-top:8px;flex-wrap:wrap">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer"><input type="radio" name="recommendation" value="Confirm"> Confirm ✅</label>
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer"><input type="radio" name="recommendation" value="Extend"> Extend 🔄</label>
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer"><input type="radio" name="recommendation" value="Transfer"> Transfer 🔀</label>
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer"><input type="radio" name="recommendation" value="Terminate"> Terminate ⚠️</label>
                </div>
            </div>

            <div style="margin-top:16px;display:grid;gap:12px">
                <div class="form-group"><label>Supervisor Comments</label><textarea class="form-control" id="supervisorComments" rows="3"></textarea></div>
                <div class="form-group"><label>Department Head Comments</label><textarea class="form-control" id="deptHeadComments" rows="3"></textarea></div>
                <div class="form-group"><label>HR Review</label><textarea class="form-control" id="hrReview" rows="3"></textarea></div>
                <div class="form-group"><label>Overall Remarks</label><textarea class="form-control" id="remarks" rows="3"></textarea></div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--gray-200)">
                <button class="btn btn-outline" onclick="saveAppraisal('draft')">Save as Draft</button>
                <button class="btn btn-primary" onclick="saveAppraisal('submit')">Submit Appraisal</button>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
