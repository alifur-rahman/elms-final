var upcomingData = [];
var completeData = [];
var editingScreeningId = null;
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";
var activeTab = "Upcoming";

function switchTab(name) {
    activeTab = name;
    document.querySelectorAll(".scr-tab").forEach(function(b) { b.classList.remove("active"); });
    document.querySelectorAll(".scr-tab-content").forEach(function(c) { c.classList.remove("active"); });
    document.getElementById("tabBtn" + name).classList.add("active");
    document.getElementById("tab" + name).classList.add("active");
    sortCol = -1;
    currentPage = 1;
    if (name === "Upcoming") renderUpcoming();
    else renderComplete();
}

function sortBy(col) {
    if (sortCol === col) {
        sortDir = sortDir === "asc" ? "desc" : "asc";
    } else {
        sortCol = col;
        sortDir = "asc";
    }
    currentPage = 1;
    if (activeTab === "Upcoming") renderUpcoming();
    else renderComplete();
}

function getUpcomingSortVal(c) {
    switch (sortCol) {
        case 0: return ((c.first_name || "") + " " + (c.last_name || "")).toLowerCase();
        case 1: return (c.job_title || "").toLowerCase();
        case 2: return c.applied_at || "";
        case 3: return "applied";
        default: return "";
    }
}

function getCompleteSortVal(r) {
    switch (sortCol) {
        case 0: return ((r.first_name || "") + " " + (r.last_name || "")).toLowerCase();
        case 1: return (r.job_title || "").toLowerCase();
        case 2: return r.applied_at || "";
        case 3: return (r.screening_result || "").toLowerCase();
        case 4: return r.screening_date || "";
        default: return "";
    }
}

