<?php
$basePath    = '..';
$pageTitle   = 'Candidates';
$activePage  = 'candidates';
$breadcrumb  = 'Home / Recruitment / Candidates';
$pageStyles  = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-total{border-left-color:var(--primary)}
.cc-card.cc-total .cc-num{color:var(--primary)}
.cc-card.cc-applied{border-left-color:#3b82f6}
.cc-card.cc-applied .cc-num{color:#3b82f6}
.cc-card.cc-shortlisted{border-left-color:var(--success)}
.cc-card.cc-shortlisted .cc-num{color:var(--success)}
.cc-card.cc-interview{border-left-color:#f59e0b}
.cc-card.cc-interview .cc-num{color:#f59e0b}
.cc-card.cc-selected{border-left-color:#8b5cf6}
.cc-card.cc-selected .cc-num{color:#8b5cf6}
.cc-card.cc-offered{border-left-color:#f97316}
.cc-card.cc-offered .cc-num{color:#f97316}
.cc-card.cc-joined{border-left-color:#10b981}
.cc-card.cc-joined .cc-num{color:#10b981}
.cc-card.cc-rejected{border-left-color:var(--danger)}
.cc-card.cc-rejected .cc-num{color:var(--danger)}
.cc-card.cc-hold{border-left-color:#eab308}
.cc-card.cc-hold .cc-num{color:#eab308}
/* ===== CANDIDATES PAGE ===== */
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
.rp-filter{position:relative}
.rp-filter select{padding:9px 30px 9px 12px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;background:#fff;outline:none;cursor:pointer;font-family:inherit;appearance:auto}
.rp-filter select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
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
.rp-table .rp-btn-text.rp-btn-danger{border-color:var(--danger);color:var(--danger);background:rgba(220,38,38,.04)}
.rp-table .rp-btn-text.rp-btn-danger:hover{background:var(--danger);color:#fff}
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
/* View candidate modal */
.view-candidate-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px 24px}
.view-candidate-grid .vc-item label{display:block;font-size:11px;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.4px;margin-bottom:2px}
.view-candidate-grid .vc-item span{font-size:14px;font-weight:500;color:var(--gray-900)}
.view-candidate-grid .vc-item-full{grid-column:1/-1}
/* ===== RESPONSIVE ===== */
@media(max-width:900px){
 .rp-top h1{font-size:18px}
 .rp-search input{width:200px}
}
@media(max-width:768px){
 .page-content{padding:16px}
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;margin-bottom:18px}
 .cc-card{padding:14px 14px;gap:10px}
 .cc-card .cc-num{font-size:22px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
 .rp-top h1 small{display:block;margin:4px 0 0;font-size:12px}
 .rp-actions{width:100%}
 .rp-search{flex:1}
 .rp-search input{width:100%!important}
 .rp-filter{width:100%}
 .rp-filter select{width:100%}
 .rp-count{width:100%;text-align:center}
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
 .view-candidate-grid{grid-template-columns:1fr;gap:10px 16px}
}
@media(max-width:480px){
 .page-content{padding:12px}
 .cc-grid{grid-template-columns:repeat(2,1fr);gap:8px;margin-bottom:16px}
 .cc-card{padding:12px;gap:8px;border-left-width:3px}
 .cc-card .cc-num{font-size:20px}
 .cc-card .cc-label{font-size:11px}
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
';

ob_start();
?>
<div class="cc-grid" id="statsGrid">
    <div class="cc-card cc-total"><div><div class="cc-num">0</div><div class="cc-label">Loading...</div></div></div>
</div>

<div class="rp-top">
    <h1>All Candidates <small id="resultCount"></small></h1>
    <div class="rp-actions">
        <div class="rp-filter">
            <select id="deptFilter" onchange="applyFilters()">
                <option value="">All Departments</option>
            </select>
        </div>
        <div class="rp-search">
            <span class="rp-search-icon">&#x1F50D;</span>
            <input type="text" id="searchInput" placeholder="Search by name, code, email, job..." oninput="applyFilters()" autocomplete="off">
            <button class="rp-search-clear" id="searchClear" onclick="clearSearch()">&times;</button>
        </div>
        <button class="btn btn-primary" onclick="resetCandidateForm();openModal('addCandidateModal')">+ Add Candidate</button>
    </div>
</div>

<div class="rp-card">
    <div class="rp-table-wrap">
        <table class="rp-table" id="candidateTable">
            <thead>
                <tr>
                    <th class="rp-sortable" onclick="sortBy(0)">Code <span class="rp-sort-arrow" id="sortArrow0"></span></th>
                    <th class="rp-sortable" onclick="sortBy(1)">Candidate Name <span class="rp-sort-arrow" id="sortArrow1"></span></th>
                    <th class="rp-sortable" onclick="sortBy(2)">Email <span class="rp-sort-arrow" id="sortArrow2"></span></th>
                    <th class="rp-sortable" onclick="sortBy(3)">Phone <span class="rp-sort-arrow" id="sortArrow3"></span></th>
                    <th class="rp-sortable" onclick="sortBy(4)">Job Applied <span class="rp-sort-arrow" id="sortArrow4"></span></th>
                    <th class="rp-sortable" onclick="sortBy(5)">Applied Date <span class="rp-sort-arrow" id="sortArrow5"></span></th>
                    <th class="rp-sortable" onclick="sortBy(6)">Status <span class="rp-sort-arrow" id="sortArrow6"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="candidateTableBody"></tbody>
        </table>
    </div>
    <div class="rp-mobile-list" id="mobileList"></div>
    <div id="paginationBar" class="rp-pagination"></div>
</div>

<!-- View Candidate Modal -->
<div class="modal-overlay" id="viewCandidateModal" onclick="if(event.target===this)closeModal('viewCandidateModal')">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Candidate Details</h3>
            <button class="modal-close" onclick="closeModal('viewCandidateModal')">&times;</button>
        </div>
        <div class="modal-body view-candidate-grid" id="viewCandidateBody"></div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline btn-sm" onclick="closeModal('viewCandidateModal')">Close</button>
        </div>
    </div>
</div>

<!-- Add / Edit Candidate Modal -->
<div class="modal-overlay" id="addCandidateModal" onclick="if(event.target===this)closeModal('addCandidateModal')">
    <div class="modal modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3 id="candModalTitle">Add Candidate</h3>
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
            </form>
        </div>
        <div class="modal-footer modal-footer-light">
            <button class="btn btn-outline" onclick="closeModal('addCandidateModal');resetCandidateForm()">Cancel</button>
            <button class="btn btn-primary" id="candSubmitBtn" onclick="window.ajaxSaveCandidate()">Save Candidate</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
