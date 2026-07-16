<?php
$basePath   = '..';
$pageTitle  = 'Edit Probation Appraisal';
$activePage = 'probation';
$breadcrumb = 'Home / Probation / Edit Appraisal';
$pageStyles = '
.profile-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 12px; background: var(--gray-50); border-radius: 8px; padding: 16px; margin-bottom: 20px; }
.profile-summary .item { font-size: 13px; }
.profile-summary .item label { color: var(--gray-500); font-size: 11px; display: block; }
.profile-summary .item span { font-weight: 600; }
.category-row { display: grid; grid-template-columns: 1fr 80px 120px; gap: 12px; align-items: center; padding: 8px 12px; border-bottom: 1px solid var(--gray-200); }
.category-row:nth-child(odd) { background: var(--gray-50); }
.category-row .cat-name { font-size: 13px; }
.category-row .cat-weight { font-size: 12px; color: var(--gray-500); text-align: center; }
.category-row input[type="number"] { width: 100%; padding: 6px 8px; border: 1px solid var(--gray-300); border-radius: 4px; font-size: 13px; }
.score-meter { height: 8px; background: var(--gray-200); border-radius: 4px; margin: 12px 0; overflow: hidden; }
.score-meter .fill { height: 100%; border-radius: 4px; transition: width 0.3s, background 0.3s; }
';
$pageScripts = <<<'EOSCRIPT'
var categories = [];
var currentAppraisalId = 0;

function loadAppraisal() {
    var params = new URLSearchParams(window.location.search);
    var id = parseInt(params.get("id"));
    if (!id) { showAlert("No appraisal ID specified", "error"); window.location.href = "list.php"; return; }
    currentAppraisalId = id;

    apiGet("ajax/appraisals_get.php?id=" + id, null, function(res) {
        if (!res.success || !res.data) { showAlert("Appraisal not found", "error"); window.location.href = "list.php"; return; }
        var a = res.data;
        document.getElementById("profName").textContent = a.employee_name;
        document.getElementById("profDept").textContent = a.dept_name;
        document.getElementById("profDesignation").textContent = a.designation_name;
        document.getElementById("profJoining").textContent = a.joining_date || "-";
        document.getElementById("profProbation").textContent = a.probation_months + " months";
        document.getElementById("joinDate").value = a.joining_date || "";
        document.getElementById("probationMonths").value = a.probation_months || 6;
        document.getElementById("profileSection").style.display = "block";

        // Load categories and populate scores
        apiGet("ajax/categories_list.php", null, function(res2) {
            categories = res2.success && res2.data ? res2.data : [];
            var html = "";
            for (var i = 0; i < categories.length; i++) {
                var c = categories[i];
                var scoreVal = 0;
                if (a.scores) {
                    for (var j = 0; j < a.scores.length; j++) {
                        if (a.scores[j].category_id === c.id) { scoreVal = a.scores[j].score; break; }
                    }
                }
                html += "<div class=\"category-row\">" +
                    "<div class=\"cat-name\">" + c.name + "</div>" +
                    "<div class=\"cat-weight\">" + c.weight + "%</div>" +
                    "<div><input type=\"number\" class=\"cat-score\" data-cat-id=\"" + c.id + "\" min=\"0\" max=\"5\" step=\"0.25\" value=\"" + scoreVal + "\" onchange=\"recalcScore()\" oninput=\"recalcScore()\"></div>" +
                "</div>";
            }
            document.getElementById("categoriesContainer").innerHTML = html;
            recalcScore();
        });

        // Populate recommendation
        if (a.recommendation) {
            var radios = document.querySelectorAll('input[name="recommendation"]');
            for (var i = 0; i < radios.length; i++) {
                if (radios[i].value === a.recommendation) { radios[i].checked = true; break; }
            }
        }
        document.getElementById("remarks").value = a.remarks || "";
        document.getElementById("supervisorComments").value = a.supervisor_comments || "";
        document.getElementById("deptHeadComments").value = a.dept_head_comments || "";
        document.getElementById("hrReview").value = a.hr_review || "";
    });
}

function loadCategories() {
    apiGet("ajax/categories_list.php", null, function(res) {
        categories = res.success && res.data ? res.data : [];
    });
}

function recalcScore() {
    var inputs = document.querySelectorAll(".cat-score");
    var total = 0;
    var totalWeight = 0;
    for (var i = 0; i < inputs.length; i++) {
        var val = parseFloat(inputs[i].value) || 0;
        var catId = parseInt(inputs[i].getAttribute("data-cat-id"));
        for (var j = 0; j < categories.length; j++) {
            if (categories[j].id === catId) {
                total += val * categories[j].weight;
                totalWeight += categories[j].weight;
                break;
            }
        }
    }
    var score = totalWeight > 0 ? (total / totalWeight) : 0;
    score = Math.round(score * 100) / 100;
    document.getElementById("overallScore").textContent = score.toFixed(2);
    var fill = document.getElementById("scoreFill");
    var pct = Math.min(score / 5 * 100, 100);
    fill.style.width = pct + "%";
    if (score >= 4) fill.style.background = "#1e8e3e";
    else if (score >= 3) fill.style.background = "#1a73e8";
    else if (score >= 2) fill.style.background = "#e37400";
    else fill.style.background = "#d93025";
}

function collectScores() {
    var inputs = document.querySelectorAll(".cat-score");
    var scores = [];
    for (var i = 0; i < inputs.length; i++) {
        var val = parseFloat(inputs[i].value) || 0;
        var catId = parseInt(inputs[i].getAttribute("data-cat-id"));
        scores.push({ category_id: catId, score: val, comments: "" });
    }
    return scores;
}

function saveAppraisal(status) {
    var recommendation = document.querySelector('input[name="recommendation"]:checked');
    var remarks = document.getElementById("remarks").value.trim();
    var supCmt = document.getElementById("supervisorComments").value.trim();
    var deptCmt = document.getElementById("deptHeadComments").value.trim();
    var hrReview = document.getElementById("hrReview").value.trim();
    var overallScore = parseFloat(document.getElementById("overallScore").textContent) || 0;

    if (status === "submit" && !recommendation) { showAlert("Please select a recommendation before submitting", "error"); return; }

    var payload = {
        id: currentAppraisalId,
        overall_score: overallScore,
        recommendation: recommendation ? recommendation.value : "",
        remarks: remarks,
        supervisor_comments: supCmt,
        dept_head_comments: deptCmt,
        hr_review: hrReview,
        scores: collectScores()
    };

    apiPost("ajax/appraisals_update.php", payload, function(res) {
        showAlert(res.message || "Saved!", "success");
        if (status === "submit") {
            apiPost("ajax/appraisals_submit.php", { id: currentAppraisalId }, function(sres) {
                showAlert(sres.message || "Submitted!", "success");
                window.location.href = "list.php";
            });
        } else {
            window.location.href = "list.php";
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    loadCategories();
    loadAppraisal();
});
EOSCRIPT;
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