function sortData(arr, getter) {
    if (sortCol < 0) return arr;
    return arr.slice().sort(function(a, b) {
        var va = getter(a);
        var vb = getter(b);
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

function getFilteredUpcoming() {
    var q = document.getElementById("searchUpcoming").value.trim().toLowerCase();
    var list = upcomingData.filter(function(c) {
        var name = ((c.first_name || "") + " " + (c.last_name || "") + " " + (c.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
    return sortData(list, getUpcomingSortVal);
}

function getFilteredComplete() {
    var q = document.getElementById("searchComplete").value.trim().toLowerCase();
    var list = completeData.filter(function(row) {
        var name = ((row.first_name || "") + " " + (row.last_name || "") + " " + (row.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
    return sortData(list, getCompleteSortVal);
}

function renderPaginationBar(containerId, total, totalPages) {
    if (totalPages <= 1) { document.getElementById(containerId).innerHTML = ""; return; }
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
    document.getElementById(containerId).innerHTML = html;
}

function goToPage(p) {
    var filtered = activeTab === "Upcoming" ? getFilteredUpcoming() : getFilteredComplete();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (p < 1) p = 1;
    if (p > totalPages) p = totalPages;
    if (p === currentPage) return;
    currentPage = p;
    if (activeTab === "Upcoming") renderUpcoming();
    else renderComplete();
}

function changePerPage(n) {
    perPage = parseInt(n);
    currentPage = 1;
    if (activeTab === "Upcoming") renderUpcoming();
    else renderComplete();
}

function applyUpcomingFilter() {
    document.getElementById("searchUpcomingClear").style.display = document.getElementById("searchUpcoming").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderUpcoming();
}

function clearUpcomingSearch() {
    document.getElementById("searchUpcoming").value = "";
    document.getElementById("searchUpcomingClear").style.display = "none";
    currentPage = 1;
    renderUpcoming();
}

function applyCompleteFilter() {
    document.getElementById("searchCompleteClear").style.display = document.getElementById("searchComplete").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderComplete();
}

function clearCompleteSearch() {
    document.getElementById("searchComplete").value = "";
    document.getElementById("searchCompleteClear").style.display = "none";
    currentPage = 1;
    renderComplete();
}

function renderStats() {
    var pending = upcomingData.length;
    var shortlisted = 0, rejected = 0, hold = 0;
    completeData.forEach(function(row) {
        if (row.screening_result === "Shortlist") shortlisted++;
        if (row.screening_result === "Reject") rejected++;
        if (row.screening_result === "Hold") hold++;
    });
    document.getElementById("ccPending").textContent = pending;
    document.getElementById("ccShortlisted").textContent = shortlisted;
    document.getElementById("ccRejected").textContent = rejected;
    document.getElementById("ccHold").textContent = hold;
    document.getElementById("tabCountUpcoming").textContent = pending;
    document.getElementById("tabCountComplete").textContent = completeData.length;
}

function renderUpcomingMobileCards(pageData) {
    var container = document.getElementById("upcomingMobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4C5;</div><h3>No pending screenings</h3><p>All candidates have been screened</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(c) {
        var name = c.first_name + " " + c.last_name;
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + name + "<small>" + (c.job_title || "N/A") + "</small></div>";
        html += "<div class=\"rp-mob-badges\"><span class=\"badge badge-pending\">Applied</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Applied Date</label><span>" + formatDate(c.applied_at) + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">";
        html += "<button class=\"btn btn-primary btn-sm\" onclick=\"window.openScreening(" + c.candidate_id + ",0)\">Screen Now</button>";
        html += "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function renderUpcoming() {
    var filtered = getFilteredUpcoming();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("upcomingTableBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"5\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4C5;</div><h3>No pending screenings</h3><p>All candidates have been screened</p></div></td></tr>";
    } else {
        pageData.forEach(function(c) {
            var name = c.first_name + " " + c.last_name;
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td>" + formatDate(c.applied_at) + "</td>" +
                "<td><span class=\"badge badge-pending\">Applied</span></td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text rp-btn-primary\" onclick=\"window.openScreening(" + c.candidate_id + ",0)\">Screen Now</button></div></td>";
            tbody.appendChild(tr);
        });
    }

    renderUpcomingMobileCards(pageData);
    renderPaginationBar("paginationUpcoming", filtered.length, totalPages);
    updateSortArrows(4, "upcoming");
}

function renderCompleteMobileCards(pageData) {
    var container = document.getElementById("completeMobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x2705;</div><h3>No completed screenings</h3><p>Screened candidates will appear here</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(row) {
        var name = row.first_name + " " + row.last_name;
        var resultBadge = "<span class=\"badge " + getBadgeClass(row.screening_result) + "\">" + (row.screening_result || "-") + "</span>";
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + name + "<small>" + (row.job_title || "N/A") + "</small></div>";
        html += "<div class=\"rp-mob-badges\">" + resultBadge + "</div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Applied</label><span>" + formatDate(row.applied_at) + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Rating</label><span>" + (row.rating || "-") + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Screened On</label><span>" + (row.screening_date ? formatDate(row.screening_date) : "-") + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">";
        html += "<button class=\"btn btn-outline btn-sm rp-btn-warning\" onclick=\"window.openScreening(" + row.candidate_id + "," + row.screening_id + ")\">Edit</button>";
        html += "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function renderComplete() {
    var filtered = getFilteredComplete();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("completeTableBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x2705;</div><h3>No completed screenings</h3><p>Screened candidates will appear here</p></div></td></tr>";
    } else {
        pageData.forEach(function(row) {
            var name = row.first_name + " " + row.last_name;
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (row.job_title || "N/A") + "</td>" +
                "<td>" + formatDate(row.applied_at) + "</td>" +
                "<td>" + (row.rating || "-") + " | <span class=\"badge " + getBadgeClass(row.screening_result) + "\">" + (row.screening_result || "-") + "</span></td>" +
                "<td>" + (row.screening_date ? formatDate(row.screening_date) : "-") + "</td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text rp-btn-warning\" onclick=\"window.openScreening(" + row.candidate_id + "," + row.screening_id + ")\">Edit</button></div></td>";
            tbody.appendChild(tr);
        });
    }

    renderCompleteMobileCards(pageData);
    renderPaginationBar("paginationComplete", filtered.length, totalPages);
    updateSortArrows(5, "complete");
}

window.openScreening = function(candidateId, screeningId) {
    document.getElementById("scCandidateId").value = candidateId;
    document.getElementById("scScreeningId").value = screeningId;
    editingScreeningId = screeningId || null;

    if (screeningId) {
        for (var i = 0; i < completeData.length; i++) {
            var row = completeData[i];
            if (row.screening_id === screeningId) {
                document.getElementById("scRating").value = row.rating;
                document.getElementById("scCommunication").value = row.communication_score;
                document.getElementById("scExperience").value = row.experience_match;
                document.getElementById("scSkill").value = row.skill_match;
                document.getElementById("scEducation").value = row.education_match;
                document.getElementById("scResult").value = row.screening_result;
                document.getElementById("scRemarks").value = row.screening_remarks || "";
                break;
            }
        }
        document.getElementById("screeningModalTitle").innerHTML = "Edit Screening";
        document.getElementById("scSubmitBtn").innerHTML = "Update Screening";
    } else {
        document.getElementById("screeningForm").reset();
        document.getElementById("scCandidateId").value = candidateId;
        document.getElementById("screeningModalTitle").innerHTML = "Screen Candidate";
        document.getElementById("scSubmitBtn").innerHTML = "Save Screening";
    }
    openModal("screeningModal");
};

window.ajaxSaveScreening = function() {
    var candidateId = parseInt(document.getElementById("scCandidateId").value);
    var screeningId = parseInt(document.getElementById("scScreeningId").value) || 0;
    var rating = parseFloat(document.getElementById("scRating").value);
    var communication = parseFloat(document.getElementById("scCommunication").value);
    var experience = document.getElementById("scExperience").value;
    var skill = document.getElementById("scSkill").value;
    var education = document.getElementById("scEducation").value;
    var result = document.getElementById("scResult").value;
    var remarks = document.getElementById("scRemarks").value.trim();

    if (!rating || !communication || !experience || !skill || !education || !result) {
        showAlert("Please fill in all required fields", "error");
        return;
    }
    if (rating < 1 || rating > 5 || communication < 1 || communication > 5) {
        showAlert("Rating and Communication Score must be between 1 and 5", "error");
        return;
    }

    var btn = document.getElementById("scSubmitBtn");
    var origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = "Saving...";

    var payload = {
        candidate_id: candidateId,
        rating: rating,
        communication_score: communication,
        experience_match: experience,
        skill_match: skill,
        education_match: education,
        result: result,
        remarks: remarks
    };

    var isEdit = editingScreeningId > 0;
    if (isEdit) payload.id = editingScreeningId;

    apiPost(isEdit ? "ajax/update.php" : "ajax/submit.php", payload, function(res) {
        btn.disabled = false;
        btn.innerHTML = origText;
        showAlert(res.message || "Screening saved!", "success");
        closeModal("screeningModal");
        renderPage();
    }, function(err, status) {
        btn.disabled = false;
        btn.innerHTML = origText;
        var msg = (typeof err === "object" && err.message) ? err.message : "Save failed";
        showAlert(msg, "error");
    });
};

function renderPage() {
    apiGet("ajax/list_upcoming.php", null, function(res) {
        if (res.success && res.data) upcomingData = res.data;
        else upcomingData = [];
        renderStats();
        if (activeTab === "Upcoming") renderUpcoming();
    }, function() { upcomingData = []; renderStats(); renderUpcoming(); });
    apiGet("ajax/list_complete.php", null, function(res) {
        if (res.success && res.data) completeData = res.data;
        else completeData = [];
        renderStats();
        if (activeTab === "Complete") renderComplete();
    }, function() { completeData = []; renderStats(); renderComplete(); });
}

renderPage();