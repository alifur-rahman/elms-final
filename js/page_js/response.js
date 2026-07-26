var pendingData = [];
var respondedData = [];
var currentTab = "pending";
var currentPage = 1;
var perPage = 10;
var sortCol = -1;
var sortDir = "asc";

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".scr-tab").forEach(function(b) { b.classList.remove("active"); });
    document.getElementById("tabBtn" + tab).classList.add("active");
    var titles = {pending: "Awaiting Response", responded: "Responded Offers"};
    document.getElementById("tableTitle").textContent = titles[tab] || "";
    sortCol = -1;
    currentPage = 1;
    document.getElementById("searchResponse").value = "";
    document.getElementById("searchResponseClear").style.display = "none";
    buildHeaders();
    renderTable();
}

function buildHeaders() {
    var tr = document.getElementById("tableHeadRow");
    if (currentTab === "pending") {
        tr.innerHTML = '<th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="sortArrow1"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(2)">Offered Salary <span class="rp-sort-arrow" id="sortArrow2"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(3)">Letter Sent <span class="rp-sort-arrow" id="sortArrow3"></span></th>' +
            '<th>Status</th><th>Actions</th>';
    } else {
        tr.innerHTML = '<th class="rp-sortable" onclick="sortBy(0)">Candidate <span class="rp-sort-arrow" id="sortArrow0"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(1)">Job <span class="rp-sort-arrow" id="sortArrow1"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(2)">Offered Salary <span class="rp-sort-arrow" id="sortArrow2"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(3)">Response <span class="rp-sort-arrow" id="sortArrow3"></span></th>' +
            '<th class="rp-sortable" onclick="sortBy(4)">Date <span class="rp-sort-arrow" id="sortArrow4"></span></th>' +
            '<th>Notes</th>';
    }
}

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
        case 2: return o.salary || 0;
        case 3: return currentTab === "pending" ? (o.letter_sent_at || "").toLowerCase() : (o.response_status || "").toLowerCase();
        case 4: return currentTab === "responded" ? (o.responded_at || "") : "";
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
    var count = currentTab === "pending" ? 4 : 5;
    for (var i = 0; i < count; i++) {
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

function getActiveData() {
    return currentTab === "pending" ? pendingData : respondedData;
}

function getFiltered() {
    var q = document.getElementById("searchResponse").value.trim().toLowerCase();
    return sortData(getActiveData().filter(function(o) {
        var name = ((o.candidate_name || "") + " " + (o.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function renderPaginationBar(total, totalPages) {
    var c = document.getElementById("paginationResponse");
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
    document.getElementById("searchResponseClear").style.display = document.getElementById("searchResponse").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}
function clearSearch() {
    document.getElementById("searchResponse").value = "";
    document.getElementById("searchResponseClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function loadStats() {
    apiGet("ajax/list_offers.php?status=Approved&response_status=Pending&letter_status=Sent", null, function(r) {
        pendingData = (r.success && r.data) ? r.data : [];
        document.getElementById("ccPending").textContent = pendingData.length;
        document.getElementById("tabCountPending").textContent = pendingData.length;
        if (currentTab === "pending") renderTable();
    }, function() { pendingData = []; document.getElementById("ccPending").textContent = "0"; });
    apiGet("ajax/list_offers.php?status=Approved&response_status=Accepted,Rejected", null, function(r) {
        respondedData = (r.success && r.data) ? r.data : [];
        var accepted = 0, rejected = 0;
        respondedData.forEach(function(o) {
            if (o.response_status === "Accepted") accepted++;
            else rejected++;
        });
        document.getElementById("ccAccepted").textContent = accepted;
        document.getElementById("ccRejected").textContent = rejected;
        document.getElementById("tabCountResponded").textContent = respondedData.length;
        if (currentTab === "responded") renderTable();
    }, function() { respondedData = []; });
}

function renderMobileCards(pageData) {
    var c = document.getElementById("responseMobileList");
    if (!pageData.length) {
        c.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4EC;</div><h3>No offers found</h3></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(o) {
        var idx = getActiveData().indexOf(o);
        if (currentTab === "pending") {
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.candidate_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
            h += "<div class=\"rp-mob-badges\"><span class=\"badge badge-in-progress\">Pending</span></div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Salary</label><span>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Letter Sent</label><span>" + (o.letter_sent_at || "-") + "</span></div>";
            h += "</div><div class=\"rp-mob-footer\"><button class=\"btn btn-primary btn-sm\" onclick=\"openMarkResponse(" + idx + ")\">Record Response</button></div></div>";
        } else {
            var rsClass = o.response_status === "Accepted" ? "badge badge-success" : "badge badge-danger";
            h += "<div class=\"rp-mob-card\">";
            h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.candidate_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
            h += "<div class=\"rp-mob-badges\"><span class=\"" + rsClass + "\">" + o.response_status + "</span></div></div>";
            h += "<div class=\"rp-mob-grid\">";
            h += "<div class=\"rp-mob-field\"><label>Salary</label><span>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\"><label>Date</label><span>" + (o.responded_at || "-") + "</span></div>";
            h += "<div class=\"rp-mob-field\" style=\"grid-column:1/-1\"><label>Notes</label><span>" + (o.response_notes || "-") + "</span></div>";
            h += "</div></div>";
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
    var tbody = document.getElementById("responseTableBody");
    tbody.innerHTML = "";
    if (!pageData.length) {
        tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4EC;</div><h3>No offers found</h3></div></td></tr>";
    } else if (currentTab === "pending") {
        pageData.forEach(function(o) {
            var idx = pendingData.indexOf(o);
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (o.candidate_name || "") + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</td>" +
                "<td>" + (o.letter_sent_at || o.letter_generated_at || "-") + "</td>" +
                "<td><span class=\"badge badge-in-progress\">Pending</span></td>" +
                "<td><div class=\"rp-actions-cell\"><button class=\"rp-btn-text rp-btn-primary\" onclick=\"openMarkResponse(" + idx + ")\">Record Response</button></div></td>";
            tbody.appendChild(tr);
        });
    } else {
        pageData.forEach(function(o) {
            var rsClass = o.response_status === "Accepted" ? "badge badge-success" : "badge badge-danger";
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (o.candidate_name || "") + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\u09F3") : "-") + "</td>" +
                "<td><span class=\"" + rsClass + "\">" + (o.response_status || "-") + "</span></td>" +
                "<td>" + (o.responded_at || "-") + "</td>" +
                "<td>" + (o.response_notes || "-") + "</td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, tp);
    updateSortArrows();
}

function openMarkResponse(idx) {
    var o = pendingData[idx];
    if (!o) return;
    document.getElementById("respOfferId").value = o.id;
    document.getElementById("respCandidateName").value = o.candidate_name || "";
    document.querySelectorAll("input[name='respStatus']").forEach(function(r) { r.checked = false; });
    document.getElementById("respNotes").value = "";
    openModal("markResponseModal");
}

function submitMarkResponse() {
    var status = document.querySelector("input[name='respStatus']:checked");
    if (!status) { showAlert("Please select a response status", "error"); return; }
    apiPost("ajax/respond.php", {
        offer_id: document.getElementById("respOfferId").value,
        response: status.value,
        notes: document.getElementById("respNotes").value
    }, function(res) {
        showAlert(res.message || "Response recorded!", "success");
        closeModal("markResponseModal");
        loadStats();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to record response", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    buildHeaders();
    loadStats();
});