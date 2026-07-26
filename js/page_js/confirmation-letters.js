var allLetters = [];
var activeTab = "all";
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;

function loadData() {
    apiGet("ajax/list_letters.php", null, function(res) {
        allLetters = res.success && res.data ? res.data : [];
        updateCounts();
        renderView();
    });
}

function updateCounts() {
    var total = allLetters.length;
    var generated = 0, approved = 0, sent = 0;
    for (var i = 0; i < allLetters.length; i++) {
        var s = allLetters[i].status;
        if (s === "Generated") generated++;
        else if (s === "Approved") approved++;
        else if (s === "Sent") sent++;
    }
    document.getElementById("cntTotal").textContent = total;
    document.getElementById("cntGenerated").textContent = generated;
    document.getElementById("cntApproved").textContent = approved;
    document.getElementById("cntSent").textContent = sent;
    document.querySelectorAll(".scr-tab-count").forEach(function(el) {
        var tab = el.getAttribute("data-count");
        if (tab === "all") el.textContent = total;
        else if (tab === "Generated") el.textContent = generated;
        else if (tab === "Approved") el.textContent = approved;
        else if (tab === "Sent") el.textContent = sent;
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
    for (var i = 0; i < allLetters.length; i++) {
        var l = allLetters[i];
        if (activeTab !== "all" && l.status !== activeTab) continue;
        if (search) {
            var hay = ((l.employee_name || "") + " " + (l.employee_code || "") + " " + (l.dept_name || "") + " " + (l.job_title || "") + " " + (l.designation_name || "")).toLowerCase();
            if (hay.indexOf(search) === -1) continue;
        }
        list.push(l);
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

function getSortVal(l, col) {
    switch(col) {
        case "employee": return (l.employee_name || "").toLowerCase();
        case "job": return (l.job_title || "").toLowerCase();
        case "dept": return (l.dept_name || "").toLowerCase();
        case "type": return (l.employment_type || "").toLowerCase();
        case "salary": return l.salary || 0;
        case "status": return (l.status || "").toLowerCase();
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
    updateSortArrows(6, "list");
}

function renderTable(rows) {
    var tbody = document.getElementById("letterTableBody");
    if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="rp-empty"><div class="rp-empty-icon">&#128196;</div><h3>No letters found</h3><p>No confirmation letters match your current filters.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var l = rows[i];
        html += "<tr>" +
            "<td><strong>" + (l.employee_name || "N/A") + "</strong><br><small style='color:#888'>" + (l.employee_code || "") + "</small></td>" +
            "<td>" + (l.job_title || "N/A") + "</td>" +
            "<td>" + (l.dept_name || "N/A") + "</td>" +
            "<td>" + (l.employment_type || "Probationer") + "</td>" +
            "<td>" + (l.salary ? formatCurrency(l.salary, "\u09F3") : "-") + "</td>" +
            "<td>" + getStatusBadge(l.status) + "</td>" +
            "<td>" + getActionHtml(l) + "</td>" +
        "</tr>";
    }
    tbody.innerHTML = html;
}

function renderMobileCards(rows) {
    var container = document.getElementById("mobileList");
    if (rows.length === 0) {
        container.innerHTML = '<div class="rp-empty"><div class="rp-empty-icon">&#128196;</div><h3>No letters found</h3><p>No confirmation letters match your current filters.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var l = rows[i];
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + (l.employee_name || "N/A") + '<small>' + (l.employee_code || "") + '</small></div>' +
                '<div class="rp-mob-badges">' + getStatusBadge(l.status) + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Job Title</label><span>' + (l.job_title || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Department</label><span>' + (l.dept_name || "N/A") + '</span></div>' +
                '<div class="rp-mob-field"><label>Type</label><span>' + (l.employment_type || "Probationer") + '</span></div>' +
                '<div class="rp-mob-field"><label>Salary</label><span>' + (l.salary ? formatCurrency(l.salary, "\u09F3") : "-") + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer">' + getActionHtml(l) + '</div>' +
        '</div>';
    }
    container.innerHTML = html;
}

function getStatusBadge(status) {
    if (status === "Generated") return '<span class="badge badge-generated">Generated</span>';
    if (status === "Approved") return '<span class="badge badge-approved">Approved</span>';
    if (status === "Sent") return '<span class="badge badge-sent">Sent</span>';
    if (status === "Rejected") return '<span class="badge badge-rejected">Rejected</span>';
    return '<span class="badge badge-generated">' + (status || "Unknown") + '</span>';
}

function getActionHtml(l) {
    var html = '<div class="rp-actions-cell">';
    if (l.status === "Generated") {
        html += '<button class="rp-btn-text" onclick="previewLetter(' + l.id + ')">Preview</button>';
        html += '<button class="rp-btn-text rp-btn-primary" onclick="approveLetter(' + l.id + ')">Approve</button>';
    } else if (l.status === "Approved") {
        html += '<button class="rp-btn-text" onclick="previewLetter(' + l.id + ')">Preview</button>';
        html += '<button class="rp-btn-text rp-btn-success" onclick="openSendEmail(' + l.id + ')">Send</button>';
    } else if (l.status === "Sent") {
        html += '<button class="rp-btn-text" onclick="previewLetter(' + l.id + ')">Preview</button>';
    }
    html += '</div>';
    return html;
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

function previewLetter(letterId) {
    apiGet("ajax/letter_view.php?letter_id=" + letterId, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("previewContent").innerHTML = res.data.letter_content || "<p>No content</p>";
            openModal("previewLetterModal");
        } else {
            showAlert("Could not load letter", "error");
        }
    });
}

function approveLetter(letterId) {
    if (!confirm("Approve this confirmation letter?")) return;
    apiPost("ajax/letter_approve.php", { letter_id: letterId, action: "approve" }, function(res) {
        showAlert(res.message || "Approved!", "success");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed", "error");
    });
}

function openSendEmail(letterId) {
    var l = null;
    for (var i = 0; i < allLetters.length; i++) { if (allLetters[i].id === letterId) { l = allLetters[i]; break; } }
    if (!l) return;
    document.getElementById("sendLetterId").value = l.id;
    document.getElementById("sendCandidateName").value = l.employee_name || "";
    document.getElementById("sendEmail").value = l.email || l.sent_to_email || "";
    document.getElementById("sendSubject").value = "Confirmation Letter - " + (l.job_title || "Position");
    document.getElementById("sendBody").value = "";
    openModal("sendEmailModal");
}

function submitSendEmail() {
    var letterId = document.getElementById("sendLetterId").value;
    var email = document.getElementById("sendEmail").value;
    var subject = document.getElementById("sendSubject").value;
    var body = document.getElementById("sendBody").value;
    if (!email) { showAlert("Please enter recipient email", "error"); return; }
    apiPost("ajax/letter_send.php", {
        letter_id: letterId,
        recipient: email,
        subject: subject,
        body: body
    }, function(res) {
        showAlert(res.message || "Sent!", "success");
        closeModal("sendEmailModal");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to send", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() { loadData(); });