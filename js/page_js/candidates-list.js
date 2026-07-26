var candidatesData = [];
var editingCandidateId = null;
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
    renderCandidates();
}

function getSortVal(c) {
    switch (sortCol) {
        case 0: return (c.code || "").toLowerCase();
        case 1: return ((c.first_name || "") + " " + (c.last_name || "")).toLowerCase();
        case 2: return (c.email || "").toLowerCase();
        case 3: return (c.phone || "").toLowerCase();
        case 4: return (c.job_title || "").toLowerCase();
        case 5: return c.applied_at || "";
        case 6: return (c.status_name || "").toLowerCase();
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
    var q = document.getElementById("searchInput").value.trim().toLowerCase();
    var dept = document.getElementById("deptFilter").value.toLowerCase();
    var list = candidatesData.filter(function(c) {
        var text = ((c.first_name || "") + " " + (c.last_name || "") + " " + (c.email || "") + " " + (c.code || "") + " " + (c.phone || "") + " " + (c.job_title || "") + " " + (c.status_name || "")).toLowerCase();
        if (q && text.indexOf(q) === -1) return false;
        if (dept && c.dept_name && c.dept_name.toLowerCase() !== dept) return false;
        return true;
    });
    return sortData(list);
}

function renderPaginationBar(total, totalPages) {
    if (totalPages <= 1) { document.getElementById("paginationBar").innerHTML = ""; return; }
    var html = "";
    html += "<div class=\"rp-pg-info\">Page " + currentPage + " of " + totalPages + " &middot; " + total + " total</div>";
    html += "<div class=\"rp-pg-center\">";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(1)\" " + (currentPage <= 1 ? "disabled" : "") + " title=\"First\">&laquo;</button>";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + (currentPage - 1) + ")\" " + (currentPage <= 1 ? "disabled" : "") + " title=\"Prev\">&lsaquo;</button>";
    var start = Math.max(1, currentPage - 2);
    var end = Math.min(totalPages, currentPage + 2);
    for (var p = start; p <= end; p++) {
        html += "<button class=\"rp-pg-btn" + (p === currentPage ? " rp-active" : "") + "\" onclick=\"goToPage(" + p + ")\">" + p + "</button>";
    }
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + (currentPage + 1) + ")\" " + (currentPage >= totalPages ? "disabled" : "") + " title=\"Next\">&rsaquo;</button>";
    html += "<button class=\"rp-pg-btn\" onclick=\"goToPage(" + totalPages + ")\" " + (currentPage >= totalPages ? "disabled" : "") + " title=\"Last\">&raquo;</button>";
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
    renderCandidates();
}

function changePerPage(n) {
    perPage = parseInt(n);
    currentPage = 1;
    renderCandidates();
}

function applyFilters() {
    document.getElementById("searchClear").style.display = document.getElementById("searchInput").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderCandidates();
}

function clearSearch() {
    document.getElementById("searchInput").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderCandidates();
}

function renderStats() {
    var counts = { total: 0 };
    var statusKey = { "Applied": "applied", "Shortlisted": "shortlisted", "Interview": "interview", "Selected": "selected", "Offered": "offered", "Joined": "joined", "Rejected": "rejected", "Hold": "hold" };
    candidatesData.forEach(function(c) {
        counts.total++;
        var key = statusKey[c.status_name] || null;
        if (key) counts[key] = (counts[key] || 0) + 1;
    });
    var cards = [
        { cls: "cc-total", num: counts.total || 0, label: "Total Candidates" },
        { cls: "cc-applied", num: counts.applied || 0, label: "Applied" },
        { cls: "cc-shortlisted", num: counts.shortlisted || 0, label: "Shortlisted" },
        { cls: "cc-interview", num: counts.interview || 0, label: "Interview" },
        { cls: "cc-selected", num: counts.selected || 0, label: "Selected" },
        { cls: "cc-offered", num: counts.offered || 0, label: "Offered" },
        { cls: "cc-joined", num: counts.joined || 0, label: "Joined" },
        { cls: "cc-rejected", num: counts.rejected || 0, label: "Rejected" },
        { cls: "cc-hold", num: counts.hold || 0, label: "On Hold" }
    ];
    var html = "";
    cards.forEach(function(c) {
        html += "<div class=\"cc-card " + c.cls + "\"><div><div class=\"cc-num\">" + c.num + "</div><div class=\"cc-label\">" + c.label + "</div></div></div>";
    });
    document.getElementById("statsGrid").innerHTML = html;
}

function updateSortArrows() {
    for (var i = 0; i <= 6; i++) {
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

function renderMobileCards(pageData) {
    var container = document.getElementById("mobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F464;</div><h3>No candidates found</h3><p>Try adjusting your search or add a new candidate</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(c) {
        var statusBadge = "<span class=\"badge " + getBadgeClass(c.status_name) + "\">" + (c.status_name || "Unknown") + "</span>";
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + c.first_name + " " + c.last_name + "<small>" + (c.code || "") + " &middot; " + (c.job_title || "N/A") + "</small></div>";
        html += "<div class=\"rp-mob-badges\">" + statusBadge + "</div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Email</label><span>" + (c.email || "-") + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Phone</label><span>" + (c.phone || "-") + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Applied</label><span>" + formatDate(c.applied_at) + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Department</label><span>" + (c.dept_name || "N/A") + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">";
        html += "<button class=\"btn btn-outline btn-sm\" onclick=\"window.editCandidate(" + c.id + ")\">Edit</button>";
        html += "<button class=\"btn btn-outline btn-sm rp-btn-danger\" onclick=\"window.deleteCandidate(" + c.id + ")\">Delete</button>";
        html += "<button class=\"btn btn-primary btn-sm\" onclick=\"window.viewCandidate(" + c.id + ")\">View</button>";
        html += "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function renderCandidates() {
    var filtered = getFilteredData();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    document.getElementById("resultCount").textContent = filtered.length + " candidate" + (filtered.length !== 1 ? "s" : "");

    var tbody = document.getElementById("candidateTableBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"8\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F464;</div><h3>No candidates found</h3><p>Try adjusting your search or add a new candidate</p></div></td></tr>";
    } else {
        pageData.forEach(function(c) {
            var statusBadge = "<span class=\"badge " + getBadgeClass(c.status_name) + "\">" + (c.status_name || "Unknown") + "</span>";
            var row = document.createElement("tr");
            row.innerHTML =
                "<td>" + (c.code || "-") + "</td>" +
                "<td><strong>" + c.first_name + " " + c.last_name + "</strong></td>" +
                "<td>" + (c.email || "-") + "</td>" +
                "<td>" + (c.phone || "-") + "</td>" +
                "<td><a href=\"../recruitment/list.php?job_id=" + c.job_id + "\" style=\"color:var(--primary);text-decoration:none;font-weight:500\">" + (c.job_title || "N/A") + "</a></td>" +
                "<td>" + formatDate(c.applied_at) + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text\" onclick=\"window.editCandidate(" + c.id + ")\">Edit</button><button class=\"rp-btn-text rp-btn-view\" onclick=\"window.viewCandidate(" + c.id + ")\">View</button><button class=\"rp-btn-text rp-btn-danger\" onclick=\"window.deleteCandidate(" + c.id + ")\">Delete</button></div></td>";
            tbody.appendChild(row);
        });
    }

    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, totalPages);
    renderStats();
    updateSortArrows();
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
        { label: "Status", val: "<span class=\"badge " + getBadgeClass(c.status_name) + "\">" + (c.status_name || "Unknown") + "</span>" },
        { label: "Applied At", val: c.applied_at ? formatDateTime(c.applied_at) : "-" },
    ];
    var html = "";
    for (var i = 0; i < fields.length; i++) {
        html += "<div class=\"vc-item\"><label>" + fields[i].label + "</label><span>" + fields[i].val + "</span></div>";
    }
    if (c.present_address) html += "<div class=\"vc-item vc-item-full\"><label>Present Address</label><span>" + c.present_address + "</span></div>";
    if (c.permanent_address) html += "<div class=\"vc-item vc-item-full\"><label>Permanent Address</label><span>" + c.permanent_address + "</span></div>";
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
    document.getElementById("candModalTitle").innerHTML = "Edit Candidate";
    document.getElementById("candSubmitBtn").innerHTML = "Update Candidate";
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
    if (!jobId) { showAlert("Please select a job", "error"); return; }
    if (!firstName) { showAlert("First name is required", "error"); return; }
    if (!lastName) { showAlert("Last name is required", "error"); return; }
    if (!email) { showAlert("Email is required", "error"); return; }
    if (email.indexOf("@") === -1 || email.indexOf(".") === -1) { showAlert("Enter a valid email", "error"); return; }

    var btn = document.getElementById("candSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "Saving...";

    var payload = {
        job_id: jobId, first_name: firstName, last_name: lastName,
        email: email, phone: phone, nid: nid, blood_group: bloodGroup,
        dob: dob, gender: gender, present_address: presentAddress,
        permanent_address: permanentAddress, current_salary: currentSalary,
        expected_salary: expectedSalary
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

function resetCandidateForm() {
    document.getElementById("candidateForm").reset();
    document.getElementById("candModalTitle").innerHTML = "Add Candidate";
    document.getElementById("candSubmitBtn").innerHTML = "Save Candidate";
    editingCandidateId = null;
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
        currentPage = 1;
        renderCandidates();
    }, function() {
        renderCandidates();
    });
}

loadJobSelect();
loadDeptFilter();
renderPage();