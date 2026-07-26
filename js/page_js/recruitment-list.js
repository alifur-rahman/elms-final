var reqSkills = [];
var jobsData = [];
var editingJobId = null;
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function sortBy(col) {
    if (sortCol === col) {
        sortDir = sortDir === "asc" ? "desc" : "asc";
    } else {
        sortCol = col;
        sortDir = "asc";
    }
    currentPage = 1;
    renderTable();
}

function getSortVal(j) {
    switch (sortCol) {
        case 0: return (j.title || "").toLowerCase();
        case 1: return (j.dept_name || getDeptName(j.dept_id) || "").toLowerCase();
        case 2: return (j.location || "").toLowerCase();
        case 3: return parseInt(j.vacancies) || 0;
        case 4: return parseInt(j.filled) || 0;
        case 5: return (j.status || "").toLowerCase();
        case 6: return (j.priority || "").toLowerCase();
        case 7: return j.deadline || "";
        default: return "";
    }
}

function sortData(arr) {
    if (sortCol < 0) return arr;
    return arr.slice().sort(function(a, b) {
        var va = getSortVal(a);
        var vb = getSortVal(b);
        if (va < vb) return sortDir === "asc" ? -1 : 1;
        if (va > vb) return sortDir === "asc" ? 1 : -1;
        return 0;
    });
}

function getFilteredData() {
    var q = document.getElementById("searchJobs").value.trim().toLowerCase();
    var list = jobsData.filter(function(j) {
        var text = (j.title + " " + (j.dept_name || getDeptName(j.dept_id)) + " " + j.location + " " + (j.status || "")).toLowerCase();
        return !q || text.indexOf(q) !== -1;
    });
    return sortData(list);
}

function updateSortArrows() {
    for (var i = 0; i <= 7; i++) {
        var el = document.getElementById("sortArrow" + i);
        if (!el) continue;
        if (i === sortCol) {
            el.textContent = sortDir === "asc" ? "\u25B2" : "\u25BC";
            el.parentNode.classList.add("rp-sorted");
        } else {
            el.textContent = "\u2195";
            el.parentNode.classList.remove("rp-sorted");
        }
    }
}

function renderPaginationBar(total, totalPages) {
    if (totalPages <= 1) { document.getElementById("paginationBar").innerHTML = ""; return; }
    var html = "";
    html += "<div class=\"rp-pg-info\">Page " + currentPage + " of " + totalPages + " &middot; " + total + " total</div>";
    html += "<div class=\"rp-pg-center\">";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(1)\" " + (currentPage <= 1 ? "disabled" : "") + " title=\"First\">&#xAB;</button>";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + (currentPage - 1) + ")\" " + (currentPage <= 1 ? "disabled" : "") + " title=\"Prev\">&#x2039;</button>";
    var start = Math.max(1, currentPage - 2);
    var end = Math.min(totalPages, currentPage + 2);
    for (var p = start; p <= end; p++) {
        html += "<button class=\"rp-pg-btn" + (p === currentPage ? " rp-active" : "") + "\" onclick=\"goToPage(" + p + ")\">" + p + "</button>";
    }
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + (currentPage + 1) + ")\" " + (currentPage >= totalPages ? "disabled" : "") + " title=\"Next\">&#x203A;</button>";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + totalPages + ")\" " + (currentPage >= totalPages ? "disabled" : "") + " title=\"Last\">&#xBB;</button>";
    html += "</div>";
    html += "<div class=\"rp-pg-rows\">Show <select onchange=\"changePerPage(this.value)\">";
    [10, 20, 50, 100].forEach(function(n) {
        html += "<option value=\"" + n + "\"" + (n === perPage ? " selected" : "") + ">" + n + "</option>";
    });
    html += "</select></div>";
    document.getElementById("paginationBar").innerHTML = html;
}

function goToPage(p) {
    var filtered = getFilteredData();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (p < 1) p = 1;
    if (p > totalPages) p = totalPages;
    if (p === currentPage) return;
    currentPage = p;
    renderTable();
}

function changePerPage(n) {
    perPage = parseInt(n);
    currentPage = 1;
    renderTable();
}

