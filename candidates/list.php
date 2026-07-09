<?php
$basePath    = '..';
$pageTitle   = 'Candidates';
$activePage  = 'candidates';
$breadcrumb  = 'Home / Recruitment / Candidates';
$pageStyles  = '
.stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: #fff; border-radius: 8px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); display: flex; align-items: center; gap: 14px; }
.stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-card .stat-icon.blue { background: #e8f0fe; color: var(--primary); }
.stat-card .stat-icon.green { background: #e6f4ea; color: var(--success); }
.stat-card .stat-icon.yellow { background: #fef7e0; color: var(--warning); }
.stat-card .stat-icon.red { background: #fce8e6; color: var(--danger); }
.stat-card .stat-info h3 { font-size: 22px; font-weight: 700; line-height: 1.2; }
.stat-card .stat-info p { font-size: 12px; color: var(--gray-500); }
.filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.filter-bar .form-control { width: auto; min-width: 130px; }
.view-candidate-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
.view-candidate-grid .vc-item label { display: block; font-size: 11px; color: var(--gray-500); text-transform: uppercase; font-weight: 600; letter-spacing: .4px; }
.view-candidate-grid .vc-item span { font-size: 14px; font-weight: 500; color: var(--gray-900); }
.view-candidate-grid .vc-item-full { grid-column: 1 / -1; }
';
$pageScripts = '
var candidatesData = [];
var editingCandidateId = null;

function resetCandidateForm() {
    document.getElementById("candidateForm").reset();
    document.getElementById("candModalTitle").innerHTML = "&#x2795; Add Candidate";
    document.getElementById("candSubmitBtn").innerHTML = "&#x2728; Save Candidate";
    editingCandidateId = null;
}

function renderCandidates() {
    var tbody = document.getElementById("candidateTableBody");
    tbody.innerHTML = "";
    var stats = { Applied: 0, Shortlisted: 0, Interview: 0, Offered: 0, Rejected: 0, Joined: 0 };
    candidatesData.forEach(function(c) {
        if (stats[c.status] !== undefined) stats[c.status]++;
        var row = document.createElement("tr");
        row.innerHTML =
            "<td>" + (c.code || "-") + "</td>" +
            "<td><strong>" + c.first_name + " " + c.last_name + "</strong></td>" +
            "<td>" + (c.email || "-") + "</td>" +
            "<td>" + (c.phone || "-") + "</td>" +
            "<td><a href=\"../recruitment/list.php?job_id=" + c.job_id + "\" style=\"color:var(--primary);text-decoration:none;font-weight:500\">" + (c.job_title || "N/A") + "</a></td>" +
            "<td>" + formatDate(c.applied_at) + "</td>" +
            "<td><span class=\"badge " + getBadgeClass(c.status) + "\">" + c.status + "</span></td>" +
            "<td>" +
                "<div class=\"btn-group\">" +
                    "<button class=\"btn btn-sm btn-outline\" onclick=\"window.editCandidate(" + c.id + ")\" style=\"margin-right:4px\">Edit</button>" +
                    "<button class=\"btn btn-sm btn-primary\" onclick=\"window.viewCandidate(" + c.id + ")\" style=\"margin-right:4px\">View</button>" +
                     "<button class=\"btn btn-sm btn-danger\" onclick=\"window.deleteCandidate(" + c.id + ")\" style=\"margin-left:4px\">&#x1F5D1;</button>" +
                    "<select class=\"form-control\" style=\"width:auto;min-width:90px;padding:4px 8px;font-size:12px;\" onchange=\"window.changeStatus(" + c.id + ",this.value)\">" +
                        "<option value=\"\">Status</option>" +
                        "<option value=\"Applied\">Applied</option>" +
                        "<option value=\"Shortlisted\">Shortlisted</option>" +
                        "<option value=\"Interview\">Interview</option>" +
                        "<option value=\"Offered\">Offered</option>" +
                        "<option value=\"Rejected\">Rejected</option>" +
                        "<option value=\"Joined\">Joined</option>" +
                    "</select>" +
                   
                "</div>" +
            "</td>";
        tbody.appendChild(row);
    });
    document.getElementById("statApplied").textContent = stats.Applied;
    document.getElementById("statShortlisted").textContent = stats.Shortlisted;
    document.getElementById("statInterviewed").textContent = stats.Interview;
    document.getElementById("statOffered").textContent = stats.Offered;
    document.getElementById("statRejected").textContent = stats.Rejected;
    document.getElementById("statJoined").textContent = stats.Joined;
}

window.viewCandidate = function(id) {
    var c = null;
    for (var i = 0; i < candidatesData.length; i++) { if (candidatesData[i].id === id) { c = candidatesData[i]; break; } }
    if (!c) return;
    var fields = [
        { label: "Code", val: c.code || "-" },
        { label: "Full Name", val: c.first_name + " " + c.last_name },
        { label: "Email", val: c.email || "-" },
        { label: "Phone", val: c.phone || "-" },
        { label: "NID", val: c.nid || "-" },
        { label: "Blood Group", val: c.blood_group || "-" },
        { label: "Date of Birth", val: c.dob ? formatDate(c.dob) : "-" },
        { label: "Gender", val: c.gender || "-" },
        { label: "Job Applied", val: c.job_title || "N/A" },
        { label: "Department", val: c.dept_name || "N/A" },
        { label: "Current Salary", val: c.current_salary ? formatCurrency(c.current_salary, "BDT ") : "-" },
        { label: "Expected Salary", val: c.expected_salary ? formatCurrency(c.expected_salary, "BDT ") : "-" },
        { label: "Status", val: "<span class=\"badge " + getBadgeClass(c.status) + "\">" + c.status + "</span>" },
        { label: "Applied At", val: c.applied_at ? formatDateTime(c.applied_at) : "-" },
    ];
    var html = "";
    for (var i = 0; i < fields.length; i++) {
        html += "<div class=\"form-group\"><label>" + fields[i].label + "</label><div>" + fields[i].val + "</div></div>";
    }
    if (c.present_address) html += "<div class=\"form-group vc-item-full\"><label>Present Address</label><div>" + c.present_address + "</div></div>";
    if (c.permanent_address) html += "<div class=\"form-group vc-item-full\"><label>Permanent Address</label><div>" + c.permanent_address + "</div></div>";
    document.getElementById("viewCandidateBody").innerHTML = html;
    openModal("viewCandidateModal");
};

window.editCandidate = function(id) {
    var c = null;
    for (var i = 0; i < candidatesData.length; i++) { if (candidatesData[i].id === id) { c = candidatesData[i]; break; } }
    if (!c) return;
    editingCandidateId = c.id;
    document.getElementById("acJobId").value = c.job_id;
    document.getElementById("acFirstName").value = c.first_name;
    document.getElementById("acLastName").value = c.last_name;
    document.getElementById("acEmail").value = c.email;
    document.getElementById("acPhone").value = c.phone;
    document.getElementById("acNid").value = c.nid;
    document.getElementById("acBloodGroup").value = c.blood_group;
    document.getElementById("acDob").value = c.dob;
    document.getElementById("acGender").value = c.gender;
    document.getElementById("acPresentAddress").value = c.present_address;
    document.getElementById("acPermanentAddress").value = c.permanent_address;
    document.getElementById("acCurrentSalary").value = c.current_salary;
    document.getElementById("acExpectedSalary").value = c.expected_salary;
    document.getElementById("acStatus").value = c.status;
    document.getElementById("candModalTitle").innerHTML = "&#x270F; Edit Candidate";
    document.getElementById("candSubmitBtn").innerHTML = "&#x2728; Update Candidate";
    openModal("addCandidateModal");
};

window.deleteCandidate = function(id) {
    if (!confirm("Are you sure you want to delete this candidate?")) return;
    apiPost("ajax/delete.php", { id: id }, function(res) {
        showAlert(res.message || "Candidate deleted!", "success");
        renderPage();
    }, function(err, status) {
        var msg = (typeof err === "object" && err.message) ? err.message : "Delete failed";
        showAlert(msg, "error");
    });
};

window.changeStatus = function(id, newStatus) {
    if (!newStatus) return;
    var c = null;
    for (var i = 0; i < candidatesData.length; i++) { if (candidatesData[i].id === id) { c = candidatesData[i]; break; } }
    if (!c) return;
    apiPost("ajax/update.php", {
        id: id, job_id: c.job_id, first_name: c.first_name, last_name: c.last_name,
        email: c.email, phone: c.phone, nid: c.nid, blood_group: c.blood_group,
        dob: c.dob, gender: c.gender, present_address: c.present_address,
        permanent_address: c.permanent_address, current_salary: c.current_salary,
        expected_salary: c.expected_salary, status: newStatus
    }, function(res) {
        showAlert("Status updated to " + newStatus, "success");
        renderPage();
    }, function(err) {
        showAlert("Status update failed", "error");
    });
};

window.ajaxSaveCandidate = function() {
    var jobId = document.getElementById("acJobId").value;
    var firstName = document.getElementById("acFirstName").value.trim();
    var lastName = document.getElementById("acLastName").value.trim();
    var email = document.getElementById("acEmail").value.trim();
    var phone = document.getElementById("acPhone").value.trim();
    var nid = document.getElementById("acNid").value.trim();
    var bloodGroup = document.getElementById("acBloodGroup").value;
    var dob = document.getElementById("acDob").value;
    var gender = document.getElementById("acGender").value;
    var presentAddress = document.getElementById("acPresentAddress").value.trim();
    var permanentAddress = document.getElementById("acPermanentAddress").value.trim();
    var currentSalary = parseFloat(document.getElementById("acCurrentSalary").value) || 0;
    var expectedSalary = parseFloat(document.getElementById("acExpectedSalary").value) || 0;
    var status = document.getElementById("acStatus").value;

    if (!jobId) { showAlert("Please select a job", "error"); return; }
    if (!firstName) { showAlert("First name is required", "error"); return; }
    if (!lastName) { showAlert("Last name is required", "error"); return; }
    if (!email) { showAlert("Email is required", "error"); return; }
    if (email.indexOf("@") === -1 || email.indexOf(".") === -1) { showAlert("Enter a valid email", "error"); return; }
    if (!status) { showAlert("Status is required", "error"); return; }

    var btn = document.getElementById("candSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "\u23F3 Saving...";

    var payload = {
        job_id: jobId, first_name: firstName, last_name: lastName,
        email: email, phone: phone, nid: nid, blood_group: bloodGroup,
        dob: dob, gender: gender, present_address: presentAddress,
        permanent_address: permanentAddress, current_salary: currentSalary,
        expected_salary: expectedSalary, status: status
    };

    var isEdit = editingCandidateId !== null;
    if (isEdit) payload.id = editingCandidateId;

    apiPost(isEdit ? "ajax/update.php" : "ajax/submit.php", payload, function(res) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert(res.message || "Candidate saved!", "success");
        closeModal("addCandidateModal");
        resetCandidateForm();
        renderPage();
    }, function(err, status) {
        btn.disabled = false;
        btn.innerHTML = origText;
        var msg = (typeof err === "object" && err.message) ? err.message : "Save failed";
        showAlert(msg, "error");
    });
};

function filterByDept() {
    var deptVal = document.getElementById("deptFilter").value.toLowerCase();
    var rows = document.querySelectorAll("#candidateTable tbody tr");
    for (var i = 0; i < rows.length; i++) {
        var jobCell = rows[i].querySelectorAll("td")[4];
        if (!deptVal || (jobCell && jobCell.textContent.toLowerCase().indexOf(deptVal) !== -1)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

function loadDeptFilter() {
    var sel = document.getElementById("deptFilter");
    if (!sel || sel.options.length > 1) return;
    if (typeof DATA !== "undefined" && DATA.departments) {
        DATA.departments.forEach(function(d) {
            var opt = document.createElement("option");
            opt.value = d.name;
            opt.textContent = d.name;
            sel.appendChild(opt);
        });
    }
}

function loadJobSelect() {
    var sel = document.getElementById("acJobId");
    if (!sel || sel.options.length > 1) return;
    apiGet("../recruitment/ajax/list.php", null, function(res) {
        if (res.success && res.data) {
            res.data.forEach(function(j) {
                var opt = document.createElement("option");
                opt.value = j.id;
                opt.textContent = j.title;
                sel.appendChild(opt);
            });
        }
    }, function() {});
}

function renderPage() {
    apiGet("ajax/list.php", null, function(res) {
        if (res.success && res.data) {
            candidatesData = res.data;
        }
        renderCandidates();
    }, function() {
        renderCandidates();
    });
}

loadJobSelect();
loadDeptFilter();
renderPage();
';

ob_start();
?>
<div class="stats-grid" id="statsGrid">
    <div class="stat-card">
        <div class="stat-icon blue">&#x1F4CB;</div>
        <div class="stat-info"><h3 id="statApplied">0</h3><p>Total Applied</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#x2B50;</div>
        <div class="stat-info"><h3 id="statShortlisted">0</h3><p>Shortlisted</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">&#x1F91D;</div>
        <div class="stat-info"><h3 id="statInterviewed">0</h3><p>Interviewed</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">&#x1F4C4;</div>
        <div class="stat-info"><h3 id="statOffered">0</h3><p>Offered</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">&#x2715;</div>
        <div class="stat-info"><h3 id="statRejected">0</h3><p>Rejected</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#x2705;</div>
        <div class="stat-info"><h3 id="statJoined">0</h3><p>Joined</p></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Candidates</h2>
        <div class="filter-bar">
            <select class="form-control" id="statusFilter" onchange="filterTable('statusFilter','candidateTable',6)">
                <option value="">All Status</option>
                <option value="Applied">Applied</option>
                <option value="Shortlisted">Shortlisted</option>
                <option value="Interview">Interview</option>
                <option value="Offered">Offered</option>
                <option value="Rejected">Rejected</option>
                <option value="Joined">Joined</option>
            </select>
            <select class="form-control" id="deptFilter" onchange="filterByDept()">
                <option value="">All Departments</option>
            </select>
            <div class="search-box">
                <span class="search-icon">&#x1F50D;</span>
                <input type="text" id="searchInput" placeholder="Search candidates..." onkeyup="filterTable('searchInput','candidateTable')">
            </div>
            <button class="btn btn-primary" onclick="resetCandidateForm();openModal('addCandidateModal')">+ Add Candidate</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table id="candidateTable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Candidate Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Job Applied</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="candidateTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Candidate Modal -->
<div class="modal-overlay" id="viewCandidateModal" onclick="if(event.target===this)closeModal('viewCandidateModal')">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3>Candidate Details</h3>
            <button class="modal-close" onclick="closeModal('viewCandidateModal')">&times;</button>
        </div>
        <div class="modal-body view-candidate-grid" id="viewCandidateBody"></div>
    </div>
</div>

<!-- Add / Edit Candidate Modal -->
<div class="modal-overlay" id="addCandidateModal" onclick="if(event.target===this)closeModal('addCandidateModal')">
    <div class="modal">
        <div class="modal-header">
            <h3 id="candModalTitle">&#x2795; Add Candidate</h3>
            <button class="modal-close" onclick="closeModal('addCandidateModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="candidateForm" onsubmit="event.preventDefault();window.ajaxSaveCandidate()">
                <div class="form-group">
                    <label>Job Applied <span class="required">*</span></label>
                    <select class="form-control" id="acJobId" required>
                        <option value="">Select Job</option>
                    </select>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="acFirstName" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="acLastName" required>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" class="form-control" id="acEmail" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="acPhone">
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>NID</label>
                        <input type="text" class="form-control" id="acNid">
                    </div>
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select class="form-control" id="acBloodGroup">
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" id="acDob">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" id="acGender">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Present Address</label>
                    <textarea class="form-control" id="acPresentAddress" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Permanent Address</label>
                    <textarea class="form-control" id="acPermanentAddress" rows="2"></textarea>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Current Salary</label>
                        <input type="number" class="form-control" id="acCurrentSalary" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Expected Salary</label>
                        <input type="number" class="form-control" id="acExpectedSalary" min="0" step="0.01">
                    </div>
                </div>
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select class="form-control" id="acStatus" required>
                        <option value="Applied">Applied</option>
                        <option value="Shortlisted">Shortlisted</option>
                        <option value="Interview">Interview</option>
                        <option value="Offered">Offered</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Joined">Joined</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('addCandidateModal');resetCandidateForm()">Cancel</button>
            <button class="btn btn-primary" id="candSubmitBtn" onclick="window.ajaxSaveCandidate()">&#x2728; Save Candidate</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
