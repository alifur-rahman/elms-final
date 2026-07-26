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