var allData = [];
var activeTab = "all";
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;

function loadData() {
    apiGet("ajax/list_eligible.php", null, function(res) {
        allData = res.success && res.data ? res.data : [];
        updateCounts();
        renderView();
    });
}

function updateCounts() {
    var total = allData.length;
    var pending = 0, generated = 0, sent = 0;
    for (var i = 0; i < allData.length; i++) {
        var s = allData[i].confirmation_status;
        if (!s) pending++;
        else if (s === "Generated") generated++;
        else if (s === "Sent") sent++;
        else pending++;
    }
    document.getElementById("cntTotal").textContent = total;
    document.getElementById("cntPending").textContent = pending;
    document.getElementById("cntGenerated").textContent = generated;
    document.getElementById("cntSent").textContent = sent;
    document.querySelectorAll(".scr-tab-count").forEach(function(el) {
        var tab = el.getAttribute("data-count");
        if (tab === "all") el.textContent = total;
        else if (tab === "pending") el.textContent = pending;
        else if (tab === "generated") el.textContent = generated;
        else if (tab === "sent") el.textContent = sent;
    });
}

function switchTab(tab) {
    activeTab = tab;
    pgPage = 1;
    sortCol = "";
    sortAsc = true;
    document.querySelectorAll(".scr-tab").forEach(function(t) {
        t.classList.toggle("active", t.getAttribute("data-tab") === tab);
    });
    renderView();
}

function getFiltered() {
    var search = (document.getElementById("searchInput").value || "").toLowerCase().trim();
    var list = [];
    for (var i = 0; i < allData.length; i++) {
        var e = allData[i];
        var s = e.confirmation_status;
        if (activeTab === "pending" && s) continue;
        if (activeTab === "generated" && s !== "Generated") continue;
        if (activeTab === "sent" && s !== "Sent") continue;
        if (search) {
            var hay = ((e.employee_name || "") + " " + (e.employee_code || "") + " " + (e.dept_name || "") + " " + (e.job_title || "") + " " + (e.designation_name || "")).toLowerCase();
            if (hay.indexOf(search) === -1) continue;
        }
        list.push(e);
    }
    if (sortCol) {
        list.sort(function(a, b) {
            var va = getSortVal(a, sortCol);
            var vb = getSortVal(b, sortCol);
            if (va < vb) return sortAsc ? -1 : 1;
            if (va > vb) return sortAsc ? 1 : -1;
            return 0;
        });
    }
    return list;
}

function sortBy(col) {
    if (sortCol === col) sortAsc = !sortAsc;
    else { sortCol = col; sortAsc = true; }
    pgPage = 1;
    renderView();
}

function getSortVal(e, col) {
    switch(col) {
        case "employee": return (e.employee_name || "").toLowerCase();
        case "job": return (e.job_title || "").toLowerCase();
        case "dept": return (e.dept_name || "").toLowerCase();
        case "designation": return (e.designation_name || "").toLowerCase();
        case "type": return (e.employment_type || "").toLowerCase();
        case "end": return e.end_date || "";
        case "score": return e.overall_score || 0;
        default: return "";
    }
}

function updateSortArrows(count, prefix) {
    for (var i = 0; i < count; i++) {
        var el = document.getElementById(prefix + "SortArrow" + i);
        if (!el) continue;
        if (sortCol === el.getAttribute("data-col")) {
            el.textContent = sortAsc ? "\u25B2" : "\u25BC";
            el.parentElement.classList.add("rp-sorted");
        } else {
            el.textContent = "\u2195";
            el.parentElement.classList.remove("rp-sorted");
        }
    }
}

function renderView() {
    var filtered = getFiltered();
    var totalPages = Math.max(1, Math.ceil(filtered.length / pgRows));
    if (pgPage > totalPages) pgPage = totalPages;
    var start = (pgPage - 1) * pgRows;
    var paged = filtered.slice(start, start + pgRows);

    renderTable(paged);
    renderMobileCards(paged);
    renderPagination(filtered.length, totalPages);
    updateSortArrows(7, "list");
}

function renderTable(rows) {
    var tbody = document.getElementById("tableBody");
    if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8"><div class="rp-empty"><div class="rp-empty-icon">&#128100;</div><h3>No employees found</h3><p>No eligible employees match your current filters.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var e = rows[i];
        var statusBadge = getStatusBadge(e.confirmation_status);
        var action = getActionHtml(e);
        html += "<tr>" +
            "<td><strong>" + (e.employee_name || "N/A") + "</strong><br><small style='color:#888'>" + (e.employee_code || "") + "</small></td>" +
            "<td>" + (e.job_title || "N/A") + "</td>" +
            "<td>" + (e.dept_name || "N/A") + "</td>" +
            "<td>" + (e.designation_name || "N/A") + "</td>" +
            "<td>" + (e.employment_type || "Probationer") + "</td>" +
            "<td>" + (e.end_date || "-") + "</td>" +
            "<td>" + (e.overall_score != null ? e.overall_score.toFixed(1) : "-") + "</td>" +
            "<td>" + action + "</td>" +
        "</tr>";
    }
    tbody.innerHTML = html;
}