function applyFilters() {
    var val = document.getElementById("searchJobs").value;
    document.getElementById("searchClear").style.display = val.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}

function clearSearch() {
    document.getElementById("searchJobs").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function resetRequisitionForm() {
    document.getElementById("requisitionForm").reset();
    document.getElementById("reqModalTitle").innerHTML = "&#x1F4CB; Create New Requisition";
    document.getElementById("reqSubmitBtn").innerHTML = "&#x2728; Create Requisition";
    editingJobId = null;
    reqSkills = [];
    renderSkills();
    var d = new Date(Date.now() + 90 * 24 * 60 * 60 * 1000);
    document.getElementById("reqDeadline").value = d.toISOString().split("T")[0];
}

window.addSkill = function() {
    var input = document.getElementById("skillsInput");
    var val = input.value.trim().replace(/,/g, "");
    if (!val) return;
    if (reqSkills.indexOf(val) === -1) {
        reqSkills.push(val);
        renderSkills();
    }
    input.value = "";
};

function renderSkills() {
    var container = document.getElementById("skillsTags");
    container.innerHTML = "";
    reqSkills.forEach(function(s, i) {
        var tag = document.createElement("span");
        tag.className = "tag";
        tag.innerHTML = s + "<span class=\"tag-remove\" onclick=\"window.removeSkill(" + i + ")\">&times;</span>";
        container.appendChild(tag);
    });
}

window.removeSkill = function(idx) {
    reqSkills.splice(idx, 1);
    renderSkills();
};

window.ajaxSubmitRequisition = function() {
    var title = document.getElementById("reqJobTitle").value.trim();
    var dept = document.getElementById("reqDepartment").value;
    var location = document.getElementById("reqLocation").value.trim();
    var type = document.getElementById("reqType").value;
    var vacancies = parseInt(document.getElementById("reqVacancies").value);
    var deadline = document.getElementById("reqDeadline").value;
    var education = document.getElementById("reqEducation").value;
    var experience = parseFloat(document.getElementById("reqExperience").value) || 0;
    var salaryMin = parseFloat(document.getElementById("reqSalaryMin").value) || 0;
    var salaryMax = parseFloat(document.getElementById("reqSalaryMax").value) || 0;
    var description = document.getElementById("reqDescription").value.trim();
    var responsibilities = document.getElementById("reqResponsibilities").value.trim();
    var benefits = document.getElementById("reqBenefits").value.trim();
    var priority = document.getElementById("reqPriority").value;
    var status = document.getElementById("reqStatus").value;

    if (!title) { showAlert("Please enter a job title", "error"); return; }
    if (!dept) { showAlert("Please select a department", "error"); return; }
    if (!deadline) { showAlert("Please select a deadline date", "error"); return; }

    var btn = document.getElementById("reqSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "\u23F3 Submitting...";

    var payload = {
        title: title, dept_id: dept, location: location, type: type,
        vacancies: vacancies, deadline: deadline, education: education,
        experience: experience, salary_min: salaryMin, salary_max: salaryMax,
        skills: reqSkills.join(", "), description: description,
        responsibilities: responsibilities, benefits: benefits,
        priority: priority, status: status
    };

    var isEdit = editingJobId !== null;
    if (isEdit) payload.id = editingJobId;

    apiPost(isEdit ? "ajax/update.php" : "ajax/submit.php", payload, function(res) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert(res.message || "Requisition saved!", "success");
        closeModal("createRequisitionModal");
        resetRequisitionForm();
        renderPage();
    }, function(err, status) {
        btn.disabled = false;
        btn.innerHTML = origText;
        var msg = (typeof err === "object" && err.message) ? err.message : "Submission failed";
        showAlert(msg, "error");
    });
};

window.viewJob = function(jobId) {
    var job = null;
    for (var i = 0; i < jobsData.length; i++) { if (jobsData[i].id === jobId) { job = jobsData[i]; break; } }
    if (!job) {
        if (typeof DATA !== "undefined" && DATA.jobs) {
            for (var i = 0; i < DATA.jobs.length; i++) { if (DATA.jobs[i].id === jobId) { job = DATA.jobs[i]; break; } }
        }
    }
    if (!job) return;
    document.getElementById("viewJobTitle").textContent = job.title;
    var deptName = job.dept_name || getDeptName(job.dept_id);
    var statusBadge = "<span class=\"badge " + getBadgeClass(job.status) + "\">" + job.status + "</span>";
    var priorityBadge = "<span class=\"badge " + getBadgeClass(job.priority) + "\">" + job.priority + "</span>";
    var details = document.getElementById("viewJobDetails");
    details.innerHTML =
        "<div class=\"vj-item\"><label>Department</label><span>" + deptName + "</span></div>" +
        "<div class=\"vj-item\"><label>Location</label><span>" + job.location + "</span></div>" +
        "<div class=\"vj-item\"><label>Type</label><span>" + job.type + "</span></div>" +
        "<div class=\"vj-item\"><label>Vacancies</label><span>" + job.vacancies + "</span></div>" +
        "<div class=\"vj-item\"><label>Filled</label><span>" + (job.filled || 0) + "</span></div>" +
        "<div class=\"vj-item\"><label>Experience</label><span>" + job.experience + " years</span></div>" +
        "<div class=\"vj-item\"><label>Education</label><span>" + job.education + "</span></div>" +
        "<div class=\"vj-item\"><label>Salary Range</label><span>" + formatCurrency(job.salary_min, "$") + " - " + formatCurrency(job.salary_max, "$") + "</span></div>" +
        "<div class=\"vj-item\"><label>Deadline</label><span>" + formatDate(job.deadline) + "</span></div>" +
        "<div class=\"vj-item\"><label>Skills</label><span>" + (job.skills || "N/A") + "</span></div>" +
        "<div class=\"vj-item\"><label>Priority</label><span>" + priorityBadge + "</span></div>" +
        "<div class=\"vj-item\"><label>Status</label><span>" + statusBadge + "</span></div>";
    var descDiv = document.getElementById("viewJobDesc");
    var descHtml = "";
    if (job.description) descHtml += "<h4>\uD83D\uDCC4 Description</h4><p>" + job.description + "</p>";
    if (job.responsibilities) descHtml += "<h4 class=\"mt-16\">\u2713 Responsibilities</h4><p>" + job.responsibilities + "</p>";
    if (job.benefits) descHtml += "<h4 class=\"mt-16\">\uD83C\uDF81 Benefits</h4><p>" + job.benefits + "</p>";
    descDiv.innerHTML = descHtml;
    openModal("viewJobModal");
};

window.editJob = function(jobId) {
    var job = null;
    for (var i = 0; i < jobsData.length; i++) { if (jobsData[i].id === jobId) { job = jobsData[i]; break; } }
    if (!job) return;
    editingJobId = job.id;
    document.getElementById("reqJobTitle").value = job.title;
    document.getElementById("reqDepartment").value = job.dept_id;
    document.getElementById("reqLocation").value = job.location;
    document.getElementById("reqType").value = job.type;
    document.getElementById("reqVacancies").value = job.vacancies;
    document.getElementById("reqDeadline").value = job.deadline;
    document.getElementById("reqEducation").value = job.education;
    document.getElementById("reqExperience").value = job.experience;
    document.getElementById("reqSalaryMin").value = job.salary_min;
    document.getElementById("reqSalaryMax").value = job.salary_max;
    document.getElementById("reqDescription").value = job.description;
    document.getElementById("reqResponsibilities").value = job.responsibilities;
    document.getElementById("reqBenefits").value = job.benefits;
    document.getElementById("reqPriority").value = job.priority;
    document.getElementById("reqStatus").value = job.status;
    reqSkills = [];
    if (job.skills) {
        var parts = job.skills.split(",");
        for (var i = 0; i < parts.length; i++) { var s = parts[i].trim(); if (s) reqSkills.push(s); }
    }
    renderSkills();
    document.getElementById("reqModalTitle").innerHTML = "&#x1F4CB; Edit Requisition";
    document.getElementById("reqSubmitBtn").innerHTML = "&#x2728; Update Requisition";
    openModal("createRequisitionModal");
};

function renderMobileCards(pageData) {
    var container = document.getElementById("mobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F50D;</div><h3>No positions found</h3><p>Try adjusting your search</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(job) {
        var deptName = job.dept_name || getDeptName(job.dept_id);
        var statusBadge = "<span class=\"badge " + getBadgeClass(job.status) + "\">" + job.status + "</span>";
        var priorityBadge = "<span class=\"badge " + getBadgeClass(job.priority) + "\">" + job.priority + "</span>";
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + job.title + "<small>" + deptName + " &middot; " + job.location + "</small></div>";
        html += "<div class=\"rp-mob-badges\">" + statusBadge + "</div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Vacancies</label><span>" + job.vacancies + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Filled</label><span>" + (job.filled || 0) + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Deadline</label><span>" + formatDate(job.deadline) + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Priority</label><span>" + priorityBadge + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">";
        html += "<button class=\"btn btn-outline btn-sm\" onclick=\"window.editJob(" + job.id + ")\">Edit</button>";
        html += "<button class=\"btn btn-primary btn-sm\" onclick=\"window.viewJob(" + job.id + ")\">View Details</button>";
        html += "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function renderTable() {
    var filtered = getFilteredData();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    document.getElementById("resultCount").textContent = filtered.length + " position" + (filtered.length !== 1 ? "s" : "");

    // Desktop table
    var tbody = document.getElementById("jobsBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"9\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4ED;</div><h3>No positions found</h3><p>Try adjusting your search or create a new requisition</p></div></td></tr>";
    } else {
        pageData.forEach(function(job) {
            var deptName = job.dept_name || getDeptName(job.dept_id);
            var statusBadge = "<span class=\"badge " + getBadgeClass(job.status) + "\">" + job.status + "</span>";
            var priorityBadge = "<span class=\"badge " + getBadgeClass(job.priority) + "\">" + job.priority + "</span>";
            var row = document.createElement("tr");
            row.innerHTML =
                "<td><strong>" + job.title + "</strong></td>" +
                "<td>" + deptName + "</td>" +
                "<td>" + job.location + "</td>" +
                "<td>" + job.vacancies + "</td>" +
                "<td>" + (job.filled || 0) + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td>" + priorityBadge + "</td>" +
                "<td>" + formatDate(job.deadline) + "</td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text\" onclick=\"window.editJob(" + job.id + ")\">Edit</button><button class=\"rp-btn-text rp-btn-view\" onclick=\"window.viewJob(" + job.id + ")\">View</button></div></td>";
            tbody.appendChild(row);
        });
    }

    // Mobile cards
    renderMobileCards(pageData);

    renderPaginationBar(filtered.length, totalPages);
    updateSortArrows();
}

function initDeptSelect() {
    var deptSelect = document.getElementById("reqDepartment");
    if (deptSelect && deptSelect.options.length <= 1) {
        if (typeof DATA !== "undefined" && DATA.departments) {
            DATA.departments.forEach(function(d) {
                var opt = document.createElement("option");
                opt.value = d.id;
                opt.textContent = d.name;
                deptSelect.appendChild(opt);
            });
        }
        var today = new Date();
        var threeMonthsLater = new Date(today.getFullYear(), today.getMonth() + 3, today.getDate());
        document.getElementById("reqDeadline").value = threeMonthsLater.toISOString().split("T")[0];
    }
}

function renderPage(callback) {
    apiGet("ajax/list.php", null, function(res) {
        if (res.success && res.data) {
            jobsData = res.data;
        }
        currentPage = 1;
        renderTable();
        initDeptSelect();
        if (callback) callback();
    }, function() {
        currentPage = 1;
        renderTable();
        initDeptSelect();
        if (callback) callback();
    });
}

var BASE_URL = "..";
renderPage(function() {
    var params = new URLSearchParams(window.location.search);
    var jobId = params.get("job_id");
    if (jobId) {
        var id = parseInt(jobId);
        setTimeout(function() { window.viewJob(id); }, 200);
    }
});