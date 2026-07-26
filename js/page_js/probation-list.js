var allData = [];
var eligibleData = [];
var departments = [];
var currentTab = "eligible";
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".scr-tab").forEach(function(b) { b.classList.remove("active"); });
    document.querySelectorAll(".tab-panel").forEach(function(p) { p.classList.remove("active"); });
    document.getElementById("tabBtn" + tab).classList.add("active");
    document.getElementById("tab" + tab).classList.add("active");
    sortCol = -1;
    currentPage = 1;
    document.getElementById("searchInput").value = "";
    document.getElementById("searchClear").style.display = "none";
    renderTable();
}

function sortBy(col) {
    if (sortCol === col) { sortDir = sortDir === "asc" ? "desc" : "asc"; }
    else { sortCol = col; sortDir = "asc"; }
    currentPage = 1;
    renderTable();
}

function getSortVal(o) {
    switch (sortCol) {
        case 0: return (o.employee_name || "").toLowerCase();
        case 1: return currentTab === "appraisals" ? (o.dept_name || "").toLowerCase() : (o.designation_name || "").toLowerCase();
        case 2: return o.joining_date || "";
        case 3: return currentTab === "appraisals" ? (o.end_date || "") : (o.days_left !== null ? o.days_left : 9999);
        case 4: return currentTab === "appraisals" ? (o.days_left !== null ? o.days_left : 9999) : "";
        case 5: return currentTab === "appraisals" ? (o.overall_score || 0) : "";
        case 6: return currentTab === "appraisals" ? (o.status || "").toLowerCase() : "";
        default: return "";
    }
}

function sortData(arr) {
    if (sortCol < 0) return arr;
    return arr.slice().sort(function(a, b) {
        var va = getSortVal(a), vb = getSortVal(b);
        if (va < vb) return sortDir === "asc" ? -1 : 1;
        if (va > vb) return sortDir === "asc" ? 1 : -1;
        return 0;
    });
}

