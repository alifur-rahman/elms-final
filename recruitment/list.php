<?php
$basePath    = '..';
$pageTitle   = 'Recruitment Pipeline';
$activePage  = 'recruitment';
$breadcrumb  = 'Home / Recruitment / Pipeline';
$pageStyles  = '
.pipeline-flow { display: flex; align-items: center; gap: 0; margin-bottom: 24px; overflow-x: auto; padding: 16px 20px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
.pf-step { display: flex; flex-direction: column; align-items: center; min-width: 80px; padding: 8px 12px; text-align: center; }
.pf-step .pf-count { font-size: 22px; font-weight: 700; color: var(--primary); }
.pf-step .pf-icon { font-size: 18px; margin: 4px 0; }
.pf-step .pf-label { font-size: 11px; color: var(--gray-500); white-space: nowrap; }
.pf-connector { width: 40px; height: 2px; background: var(--gray-300); flex-shrink: 0; }
.quick-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.qs-card { background: #fff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); display: flex; align-items: center; gap: 14px; }
.qs-card .qs-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.qs-card .qs-icon.blue { background: #e8f0fe; color: var(--primary); }
.qs-card .qs-icon.green { background: #e6f4ea; color: var(--success); }
.qs-card .qs-icon.yellow { background: #fef7e0; color: var(--warning); }
.qs-card .qs-icon.red { background: #fce8e6; color: var(--danger); }
.qs-card .qs-info h4 { font-size: 22px; font-weight: 700; line-height: 1.2; }
.qs-card .qs-info p { font-size: 12px; color: var(--gray-500); }
.view-job-details { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
.view-job-details .vj-item label { display: block; font-size: 11px; color: var(--gray-500); text-transform: uppercase; font-weight: 600; letter-spacing: .4px; }
.view-job-details .vj-item span { font-size: 14px; font-weight: 500; color: var(--gray-900); }
.view-job-desc { margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--gray-200); }
.view-job-desc h4 { font-size: 13px; font-weight: 600; margin-bottom: 8px; }
.view-job-desc p { font-size: 13px; color: var(--gray-600); line-height: 1.6; white-space: pre-line; }
';
$pageScripts = '
var reqSkills = [];
var jobsData = [];
var editingJobId = null;

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
        for (var i = 0; i < DATA.jobs.length; i++) { if (DATA.jobs[i].id === jobId) { job = DATA.jobs[i]; break; } }
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

function renderTable() {
    var tbody = document.getElementById("jobsBody");
    tbody.innerHTML = "";
    jobsData.forEach(function(job) {
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
            "<td><button class=\"btn btn-sm btn-outline\" onclick=\"window.editJob(" + job.id + ")\" style=\"margin-right:4px\">Edit</button><button class=\"btn btn-sm btn-primary\" onclick=\"window.viewJob(" + job.id + ")\">View</button></td>";
        tbody.appendChild(row);
    });
}

function renderPipeline() {
    var pipelineStages = [
        { key: "Applied", icon: "\uD83D\uDC64", filter: function(c) { return c.status === "Applied"; } },
        { key: "Shortlisted", icon: "\uD83D\uDCCB", filter: function(c) { return c.status === "Shortlisted"; } },
        { key: "Interview", icon: "\uD83E\uDD1D", filter: function(c) { return c.status === "Interview"; } },
        { key: "Selected", icon: "\u2705", filter: function(c) { return c.status === "Selected"; } },
        { key: "Offered", icon: "\uD83D\uDCC4", filter: function(c) { return c.status === "Offered"; } },
        { key: "Joined", icon: "\uD83D\uDEAA", filter: function(c) { return c.status === "Joined"; } }
    ];
    var pipeHtml = "";
    for (var i = 0; i < pipelineStages.length; i++) {
        var stage = pipelineStages[i];
        var count = typeof DATA !== "undefined" && DATA.candidates ? DATA.candidates.filter(stage.filter).length : 0;
        pipeHtml += "<div class=\"pf-step\"><div class=\"pf-count\">" + count + "</div><div class=\"pf-icon\">" + stage.icon + "</div><div class=\"pf-label\">" + stage.key + "</div></div>";
        if (i < pipelineStages.length - 1) pipeHtml += "<div class=\"pf-connector\"></div>";
    }
    document.getElementById("pipelineFlow").innerHTML = pipeHtml;
}

function renderStats() {
    var totalPos = jobsData.length;
    var activePos = 0;
    var remaining = 0;
    jobsData.forEach(function(j) {
        if (j.status === "Active") activePos++;
        remaining += j.vacancies - (j.filled || 0);
    });
    var stats = [
        { icon: "\uD83C\uDFAF", color: "blue", count: totalPos, label: "Total Positions" },
        { icon: "\u2705", color: "green", count: activePos, label: "Active" },
        { icon: "\uD83D\uDC65", color: "blue", count: typeof DATA !== "undefined" && DATA.candidates ? DATA.candidates.length : 0, label: "Total Candidates" },
        { icon: "\uD83D\uDCC4", color: "yellow", count: typeof DATA !== "undefined" && DATA.offers ? DATA.offers.filter(function(o){ return o.status === "Pending"; }).length : 0, label: "Pending Offers" },
        { icon: "\uD83D\uDEAA", color: "green", count: typeof DATA !== "undefined" && DATA.candidates ? DATA.candidates.filter(function(c){ return c.status === "Joined"; }).length : 0, label: "Joined" },
        { icon: "\uD83D\uDCCA", color: "red", count: remaining, label: "Remaining Vacancies" }
    ];
    var statsHtml = "";
    stats.forEach(function(s) {
        statsHtml += "<div class=\"qs-card\"><div class=\"qs-icon " + s.color + "\">" + s.icon + "</div><div class=\"qs-info\"><h4>" + s.count + "</h4><p>" + s.label + "</p></div></div>";
    });
    document.getElementById("quickStats").innerHTML = statsHtml;
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
        renderTable();
        renderPipeline();
        renderStats();
        initDeptSelect();
        if (callback) callback();
    }, function() {
        renderTable();
        renderPipeline();
        renderStats();
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
<div class="card-header" style="background:#fff;border-radius:8px 8px 0 0;margin-bottom:0">
    <h2>Open Positions</h2>
    <div class="btn-group">
        <div class="search-box">
            <span class="search-icon">&#x1F50D;</span>
            <input type="text" id="searchJobs" placeholder="Search positions..." onkeyup="filterTable('searchJobs','jobsTable')" style="border:none;padding:7px 8px;font-size:13px;outline:none;width:200px;font-family:inherit">
        </div>
        <button class="btn btn-primary btn-sm" onclick="resetRequisitionForm();openModal('createRequisitionModal')">+ Create Requisition</button>
    </div>
</div>
<div class="card mb-24" style="border-radius:0 0 8px 8px;margin-top:0">
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="jobsTable">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Vacancies</th>
                        <th>Filled</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="jobsBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="card-header" style="background:#fff;border-radius:8px 8px 0 0;margin-bottom:0">
    <h2>Candidate Pipeline</h2>
</div>
<div class="card mb-24" style="border-radius:0 0 8px 8px;margin-top:0">
    <div class="card-body">
        <div class="pipeline-flow" id="pipelineFlow"></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Quick Stats</h2></div>
    <div class="card-body">
        <div class="quick-stats" id="quickStats"></div>
    </div>
</div>

<div class="modal-overlay" id="viewJobModal" onclick="if(event.target===this)closeModal('viewJobModal')">
    <div class="modal">
        <div class="modal-header">
            <h3 id="viewJobTitle">Job Details</h3>
            <button class="modal-close" onclick="closeModal('viewJobModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="view-job-details" id="viewJobDetails"></div>
            <div class="view-job-desc" id="viewJobDesc"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline btn-sm" onclick="closeModal('viewJobModal')">Close</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="createRequisitionModal" onclick="if(event.target===this)closeModal('createRequisitionModal')">
    <div class="modal modal-requisition">
        <div class="modal-header">
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
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('createRequisitionModal');resetRequisitionForm()">Cancel</button>
            <button class="btn btn-primary" id="reqSubmitBtn" onclick="window.ajaxSubmitRequisition()">&#x2728; Create Requisition</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
