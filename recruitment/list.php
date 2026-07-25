<?php
$basePath    = '..';
$pageTitle   = 'Recruitment Pipeline';
$activePage  = 'recruitment';
$breadcrumb  = 'Home / Recruitment / Pipeline';
$pageStyles  = '
/* ===== RECRUIT PAGE ===== */
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.rp-top h1{font-size:20px;font-weight:700;color:var(--gray-900);margin:0;white-space:nowrap}
.rp-top h1 small{font-size:13px;font-weight:400;color:var(--gray-400);margin-left:8px}
.rp-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.rp-search{position:relative}
.rp-search input{width:240px;padding:9px 12px 9px 36px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit}
.rp-search input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.rp-search .rp-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:14px;pointer-events:none}
.rp-search .rp-search-clear{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:16px;display:none;padding:2px;line-height:1}
.rp-search .rp-search-clear:hover{color:var(--gray-700)}
.rp-count{font-size:13px;color:var(--gray-500);white-space:nowrap}
/* Table card */
.rp-card{background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);overflow:hidden}
.rp-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
.rp-table{width:100%;border-collapse:collapse}
.rp-table thead th{background:var(--gray-50);padding:12px 16px;font-size:12px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.4px;text-align:left;border-bottom:1px solid var(--gray-100);white-space:nowrap;position:sticky;top:0;z-index:1}
.rp-table thead th.rp-sortable{cursor:pointer;user-select:none;transition:color .15s}
.rp-table thead th.rp-sortable:hover{color:var(--primary)}
.rp-table thead th.rp-sorted{color:var(--primary)}
.rp-sort-arrow{font-size:10px;margin-left:4px;opacity:.5}
.rp-sorted .rp-sort-arrow{opacity:1}
.rp-table tbody tr{border-bottom:1px solid var(--gray-50);transition:background .15s}
.rp-table tbody tr:last-child{border-bottom:none}
.rp-table tbody tr:hover{background:var(--gray-50)}
.rp-table td{padding:14px 16px;font-size:13px;color:var(--gray-700);vertical-align:middle}
.rp-table td strong{color:var(--gray-900);font-weight:600}
.rp-table .rp-actions-cell{display:flex;gap:6px;white-space:nowrap}
.rp-table .rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit}
.rp-table .rp-btn-text:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-view{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-table .rp-btn-text.rp-btn-view:hover{background:var(--primary);color:#fff}
.rp-empty{text-align:center;padding:48px 24px;color:var(--gray-400)}
.rp-empty-icon{font-size:40px;margin-bottom:8px}
.rp-empty h3{font-size:15px;color:var(--gray-600);margin:0 0 4px}
.rp-empty p{font-size:13px;margin:0}
/* Pagination */
.rp-pagination{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-top:1px solid var(--gray-100);flex-wrap:wrap;gap:10px}
.rp-pg-info{font-size:13px;color:var(--gray-500)}
.rp-pg-center{display:flex;align-items:center;gap:6px}
.rp-pg-btn{min-width:32px;height:32px;padding:0 10px;border:1px solid var(--gray-200);border-radius:6px;background:#fff;font-size:13px;cursor:pointer;color:var(--gray-600);transition:all .15s;font-family:inherit;display:inline-flex;align-items:center;justify-content:center}
.rp-pg-btn:hover:not(:disabled){border-color:var(--primary);color:var(--primary)}
.rp-pg-btn.rp-active{background:var(--primary);color:#fff;border-color:var(--primary)}
.rp-pg-btn:disabled{opacity:.35;cursor:default}
.rp-pg-rows{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--gray-500)}
.rp-pg-rows select{padding:5px 8px;border:1px solid var(--gray-200);border-radius:6px;font-size:13px;font-family:inherit;background:#fff;cursor:pointer}
/* Mobile cards */
.rp-mobile-list{display:none}
/* ===== MODAL OVERRIDES ===== */
.view-job-details{display:grid;grid-template-columns:1fr 1fr;gap:14px 24px}
.view-job-details .vj-item label{display:block;font-size:11px;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.4px;margin-bottom:2px}
.view-job-details .vj-item span{font-size:14px;font-weight:500;color:var(--gray-900)}
.view-job-desc{margin-top:20px;padding-top:16px;border-top:1px solid var(--gray-200)}
.view-job-desc h4{font-size:13px;font-weight:600;color:var(--gray-700);margin-bottom:8px}
.view-job-desc p{font-size:13px;color:var(--gray-600);line-height:1.6;white-space:pre-line}
/* ===== RESPONSIVE ===== */
@media(max-width:900px){
 .rp-top h1{font-size:18px}
 .rp-search input{width:200px}
}
@media(max-width:768px){
 .page-content{padding:16px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
 .rp-top h1 small{display:block;margin:4px 0 0;font-size:12px}
 .rp-actions{width:100%}
 .rp-search{flex:1}
 .rp-search input{width:100%!important}
 .rp-count{width:100%;text-align:center}
 /* Hide table, show cards */
 .rp-table-wrap{display:none}
 .rp-mobile-list{display:block}
 .rp-mob-card{background:#fff;border-radius:10px;padding:16px;margin:8px 16px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid var(--gray-100)}
 .rp-mob-card:last-child{margin-bottom:16px}
 .rp-mob-top{display:flex;align-items:start;justify-content:space-between;gap:10px;margin-bottom:10px}
 .rp-mob-title{font-size:15px;font-weight:600;color:var(--gray-900);margin:0}
 .rp-mob-title small{font-size:12px;font-weight:400;color:var(--gray-400);display:block;margin-top:2px}
 .rp-mob-badges{display:flex;gap:6px;flex-shrink:0}
 .rp-mob-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 16px;margin-bottom:12px}
 .rp-mob-field label{display:block;font-size:11px;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px}
 .rp-mob-field span{font-size:13px;color:var(--gray-700);font-weight:500}
 .rp-mob-footer{display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--gray-100)}
 .rp-mob-footer .btn{flex:1;justify-content:center;font-size:12px;padding:7px 12px}
 .view-job-details{grid-template-columns:1fr;gap:10px 16px}
}
@media(max-width:480px){
 .page-content{padding:12px}
 .rp-top h1{font-size:16px}
 .rp-mob-card{padding:14px;margin:6px 12px}
 .rp-mob-grid{grid-template-columns:1fr;gap:8px}
 .rp-mob-top{flex-direction:column}
 .rp-pagination{flex-direction:column;align-items:center;gap:8px}
 .rp-pg-center{flex-wrap:wrap;justify-content:center}
 .rp-pg-rows{width:100%;justify-content:center}
}
';
$pageScripts = '
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
';

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