function updateSortArrows(count, prefix) {
    for (var i = 0; i < count; i++) {
        var el = document.getElementById(prefix + "SortArrow" + i);
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

function getFiltered() {
    var q = document.getElementById("searchInput").value.trim().toLowerCase();
    var src = currentTab === "appraisals" ? allData : eligibleData;
    return sortData(src.filter(function(o) {
        var name = ((o.employee_name || "") + " " + (o.dept_name || "") + " " + (o.job_title || "") + " " + (o.designation_name || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function applyFilters() {
    var statusVal = document.getElementById("statusFilter").value;
    var deptVal = document.getElementById("deptFilter").value;
    document.getElementById("searchClear").style.display = document.getElementById("searchInput").value.length > 0 ? "block" : "none";
    var params = [];
    if (statusVal) params.push("status=" + statusVal);
    if (deptVal > 0) params.push("dept_id=" + deptVal);
    var qs = params.length ? "?" + params.join("&") : "";
    apiGet("ajax/appraisals_list.php" + qs, null, function(res) {
        allData = (res.success && res.data) ? res.data : [];
        renderStats();
        if (currentTab === "appraisals") renderTable();
    }, function() { allData = []; renderStats(); renderTable(); });
}

function clearSearch() {
    document.getElementById("searchInput").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderPaginationBar(total, totalPages, containerId) {
    var c = document.getElementById(containerId);
    if (totalPages <= 1) { c.innerHTML = ""; return; }
    var h = "<div class=\"rp-pg-info\">Page " + currentPage + " of " + totalPages + " &middot; " + total + " total</div>";
    h += "<div class=\"rp-pg-center\">";
    h += "<button class=\"rp-pg-btn\"" + (currentPage <= 1 ? " disabled" : "") + " onclick=\"goToPage(1)\">&laquo;</button>";
    h += "<button class=\"rp-pg-btn\"" + (currentPage <= 1 ? " disabled" : "") + " onclick=\"goToPage(" + (currentPage - 1) + ")\">&lsaquo;</button>";
    var s = Math.max(1, currentPage - 2), e = Math.min(totalPages, currentPage + 2);
    for (var p = s; p <= e; p++) {
        h += "<button class=\"rp-pg-btn" + (p === currentPage ? " rp-active" : "") + "\" onclick=\"goToPage(" + p + ")\">" + p + "</button>";
    }
    h += "<button class=\"rp-pg-btn\"" + (currentPage >= totalPages ? " disabled" : "") + " onclick=\"goToPage(" + (currentPage + 1) + ")\">&rsaquo;</button>";
    h += "<button class=\"rp-pg-btn\"" + (currentPage >= totalPages ? " disabled" : "") + " onclick=\"goToPage(" + totalPages + ")\">&raquo;</button>";
    h += "</div>";
    h += "<div class=\"rp-pg-rows\">Show <select onchange=\"changePerPage(this.value)\">";
    [10, 20, 50, 100].forEach(function(n) { h += "<option value=\"" + n + "\"" + (n === perPage ? " selected" : "") + ">" + n + "</option>"; });
    h += "</select></div>";
    c.innerHTML = h;
}

function goToPage(p) {
    var filtered = getFiltered();
    var tp = Math.ceil(filtered.length / perPage) || 1;
    p = Math.max(1, Math.min(p, tp));
    if (p === currentPage) return;
    currentPage = p;
    renderTable();
}

function changePerPage(n) { perPage = parseInt(n); currentPage = 1; renderTable(); }

function renderStats() {
    var active = allData.filter(function(a) { return a.status === "Pending" || a.status === "In Progress" || a.status === "Draft"; }).length;
    var completed = allData.filter(function(a) { return a.status === "Completed"; }).length;
    var extended = allData.filter(function(a) { return a.recommendation === "Extend"; }).length;
    var pendingConfirm = allData.filter(function(a) { return a.status === "Completed" && a.recommendation === "Confirm"; }).length;
    document.getElementById("ccActive").textContent = active;
    document.getElementById("ccCompleted").textContent = completed;
    document.getElementById("ccExtended").textContent = extended;
    document.getElementById("ccPending").textContent = pendingConfirm;
    document.getElementById("tabCountAppraisals").textContent = allData.length;
    document.getElementById("tabCountEligible").textContent = eligibleData.length;
}

function renderMobileCards(pageData) {
    var c = document.getElementById(currentTab === "eligible" ? "eligibleMobileList" : "appraisalsMobileList");
    if (!pageData.length) {
        var emptyMsg = currentTab === "appraisals" ? "No probation appraisals found" : "All employees have been evaluated";
        c.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4CB;</div><h3>" + emptyMsg + "</h3></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(o) {
        if (currentTab === "eligible") {
            var days = o.days_left !== null ? (o.days_left < 0 ? "Overdue " + Math.abs(o.days_left) + "d" : o.days_left + "d left") : "-";
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.name || "") + "<small>" + (o.job_title || "N/A") + "</small></div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Designation</label><span>" + (o.designation_name || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Joining Date</label><span>" + (o.joining_date || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Days Left</label><span>" + days + "</span></div>";
            h += "</div><div class=\"rp-mob-footer\"><a class=\"btn btn-primary btn-sm\" href=\"new.php?employee_id=" + o.id + "\">+ Create</a></div></div>";
        } else {
            var daysBadge = "";
            if (o.days_left !== null) {
                if (o.days_left < 0) daysBadge = "<span class=\"badge badge-danger\">Overdue by " + Math.abs(o.days_left) + "d</span>";
                else if (o.days_left <= 7) daysBadge = "<span class=\"badge badge-warning\">" + o.days_left + "d left</span>";
                else daysBadge = "<span class=\"badge badge-completed\">" + o.days_left + "d left</span>";
            }
            var statusBadge = o.status === "Completed" ? "<span class=\"badge badge-completed\">Completed</span>"
                : o.status === "In Progress" ? "<span class=\"badge badge-in-progress\">In Progress</span>"
                : o.status === "Draft" ? "<span class=\"badge badge-pending\">Draft</span>"
                : o.status === "Rejected" ? "<span class=\"badge badge-danger\">Rejected</span>"
                : "<span class=\"badge badge-pending\">" + o.status + "</span>";
            var actionBtn = o.status === "Draft"
                ? "<a class=\"btn btn-outline btn-sm\" href=\"edit.php?id=" + o.id + "\">Edit</a>"
                : "<button class=\"btn btn-primary btn-sm\" onclick=\"viewDetail(" + o.id + ")\">View</button>";
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.employee_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
            h += "<div class=\"rp-mob-badges\">" + statusBadge + "</div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Department</label><span>" + (o.dept_name || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Probation End</label><span>" + (o.end_date || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Days Left</label><span>" + daysBadge + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Score</label><span>" + (o.overall_score !== null ? o.overall_score.toFixed(2) : "-") + "</span></div>";
            h += "</div><div class=\"rp-mob-footer\">" + actionBtn + "</div></div>";
        }
    });
    c.innerHTML = h;
}

function renderTable() {
    var filtered = getFiltered();
    var tp = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > tp) currentPage = tp;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);
    var tbody = document.getElementById(currentTab === "eligible" ? "eligibleTableBody" : "appraisalsTableBody");
    tbody.innerHTML = "";
    var colCount = currentTab === "appraisals" ? 8 : 5;
    if (!pageData.length) {
        var emptyMsg = currentTab === "appraisals" ? "No probation appraisals found" : "All employees have been evaluated";
        tbody.innerHTML = "<tr><td colspan=\"" + colCount + "\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4CB;</div><h3>" + emptyMsg + "</h3></div></td></tr>";
    } else if (currentTab === "eligible") {
        pageData.forEach(function(e) {
            var days = e.days_left !== null ? (e.days_left < 0 ? "Overdue " + Math.abs(e.days_left) + "d" : e.days_left + "d left") : "-";
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (e.name || "") + "</strong><br><small style='color:#888'>" + (e.job_title || "") + "</small></td>" +
                "<td>" + (e.designation_name || "-") + "</td>" +
                "<td>" + (e.joining_date || "-") + "</td>" +
                "<td>" + days + "</td>" +
                "<td><div class=\"rp-actions-cell\"><a class=\"rp-btn-text rp-btn-primary\" href=\"new.php?employee_id=" + e.id + "\">+ Create</a></div></td>";
            tbody.appendChild(tr);
        });
    } else {
        pageData.forEach(function(a) {
            var daysBadge = "";
            if (a.days_left !== null) {
                if (a.days_left < 0) daysBadge = "<span class=\"badge badge-danger\">Overdue by " + Math.abs(a.days_left) + "d</span>";
                else if (a.days_left <= 7) daysBadge = "<span class=\"badge badge-warning\">" + a.days_left + "d left</span>";
                else daysBadge = "<span class=\"badge badge-completed\">" + a.days_left + "d left</span>";
            }
            var statusBadge = a.status === "Completed" ? "<span class=\"badge badge-completed\">Completed</span>"
                : a.status === "In Progress" ? "<span class=\"badge badge-in-progress\">In Progress</span>"
                : a.status === "Draft" ? "<span class=\"badge badge-pending\">Draft</span>"
                : a.status === "Rejected" ? "<span class=\"badge badge-danger\">Rejected</span>"
                : "<span class=\"badge badge-pending\">" + a.status + "</span>";
            var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
            var action = a.status === "Draft"
                ? "<a class=\"rp-btn-text rp-btn-primary\" href=\"edit.php?id=" + a.id + "\">Edit</a>"
                : "<button class=\"rp-btn-text rp-btn-view\" onclick=\"viewDetail(" + a.id + ")\">View</button>";
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (a.employee_name || "") + "</strong><br><small style='color:#888'>" + (a.job_title || "") + "</small></td>" +
                "<td>" + (a.dept_name || "-") + "</td>" +
                "<td>" + (a.joining_date || "-") + "</td>" +
                "<td>" + (a.end_date || "-") + "</td>" +
                "<td>" + daysBadge + "</td>" +
                "<td>" + score + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + action + "</div></td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    var prefix = currentTab === "appraisals" ? "appraisals" : "eligible";
    var sortCount = currentTab === "appraisals" ? 7 : 4;
    var pgId = currentTab === "eligible" ? "eligiblePagination" : "appraisalsPagination";
    renderPaginationBar(filtered.length, tp, pgId);
    updateSortArrows(sortCount, prefix);
}

function viewDetail(id) {
    apiGet("ajax/appraisals_get.php?id=" + id, null, function(res) {
        if (!res.success || !res.data) { showAlert("Not found", "error"); return; }
        var a = res.data;
        document.getElementById("mdTitleName").textContent = a.employee_name;
        document.getElementById("mdJoining").textContent = a.joining_date || "-";
        document.getElementById("mdEnd").textContent = a.end_date || "-";
        document.getElementById("mdProbation").textContent = a.probation_months ? a.probation_months + " months" : "-";
        document.getElementById("mdDaysLeft").textContent = a.days_left !== null ? (a.days_left < 0 ? "Overdue by " + Math.abs(a.days_left) + "d" : a.days_left + "d left") : "-";
        document.getElementById("mdDept").textContent = a.dept_name || "N/A";
        document.getElementById("mdDesignation").textContent = a.designation_name || "N/A";
        document.getElementById("mdStatus").textContent = a.status;
        document.getElementById("mdStatus").className = a.status === "Completed" ? "badge badge-completed" : a.status === "Draft" ? "badge badge-pending" : a.status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
        document.getElementById("mdRecommendation").textContent = a.recommendation || "-";
        document.getElementById("mdScoreVal").textContent = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
        var pct = a.overall_score !== null ? (a.overall_score / 5 * 100) : 0;
        document.getElementById("mdScoreBar").style.width = Math.min(pct, 100) + "%";
        document.getElementById("mdScoreLabel").textContent = a.overall_score !== null ? a.overall_score.toFixed(1) + " / 5" : "0 / 5";
        document.getElementById("mdSupervisorCmt").textContent = a.supervisor_comments || "No comments";
        document.getElementById("mdDeptCmt").textContent = a.dept_head_comments || "No comments";
        document.getElementById("mdHRCmt").textContent = a.hr_review || "No comments";
        document.getElementById("mdRemarks").textContent = a.remarks || "No remarks";
        var scoresHtml = "";
        if (a.scores && a.scores.length) {
            for (var i = 0; i < a.scores.length; i++) {
                var s = a.scores[i];
                var barW = (s.score / 5 * 100);
                scoresHtml += "<tr>" +
                    "<td>" + s.category_name + "</td>" +
                    "<td>" + s.weight + "%</td>" +
                    "<td><div style='display:flex;align-items:center;gap:8px'><div style='flex:1;height:6px;background:#e9ecef;border-radius:3px;overflow:hidden'><div style='height:100%;width:" + barW + "%;background:var(--primary);border-radius:3px'></div></div><span style='font-weight:600;font-size:13px;min-width:30px'>" + s.score.toFixed(1) + "</span></div></td>" +
                    "<td style='font-size:12px;color:#666;max-width:160px'>" + (s.comments || "-") + "</td>" +
                "</tr>";
            }
        } else {
            scoresHtml = "<tr><td colspan=\"4\" style=\"text-align:center;color:#888;padding:24px\">No scores recorded yet</td></tr>";
        }
        document.getElementById("mdScoresBody").innerHTML = scoresHtml;
        var wfHtml = "";
        if (a.workflow && a.workflow.length) {
            for (var i = 0; i < a.workflow.length; i++) {
                var w = a.workflow[i];
                var wfClass = w.status === "Approved" ? "wf-done" : w.status === "In Progress" ? "wf-active" : "";
                var icon = w.status === "Approved" ? "&#10003;" : (i + 1);
                wfHtml += "<div class=\"wf-item " + wfClass + "\">" +
                    "<div class=\"wf-circle\">" + icon + "</div>" +
                    "<div class=\"wf-info\"><strong>" + w.level + "</strong>" +
                    "<span class=\"wf-status\">" + (w.status === "Approved" ? "Approved by " + (w.approved_by || "System") : w.status) + "</span>" +
                    (w.comments ? "<span class=\"wf-comment\">" + w.comments + "</span>" : "") +
                    "</div></div>";
                if (i < a.workflow.length - 1) wfHtml += "<div class=\"wf-connector " + wfClass + "\"></div>";
            }
        } else {
            wfHtml = "<div style='text-align:center;padding:16px;color:#888'>No workflow data</div>";
        }
        document.getElementById("mdWorkflow").innerHTML = wfHtml;
        openModal("viewDetailModal");
    });
}

function loadEligibleEmployees() {
    apiGet("ajax/eligible_employees.php", null, function(res) {
        eligibleData = (res.success && res.data) ? res.data : [];
        renderStats();
        if (currentTab === "eligible") renderTable();
    }, function() { eligibleData = []; renderStats(); });
}

function loadDepartments() {
    apiGet("../ajax/departments.php", null, function(res) {
        departments = res.success && res.data ? res.data : [];
        var sel = document.getElementById("deptFilter");
        sel.innerHTML = "<option value=\"0\">All Departments</option>";
        for (var i = 0; i < departments.length; i++) {
            sel.innerHTML += "<option value=\"" + departments[i].id + "\">" + departments[i].name + "</option>";
        }
    });
}

function formatDate(d) {
    if (!d) return "";
    var date = new Date(d + "T00:00:00");
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return months[date.getMonth()] + " " + ("0" + date.getDate()).slice(-2) + ", " + date.getFullYear();
}

document.addEventListener("DOMContentLoaded", function() {
    loadDepartments();
    loadEligibleEmployees();
    applyFilters();
});