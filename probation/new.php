<?php
$basePath   = '..';
$pageTitle  = 'New Probation Appraisal';
$activePage = 'probation';
$breadcrumb = 'Home / Probation / New Appraisal';
$pageStyles = '
.profile-card { display: flex; gap: 24px; align-items: center; background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 24px; margin-bottom: 24px; }
.profile-avatar { width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #1565c0); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; flex-shrink: 0; }
.profile-details { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }
.detail-item label { display: block; font-size: 11px; color: var(--gray-500); margin-bottom: 2px; }
.detail-item span { font-size: 13px; font-weight: 500; word-break: break-word; }
.workflow-steps { display: flex; align-items: center; gap: 0; margin-bottom: 24px; padding: 20px; background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); overflow-x: auto; -webkit-overflow-scrolling: touch; scroll-behavior: smooth; }
.workflow-steps::-webkit-scrollbar { height: 4px; }
.workflow-steps::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 2px; }
.workflow-step { display: flex; align-items: center; gap: 8px; position: relative; flex-shrink: 0; cursor: pointer; }
.workflow-step .step-circle { width: 36px; height: 36px; border-radius: 50%; border: 2px solid var(--gray-300); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; color: var(--gray-400); background: #fff; transition: all .2s; flex-shrink: 0; }
.workflow-step.active .step-circle { border-color: var(--primary); background: var(--primary); color: #fff; }
.workflow-step.done .step-circle { border-color: var(--success); background: var(--success); color: #fff; }
.workflow-step .step-info { display: flex; flex-direction: column; min-width: 0; }
.workflow-step .step-label { font-size: 12px; font-weight: 500; white-space: nowrap; }
.workflow-step .step-user { font-size: 10px; color: var(--gray-500); }
.workflow-connector { width: 40px; height: 2px; background: var(--gray-300); flex-shrink: 0; margin: 0 8px; }
.workflow-connector.done { background: var(--success); }
.appraisal-section { background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); margin-bottom: 16px; overflow: hidden; }
.appraisal-section .section-header { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; font-weight: 600; font-size: 14px; background: #e8f0fe; }
.appraisal-section .section-body { padding: 16px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.appraisal-table { width: 100%; border-collapse: collapse; }
.appraisal-table th { text-align: left; font-size: 12px; color: var(--gray-500); padding: 8px 12px; border-bottom: 1px solid var(--gray-200); background: var(--gray-50); position: sticky; top: 0; z-index: 1; }
.appraisal-table td { padding: 12px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; font-size: 13px; }
.appraisal-table td[data-label] { position: relative; }
.rating-group { display: flex; flex-direction: row-reverse; gap: 4px; justify-content: flex-end; flex-wrap: wrap; }
.rating-group input[type="radio"] { display: none; }
.rating-group label { width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--gray-300); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .15s; color: var(--gray-400); flex-shrink: 0; -webkit-tap-highlight-color: transparent; }
.rating-group label:hover { border-color: var(--primary); color: var(--primary); }
.rating-group input:checked ~ label { border-color: var(--primary); background: var(--primary); color: #fff; }
.meter-bar { display: flex; align-items: center; gap: 12px; }
.bar-track { flex: 1; height: 12px; background: var(--gray-200); border-radius: 6px; overflow: hidden; }
.bar-fill { height: 100%; border-radius: 6px; background: linear-gradient(90deg, #f44336, #ff9800, #ffc107, #4caf50, #1b5e20); transition: width 0.5s ease; }
.bar-label { font-size: 14px; font-weight: 600; color: var(--primary); white-space: nowrap; }
.rec-option { display: flex; align-items: center; gap: 10px; padding: 16px; border: 2px solid var(--gray-200); border-radius: var(--radius); cursor: pointer; transition: all .2s; }
.rec-option:hover { border-color: var(--primary); }
.rec-option input[type="radio"] { width: 18px; height: 18px; accent-color: var(--primary); flex-shrink: 0; }
.weight-badge { font-size: 11px; color: var(--gray-500); font-weight: 400; }
.form-row-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.employee-selector { padding: 16px; background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); margin-bottom: 24px; display: flex; align-items: center; gap: 16px; }
.employee-selector label { font-weight: 500; font-size: 14px; white-space: nowrap; }
.employee-selector select { min-width: 0; flex: 1; }
.alert-info { background: #e8f0fe; border: 1px solid #c6dafc; color: #1a73e8; }
.integrated-stats { display: flex; flex-wrap: wrap; gap: 6px 12px; margin-top: 4px; font-size: 12px; color: var(--gray-600); }
.integrated-stats span { white-space: nowrap; }
.bottom-actions { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; }
.score-labels { display: flex; justify-content: space-between; margin-top: 4px; font-size: 11px; color: var(--gray-500); }
.mb-24 { margin-bottom: 24px; }
.mb-16 { margin-bottom: 16px; }
.mb-8 { margin-bottom: 8px; }
.card .card-body .form-group { margin-bottom: 16px; }
.card .card-body .form-group:last-child { margin-bottom: 0; }

@media(max-width:900px){
 .profile-details{grid-template-columns:repeat(auto-fill,minmax(150px,1fr))}
 .form-row-4{grid-template-columns:1fr 1fr}
 .rec-option{padding:14px 12px}
}

@media(max-width:768px){
 .page-content{padding:16px}
 .employee-selector{flex-direction:column;align-items:stretch;gap:10px}
 .employee-selector label{white-space:normal}
 .employee-selector span{font-size:11px}
 .profile-card{flex-direction:column;align-items:center;text-align:center;gap:16px;padding:20px}
 .profile-avatar{width:56px;height:56px;font-size:18px}
 .profile-details{grid-template-columns:1fr 1fr;gap:10px}
 .detail-item label{font-size:10px}
 .detail-item span{font-size:12px}
 .workflow-steps{padding:14px;gap:0}
 .workflow-step .step-circle{width:30px;height:30px;font-size:12px}
 .workflow-step .step-info{min-width:0}
 .workflow-step .step-label{font-size:11px}
 .workflow-step .step-user{font-size:9px}
 .workflow-connector{width:20px;margin:0 4px}
 .appraisal-section .section-header{flex-direction:column;align-items:flex-start;gap:4px;padding:10px 14px;font-size:13px}
 .appraisal-section .section-body{padding:12px}
 .appraisal-table{min-width:420px}
 .appraisal-table th{font-size:11px;padding:6px 10px}
 .appraisal-table td{padding:10px;font-size:12px}
 .rating-group label{width:30px;height:30px;font-size:11px}
 .meter-bar{gap:8px}
 .bar-track{height:10px}
 .bar-label{font-size:12px}
 .score-labels{font-size:10px}
 .form-row-4{grid-template-columns:1fr 1fr}
 .rec-option{padding:12px;gap:8px}
 .rec-option input[type="radio"]{width:16px;height:16px}
 .rec-option > div > div:first-child{font-size:13px}
 .rec-option > div > div:last-child{font-size:10px}
 .form-row{grid-template-columns:1fr!important}
 .form-group{margin-bottom:14px}
 textarea.form-control{min-height:80px}
 .card .card-header{flex-direction:column;align-items:flex-start;gap:8px;padding:14px 16px}
 .card .card-header h2{font-size:14px}
 .card .card-body{padding:14px 16px}
 .mb-24{margin-bottom:16px}
 .bottom-actions{flex-direction:column;gap:12px;align-items:stretch}
 .bottom-actions > div:first-child{text-align:center}
 .bottom-actions .btn-group{flex-direction:row;gap:10px}
 .bottom-actions .btn-group .btn{flex:1}
 .bottom-actions a.btn{display:block;text-align:center}
 .integrated-stats{flex-direction:column;gap:4px}
 .integrated-stats span{text-align:center}
}

@media(max-width:480px){
 .page-content{padding:12px}
 .employee-selector{padding:12px}
 .profile-card{padding:14px;border-radius:6px}
 .profile-avatar{width:48px;height:48px;font-size:16px}
 .profile-details{grid-template-columns:1fr 1fr;gap:8px}
 .detail-item label{font-size:9px}
 .detail-item span{font-size:11px}
 .workflow-steps{padding:12px}
 .workflow-step .step-circle{width:26px;height:26px;font-size:11px;border-width:1.5px}
 .workflow-step .step-label{font-size:10px}
 .workflow-step .step-user{display:none}
 .workflow-step .step-info{display:none}
 .workflow-connector{width:14px;margin:0 3px}
 .appraisal-section .section-header{padding:8px 12px;font-size:12px}
 .appraisal-section .section-body{padding:10px;overflow-x:auto;-webkit-overflow-scrolling:touch}
 .appraisal-table thead{display:none}
 .appraisal-table,
 .appraisal-table tbody,
 .appraisal-table tr,
 .appraisal-table td{display:block;width:100%}
 .appraisal-table tr{border:1px solid var(--gray-200);border-radius:6px;padding:10px 12px;margin-bottom:10px;background:#fff}
 .appraisal-table td{padding:6px 0;border-bottom:none;position:relative;padding-left:0}
 .appraisal-table td[data-label]::before{
 content:attr(data-label);
 display:block;
 font-size:10px;
 font-weight:600;
 color:var(--gray-500);
 text-transform:uppercase;
 letter-spacing:.5px;
 margin-bottom:4px;
 }
 .rating-group{justify-content:flex-start!important;flex-wrap:wrap;gap:6px}
 .rating-group label{width:36px;height:36px;font-size:13px}
 .appraisal-table td input[type="text"]{width:100%;margin-top:4px}
 .form-row-4{grid-template-columns:1fr}
 .rec-option{padding:10px;gap:6px}
 .rec-option > div > div:first-child{font-size:12px}
 .rec-option > div > div:last-child{font-size:9px}
 .bottom-actions .btn-group{flex-direction:column}
 .bottom-actions .btn-group .btn{width:100%;justify-content:center}
 .bottom-actions a.btn{width:100%}
 .card{border-radius:6px}
 .mb-24{margin-bottom:14px}
 .alert{padding:10px 12px;font-size:12px;border-radius:6px}
 .meter-bar{flex-direction:column;align-items:stretch;gap:6px}
 .bar-label{text-align:right}
 .score-labels span:nth-child(2),
 .score-labels span:nth-child(3){display:none}
 .card .card-header{padding:12px 14px}
 .card .card-header h2{font-size:13px}
 .card .card-body{padding:12px 14px}
}
';
$pageScripts = <<<'EOSCRIPT'
var employees = [];
var categories = [];
var currentAppraisalId = 0;

function loadEmployees() {
    apiGet("ajax/employees_list.php", null, function(res) {
        employees = res.success && res.data ? res.data : [];
        var sel = document.getElementById("empSelect");
        sel.innerHTML = "<option value=\"\">-- Choose an employee --</option>";
        for (var i = 0; i < employees.length; i++) {
            if (employees[i].has_appraisal) continue;
            sel.innerHTML += "<option value=\"" + employees[i].id + "\">" + employees[i].name + " (" + employees[i].designation_name + ")</option>";
        }
    });
}

function loadCategories() {
    apiGet("ajax/categories_list.php", null, function(res) {
        categories = res.success && res.data ? res.data : [];
        renderCategories();
    });
}

function renderCategories() {
    var container = document.getElementById("categoryContainer");
    container.innerHTML = "";
    for (var c = 0; c < categories.length; c++) {
        var cat = categories[c];
        var section = document.createElement("div");
        section.className = "appraisal-section";
        section.innerHTML =
            '<div class="section-header">' +
                '<span>' + cat.name + '</span>' +
                '<span class="weight-badge">Weight: ' + cat.weight + '% | Avg: <span class="section-avg" id="avg_' + cat.id + '">-</span></span>' +
            '</div>' +
            '<div class="section-body">' +
                '<table class="appraisal-table">' +
                    '<thead><tr><th style="width:40%">Question</th><th style="width:35%">Rating (1-5)</th><th>Comments</th></tr></thead>' +
                    '<tbody id="qBody_' + cat.id + '"></tbody>' +
                '</table>' +
            '</div>';
        container.appendChild(section);
        var tbody = document.getElementById("qBody_" + cat.id);
        var sampleQs = [
            cat.name + ' - Demonstrates understanding of role requirements',
            cat.name + ' - Maintains consistent performance standards',
            cat.name + ' - Shows improvement and learning ability'
        ];
        for (var q = 0; q < sampleQs.length; q++) {
            var qid = cat.id + "_" + q;
            var tr = document.createElement("tr");
            tr.innerHTML =
                '<td data-label="Question">' + sampleQs[q] + '</td>' +
                '<td data-label="Rating">' +
                    '<div class="rating-group">' +
                        '<input type="radio" name="rating_' + qid + '" id="r_' + qid + '_5" value="5" onchange="recalcScore()"><label for="r_' + qid + '_5">5</label>' +
                        '<input type="radio" name="rating_' + qid + '" id="r_' + qid + '_4" value="4" onchange="recalcScore()"><label for="r_' + qid + '_4">4</label>' +
                        '<input type="radio" name="rating_' + qid + '" id="r_' + qid + '_3" value="3" onchange="recalcScore()"><label for="r_' + qid + '_3">3</label>' +
                        '<input type="radio" name="rating_' + qid + '" id="r_' + qid + '_2" value="2" onchange="recalcScore()"><label for="r_' + qid + '_2">2</label>' +
                        '<input type="radio" name="rating_' + qid + '" id="r_' + qid + '_1" value="1" onchange="recalcScore()"><label for="r_' + qid + '_1">1</label>' +
                    '</div>' +
                '</td>' +
                '<td data-label="Comments"><input type="text" class="form-control" data-q-comment="' + qid + '" placeholder="Optional comments..."></td>';
            tbody.appendChild(tr);
        }
    }
}

function onEmployeeChange() {
    var empId = parseInt(document.getElementById("empSelect").value);
    var warningEl = document.getElementById("existingWarning");
    if (!empId) {
        document.getElementById("appraisalForm").style.display = "none";
        warningEl.style.display = "none";
        return;
    }
    var e = null;
    for (var i = 0; i < employees.length; i++) {
        if (employees[i].id === empId) { e = employees[i]; break; }
    }
    if (e && e.has_appraisal) {
        warningEl.style.display = "block";
        warningEl.querySelector("strong").textContent = e.name + " already has a probation appraisal.";
        document.getElementById("appraisalForm").style.display = "none";
    } else {
        warningEl.style.display = "none";
        document.getElementById("appraisalForm").style.display = "block";
        document.getElementById("empSelect").setAttribute("data-join", e.joining_date || "");
        document.getElementById("empSelect").setAttribute("data-probation", e.probation_months || 6);
        loadProfile(empId);
        renderCategories();
        renderWorkflow(1);
    }
}

function loadProfile(empId) {
    var e = null;
    for (var i = 0; i < employees.length; i++) {
        if (employees[i].id === empId) { e = employees[i]; break; }
    }
    if (!e) return;
    var initials = (e.first_name ? e.first_name.charAt(0) : "") + (e.last_name ? e.last_name.charAt(0) : "");
    var joinDate = e.joining_date ? formatDate(e.joining_date) : "N/A";
    var endDate = "N/A";
    if (e.joining_date) {
        var d = new Date(e.joining_date + "T00:00:00");
        d.setMonth(d.getMonth() + (e.probation_months || 6));
        endDate = formatDate(d.toISOString().slice(0, 10));
    }
    document.getElementById("profileCard").innerHTML =
        '<div class="profile-avatar">' + initials + '</div>' +
        '<div class="profile-details">' +
            '<div class="detail-item"><label>Employee Code</label><span>' + (e.employee_code || "N/A") + '</span></div>' +
            '<div class="detail-item"><label>Employee Name</label><span>' + e.name + '</span></div>' +
            '<div class="detail-item"><label>Department</label><span>' + (e.dept_name || "N/A") + '</span></div>' +
            '<div class="detail-item"><label>Designation</label><span>' + (e.designation_name || "N/A") + '</span></div>' +
            '<div class="detail-item"><label>Joining Date</label><span>' + joinDate + '</span></div>' +
            '<div class="detail-item"><label>Probation End</label><span>' + endDate + '</span></div>' +
            '<div class="detail-item"><label>Reporting Manager</label><span>N/A</span></div>' +
            '<div class="detail-item"><label>Current Leave</label><span>3.5 / 15 days</span></div>' +
            '<div class="detail-item"><label>Attendance %</label><span>94%</span></div>' +
            '<div class="detail-item"><label>Late %</label><span>2%</span></div>' +
        '</div>';
}

function renderWorkflow(currentStep) {
    var steps = document.querySelectorAll(".workflow-step");
    var conns = document.querySelectorAll(".workflow-connector");
    for (var i = 0; i < steps.length; i++) {
        steps[i].classList.remove("active", "done");
        var stepNum = parseInt(steps[i].getAttribute("data-step"));
        if (stepNum < currentStep) {
            steps[i].classList.add("done");
            steps[i].querySelector(".step-circle").innerHTML = "&#10003;";
        } else if (stepNum === currentStep) {
            steps[i].classList.add("active");
        }
        if (i < conns.length) {
            if (i < currentStep - 1) conns[i].classList.add("done");
            else conns[i].classList.remove("done");
        }
    }
}

function recalcScore() {
    var totalWeight = 0;
    var weightedSum = 0;
    for (var c = 0; c < categories.length; c++) {
        var cat = categories[c];
        var qs = document.querySelectorAll("#qBody_" + cat.id + " tr");
        var catSum = 0;
        var catCount = 0;
        for (var q = 0; q < qs.length; q++) {
            var checked = qs[q].querySelector('input[type="radio"]:checked');
            if (checked) {
                catSum += parseInt(checked.value);
                catCount++;
            }
        }
        var avg = catCount > 0 ? catSum / catCount : 0;
        weightedSum += avg * cat.weight;
        totalWeight += cat.weight;
        document.getElementById("avg_" + cat.id).textContent = catCount > 0 ? avg.toFixed(1) : "-";
    }
    var overall = totalWeight > 0 ? (weightedSum / totalWeight) : 0;
    document.getElementById("overallScore").textContent = overall.toFixed(2);
    document.getElementById("scoreBar").style.width = (overall / 5 * 100) + "%";
    document.getElementById("scoreLabel").textContent = overall.toFixed(1) + " / 5";
}

function selectRecommendation(el) {
    var labels = document.querySelectorAll(".rec-option");
    for (var i = 0; i < labels.length; i++) {
        labels[i].style.borderColor = "var(--gray-200)";
        labels[i].style.background = "transparent";
    }
    var val = el.querySelector("input").value;
    var colors = { Confirm: "var(--success)", Extend: "var(--warning)", Transfer: "var(--primary)", Terminate: "var(--danger)" };
    var bgColors = { Confirm: "#e6f4ea", Extend: "#fef7e0", Transfer: "#e8f0fe", Terminate: "#fce8e6" };
    el.style.borderColor = colors[val] || "var(--gray-200)";
    el.style.background = bgColors[val] || "transparent";
    el.querySelector("input").checked = true;
    document.getElementById("extendSection").style.display = val === "Extend" ? "block" : "none";
    document.getElementById("transferSection").style.display = val === "Transfer" ? "block" : "none";
}

function updateExtendEndDate() {
    var months = parseInt(document.getElementById("extensionMonths").value);
    var today = new Date();
    var end = new Date(today.setMonth(today.getMonth() + months));
    document.getElementById("newProbationEnd").value = end.toISOString().slice(0, 10);
}

function collectScores() {
    var scores = [];
    for (var c = 0; c < categories.length; c++) {
        var cat = categories[c];
        var qs = document.querySelectorAll("#qBody_" + cat.id + " tr");
        var catSum = 0;
        var catCount = 0;
        for (var q = 0; q < qs.length; q++) {
            var checked = qs[q].querySelector('input[type="radio"]:checked');
            var val = checked ? parseInt(checked.value) : 0;
            var commentInput = qs[q].querySelector('input[data-q-comment]');
            var comment = commentInput ? commentInput.value : "";
            if (checked) {
                catSum += val;
                catCount++;
                scores.push({ category_id: cat.id, score: val, comments: comment });
            }
        }
    }
    return scores;
}

function saveAppraisal(status) {
    var empId = parseInt(document.getElementById("empSelect").value);
    if (!empId) { showAlert("Please select an employee", "error"); return; }
    var rec = document.querySelector('input[name="recommendation"]:checked');
    var overallScore = parseFloat(document.getElementById("overallScore").textContent) || 0;

    if (status === "submit" && !rec) { showAlert("Please select a recommendation before submitting", "error"); return; }

    var sel = document.getElementById("empSelect");
    var payload = {
        employee_id: empId,
        joining_date: sel.getAttribute("data-join") || "",
        probation_months: parseInt(sel.getAttribute("data-probation")) || 6,
        overall_score: overallScore,
        recommendation: rec ? rec.value : "",
        supervisor_comments: document.getElementById("supervisorComments").value.trim(),
        dept_head_comments: document.getElementById("deptHeadComments").value.trim(),
        hr_review: document.getElementById("hrReview").value.trim(),
        remarks: document.getElementById("overallRemarks").value.trim(),
        scores: collectScores()
    };

    if (currentAppraisalId > 0) {
        payload.id = currentAppraisalId;
        payload.status = status === "submit" ? "Pending" : "Draft";
        apiPost("ajax/appraisals_update.php", payload, function(res) {
            showAlert(res.message || "Saved!", "success");
            if (status === "submit") {
                apiPost("ajax/appraisals_submit.php", { id: currentAppraisalId }, function(sres) {
                    showAlert(sres.message || "Submitted!", "success");
                    window.location.href = "list.php";
                });
            }
        });
    } else {
        apiPost("ajax/appraisals_create.php", payload, function(res) {
            if (res.success) {
                currentAppraisalId = res.id;
                if (status === "submit") {
                    apiPost("ajax/appraisals_update.php", { id: res.id, scores: collectScores(), overall_score: overallScore, recommendation: rec ? rec.value : "", supervisor_comments: document.getElementById("supervisorComments").value.trim(), dept_head_comments: document.getElementById("deptHeadComments").value.trim(), hr_review: document.getElementById("hrReview").value.trim(), remarks: document.getElementById("overallRemarks").value.trim(), status: "Pending" }, function(ures) {
                        apiPost("ajax/appraisals_submit.php", { id: res.id }, function(sres) {
                            showAlert(sres.message || "Submitted!", "success");
                            window.location.href = "list.php";
                        });
                    });
                } else {
                    showAlert("Draft saved!", "success");
                    window.location.href = "list.php";
                }
            } else {
                showAlert(res.message || "Failed to create", "error");
            }
        });
    }
}

function formatDate(d) {
    if (!d) return "";
    var date = new Date(d + "T00:00:00");
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return months[date.getMonth()] + " " + ("0" + date.getDate()).slice(-2) + ", " + date.getFullYear();
}

function getQueryParam(name) {
    var url = new URL(window.location.href);
    return url.searchParams.get(name);
}

document.addEventListener("DOMContentLoaded", function() {
    loadEmployees();
    loadCategories();
    var preselected = getQueryParam("employee_id");
    if (preselected) {
        var checkLoaded = setInterval(function() {
            if (document.getElementById("empSelect").options.length > 1) {
                clearInterval(checkLoaded);
                document.getElementById("empSelect").value = preselected;
                onEmployeeChange();
            }
        }, 100);
    }
});
EOSCRIPT;
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
