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