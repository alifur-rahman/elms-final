var allData = [];
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function sortBy(col) {
    if (sortCol === col) { sortDir = sortDir === "asc" ? "desc" : "asc"; }
    else { sortCol = col; sortDir = "asc"; }
    currentPage = 1;
    renderTable();
}

function getSortVal(o) {
    switch (sortCol) {
        case 0: return (o.candidate_name || "").toLowerCase();
        case 1: return (o.job_title || "").toLowerCase();
        case 2: return (o.dept_name || "").toLowerCase();
        case 3: return (o.designation_name || "").toLowerCase();
        case 4: return o.salary || 0;
        case 5: return o.employee_created ? "a" : "b";
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

function updateSortArrows() {
    for (var i = 0; i <= 5; i++) {
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

function getFiltered() {
    var q = document.getElementById("searchInput").value.trim().toLowerCase();
    return sortData(allData.filter(function(o) {
        var name = ((o.candidate_name || "") + " " + (o.job_title || "") + " " + (o.dept_name || "") + " " + (o.designation_name || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function renderPaginationBar(total, totalPages) {
    var c = document.getElementById("paginationBar");
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

function applyFilter() {
    document.getElementById("searchClear").style.display = document.getElementById("searchInput").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}

function clearSearch() {
    document.getElementById("searchInput").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderMobileCards(pageData) {
    var c = document.getElementById("mobileList");
    if (!pageData.length) {
        c.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F465;</div><h3>No candidates found</h3><p>Accepted offers will appear here for employee creation</p></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(o) {
        var statusBadge = o.employee_created
            ? "<span class=\"badge badge-success\">Created</span>"
            : "<span class=\"badge badge-in-progress\">Pending</span>";
        var actionBtn = o.employee_created
            ? "<a class=\"btn btn-outline btn-sm\" href=\"view.php?offer_id=" + o.id + "\">View</a>"
            : "<a class=\"btn btn-primary btn-sm\" href=\"create.php?offer_id=" + o.id + "\">Create Employee</a>";
        h += "<div class=\"rp-mob-card\">";
        h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.candidate_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
        h += "<div class=\"rp-mob-badges\">" + statusBadge + "</div></div>";
        h += "<div class=\"rp-mob-grid\">";
        h += "<div class=\"rp-mob-field\"><label>Department</label><span>" + (o.dept_name || "N/A") + "</span></div>";
        h += "<div class=\"rp-mob-field\"><label>Designation</label><span>" + (o.designation_name || "N/A") + "</span></div>";
        h += "<div class=\"rp-mob-field\"><label>Salary</label><span>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</span></div>";
        h += "</div><div class=\"rp-mob-footer\">" + actionBtn + "</div></div>";
    });
    c.innerHTML = h;
}

function renderTable() {
    var filtered = getFiltered();
    var tp = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > tp) currentPage = tp;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);
    var tbody = document.getElementById("empTableBody");
    tbody.innerHTML = "";
    if (!pageData.length) {
        tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F465;</div><h3>No candidates found</h3><p>Accepted offers will appear here for employee creation</p></div></td></tr>";
    } else {
        pageData.forEach(function(o) {
            var statusBadge = o.employee_created
                ? "<span class=\"badge badge-success\">Created</span>"
                : "<span class=\"badge badge-in-progress\">Pending</span>";
            var action = o.employee_created
                ? "<a class=\"rp-btn-text rp-btn-view\" href=\"view.php?offer_id=" + o.id + "\">View</a>"
                : "<a class=\"rp-btn-text rp-btn-primary\" href=\"create.php?offer_id=" + o.id + "\">Create Employee</a>";
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (o.candidate_name || "") + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.dept_name || "N/A") + "</td>" +
                "<td>" + (o.designation_name || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + action + "</div></td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, tp);
    updateSortArrows();
}

function loadStats() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=Accepted", null, function(r) {
        allData = (r.success && r.data) ? r.data : [];
        var created = 0, pending = 0;
        allData.forEach(function(o) {
            if (o.employee_created) created++;
            else pending++;
        });
        document.getElementById("ccTotal").textContent = allData.length;
        document.getElementById("ccCreated").textContent = created;
        document.getElementById("ccPending").textContent = pending;
        renderTable();
    }, function() {
        allData = [];
        document.getElementById("ccTotal").textContent = "0";
        document.getElementById("ccCreated").textContent = "0";
        document.getElementById("ccPending").textContent = "0";
        renderTable();
    });
}

document.addEventListener("DOMContentLoaded", function() {
    loadStats();
});