function renderMobileCards(rows) {
    var container = document.getElementById("mobileList");
    if (rows.length === 0) {
        container.innerHTML = '<div class="rp-empty"><div class="rp-empty-icon">&#128100;</div><h3>No employees found</h3><p>No eligible employees match your current filters.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var e = rows[i];
        var statusBadge = getStatusBadge(e.confirmation_status);
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + (e.employee_name || "N/A") + '<small>' + (e.employee_code || "") + '</small></div>' +
                '<div class="rp-mob-badges">' + statusBadge + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Job Title</label><span>' + (e.job_title || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Department</label><span>' + (e.dept_name || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Designation</label><span>' + (e.designation_name || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Type</label><span>' + (e.employment_type || "Probationer") + '</span></div>' +
                '<div class="rp-mob-field"><label>Probation End</label><span>' + (e.end_date || "-") + '</span></div>' +
                '<div class="rp-mob-field"><label>Score</label><span>' + (e.overall_score != null ? e.overall_score.toFixed(1) + " / 5" : "-") + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer">' + getActionHtml(e) + '</div>' +
        '</div>';
    }
    container.innerHTML = html;
}

function getStatusBadge(status) {
    if (!status) return '<span class="badge badge-pending">Pending</span>';
    if (status === "Sent") return '<span class="badge badge-completed">Sent</span>';
    if (status === "Approved") return '<span class="badge badge-in-progress">Approved</span>';
    if (status === "Generated") return '<span class="badge badge-in-progress">Generated</span>';
    if (status === "Rejected") return '<span class="badge badge-danger">Rejected</span>';
    return '<span class="badge badge-draft">' + status + '</span>';
}

function getActionHtml(e) {
    if (e.confirmation_status === "Sent") {
        return '<div class="rp-actions-cell">' +
            '<span class="badge badge-completed" style="margin-right:4px">Sent</span>' +
            '<a class="rp-btn-text" href="confirmation_letters.php">View</a></div>';
    }
    if (e.confirmation_status === "Generated" || e.confirmation_status === "Approved") {
        return '<div class="rp-actions-cell">' +
            '<a class="rp-btn-text rp-btn-primary" href="generate.php?employee_id=' + e.employee_id + '&appraisal_id=' + e.appraisal_id + '">Generate</a></div>';
    }
    return '<div class="rp-actions-cell">' +
        '<a class="rp-btn-text rp-btn-primary" href="generate.php?employee_id=' + e.employee_id + '&appraisal_id=' + e.appraisal_id + '">Generate Letter</a></div>';
}

function renderPagination(total, totalPages) {
    var pgBar = document.getElementById("pgBar");
    if (total <= pgRows) { pgBar.style.display = "none"; return; }
    pgBar.style.display = "";
    var info = document.getElementById("pgInfo");
    var center = document.getElementById("pgCenter");
    var rowsInfo = document.getElementById("pgRowsInfo");
    var start = total === 0 ? 0 : (pgPage - 1) * pgRows + 1;
    var end = Math.min(pgPage * pgRows, total);
    info.textContent = "Showing " + start + "\u2013" + end + " of " + total;

    var html = '<button class="rp-pg-btn" onclick="goPage(' + (pgPage - 1) + ')"' + (pgPage <= 1 ? ' disabled' : '') + '>\u2190</button>';
    var startP = Math.max(1, pgPage - 2);
    var endP = Math.min(totalPages, pgPage + 2);
    if (startP > 1) html += '<button class="rp-pg-btn" onclick="goPage(1)">1</button>';
    if (startP > 2) html += '<span style="color:var(--gray-400)">...</span>';
    for (var p = startP; p <= endP; p++) {
        html += '<button class="rp-pg-btn' + (p === pgPage ? ' rp-active' : '') + '" onclick="goPage(' + p + ')">' + p + '</button>';
    }
    if (endP < totalPages - 1) html += '<span style="color:var(--gray-400)">...</span>';
    if (endP < totalPages) html += '<button class="rp-pg-btn" onclick="goPage(' + totalPages + ')">' + totalPages + '</button>';
    html += '<button class="rp-pg-btn" onclick="goPage(' + (pgPage + 1) + ')"' + (pgPage >= totalPages ? ' disabled' : '') + '>\u2192</button>';
    center.innerHTML = html;

    rowsInfo.innerHTML = 'Rows: <select onchange="changeRows(this.value)"><option value="5"' + (pgRows === 5 ? ' selected' : '') + '>5</option><option value="10"' + (pgRows === 10 ? ' selected' : '') + '>10</option><option value="25"' + (pgRows === 25 ? ' selected' : '') + '>25</option><option value="50"' + (pgRows === 50 ? ' selected' : '') + '>50</option></select>';
}

function goPage(p) {
    pgPage = p;
    renderView();
}

function changeRows(val) {
    pgRows = parseInt(val) || 10;
    pgPage = 1;
    renderView();
}

document.addEventListener("DOMContentLoaded", function() { loadData(); });