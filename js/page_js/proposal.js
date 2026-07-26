var proposalData = [];
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
    renderTable();
}

function getSortVal(c) {
    switch (sortCol) {
        case 0: return ((c.first_name || "") + " " + (c.last_name || "")).toLowerCase();
        case 1: return (c.job_title || "").toLowerCase();
        case 2: return c.expected_salary || 0;
        case 3: return c.proposed_amount || 0;
        case 4: return (c.proposal_status || "").toLowerCase();
        case 5: return c.version || 0;
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
    var q = document.getElementById("searchProposal").value.trim().toLowerCase();
    var list = proposalData.filter(function(c) {
        var name = ((c.first_name || "") + " " + (c.last_name || "") + " " + (c.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    });
    return sortData(list);
}

function renderPaginationBar(total, totalPages) {
    var container = document.getElementById("paginationProposal");
    if (totalPages <= 1) { container.innerHTML = ""; return; }
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
    container.innerHTML = html;
}

function goToPage(p) {
    var filtered = getFiltered();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (p < 1) p = 1;
    if (p > totalPages) p = totalPages;
    if (p === currentPage) return;
    currentPage = p;
    renderTable();
}

function changePerPage(n) {
    perPage = parseInt(n);
    currentPage = 1;
    renderTable();
}

function applyFilter() {
    document.getElementById("searchClear").style.display = document.getElementById("searchProposal").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}

function clearSearch() {
    document.getElementById("searchProposal").value = "";
    document.getElementById("searchClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderStats() {
    var pending = 0, approvals = 0, letters = 0, responses = 0;
    var done = 0;
    function checkDone() {
        done++;
        if (done === 4) {
            document.getElementById("ccProposals").textContent = pending;
            document.getElementById("ccApprovals").textContent = approvals;
            document.getElementById("ccLetters").textContent = letters;
            document.getElementById("ccResponse").textContent = responses;
        }
    }
    apiGet("ajax/list_proposals.php", null, function(res) {
        var list = res.success && res.data ? res.data : [];
        pending = list.filter(function(c) { return c.proposal_status !== "Finalized"; }).length;
        checkDone();
    }, function() { checkDone(); });
    apiGet("ajax/list_pending.php", null, function(res) {
        approvals = (res.success && res.data) ? res.data.length : 0;
        checkDone();
    }, function() { checkDone(); });
    apiGet("ajax/list_offers.php?status=Approved&with_letter=1", null, function(res) {
        var list = res.success && res.data ? res.data : [];
        letters = list.filter(function(o) { return o.letter_status !== "Sent" && o.letter_status !== "Generated"; }).length;
        checkDone();
    }, function() { checkDone(); });
    apiGet("ajax/list_offers.php?status=Approved&response_status=Pending", null, function(res) {
        responses = (res.success && res.data) ? res.data.length : 0;
        checkDone();
    }, function() { checkDone(); });
}

function renderMobileCards(pageData) {
    var container = document.getElementById("proposalMobileList");
    if (pageData.length === 0) {
        container.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4B0;</div><h3>No candidates ready for salary proposal</h3><p>Candidates will appear here after selection</p></div>";
        return;
    }
    var html = "";
    pageData.forEach(function(c) {
        var name = c.first_name + " " + c.last_name;
        var salary = c.expected_salary ? formatCurrency(c.expected_salary, "\\u09F3") : "-";
        var proposed = c.proposed_amount ? formatCurrency(c.proposed_amount, "\\u09F3") : "-";
        var status = c.proposal_status || "None";
        var statusClass = getStatusBadgeClass(status);
        var action = "";
        if (status === "None" || status === "Draft") {
            action = "<a class=\"btn btn-primary btn-sm\" href=\"salary_breakdown.php?candidate_id=" + c.id + "\">Propose Salary</a>";
        } else if (status === "Proposed") {
            action = "<button class=\"btn btn-outline btn-sm\" onclick=\"submitForApproval(" + c.id + ")\">Submit for Approval</button> <a class=\"btn btn-outline btn-sm\" href=\"salary_breakdown.php?candidate_id=" + c.id + "&proposal_id=" + (c.proposal_id || "") + "\">Revise</a>";
        } else if (status === "Pending_Approval") {
            action = "<span class=\"badge badge-in-progress\">Pending Approval</span>";
        } else if (status === "Finalized") {
            action = "<span class=\"badge badge-success\">Ready for Offer</span>";
        }
        html += "<div class=\"rp-mob-card\">";
        html += "<div class=\"rp-mob-top\">";
        html += "<div class=\"rp-mob-title\">" + name + "<small>" + (c.job_title || "N/A") + "</small></div>";
        html += "<div class=\"rp-mob-badges\"><span class=\"badge " + statusClass + "\">" + status + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-grid\">";
        html += "<div class=\"rp-mob-field\"><label>Expected</label><span>" + salary + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Proposed</label><span>" + proposed + "</span></div>";
        html += "<div class=\"rp-mob-field\"><label>Version</label><span>" + (c.version ? "v" + c.version : "-") + "</span></div>";
        html += "</div>";
        html += "<div class=\"rp-mob-footer\">" + action + "</div>";
        html += "</div>";
    });
    container.innerHTML = html;
}

function getStatusBadgeClass(status) {
    switch (status) {
        case "Finalized": return "badge-success";
        case "Pending_Approval": return "badge-in-progress";
        case "Proposed": return "badge-in-progress";
        case "Draft": return "badge";
        default: return "badge";
    }
}

function renderTable() {
    var filtered = getFiltered();
    var totalPages = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);

    var tbody = document.getElementById("proposalTableBody");
    tbody.innerHTML = "";
    if (pageData.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">&#x1F4B0;</div><h3>No candidates ready for salary proposal</h3><p>Candidates will appear here after selection</p></div></td></tr>";
    } else {
        pageData.forEach(function(c) {
            var name = c.first_name + " " + c.last_name;
            var salary = c.expected_salary ? formatCurrency(c.expected_salary, "\\u09F3") : "-";
            var proposed = c.proposed_amount ? formatCurrency(c.proposed_amount, "\\u09F3") : "-";
            var status = c.proposal_status || "None";
            var statusClass = getStatusBadgeClass(status);
            var action = "";
            if (status === "None" || status === "Draft") {
                action = "<a class=\"rp-btn-text rp-btn-primary\" href=\"salary_breakdown.php?candidate_id=" + c.id + "\">Propose</a>";
            } else if (status === "Proposed") {
                action = "<button class=\"rp-btn-text rp-btn-primary\" onclick=\"submitForApproval(" + c.id + ")\">Submit</button> <a class=\"rp-btn-text rp-btn-view\" href=\"salary_breakdown.php?candidate_id=" + c.id + "&proposal_id=" + (c.proposal_id || "") + "\">Revise</a>";
            } else if (status === "Pending_Approval") {
                action = "<span class=\"badge badge-in-progress\">Pending Approval</span>";
            } else if (status === "Finalized") {
                action = "<span class=\"badge badge-success\">Ready for Offer</span>";
            }
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + name + "</strong></td>" +
                "<td>" + (c.job_title || "N/A") + "</td>" +
                "<td>" + salary + "</td>" +
                "<td>" + proposed + "</td>" +
                "<td><span class=\"badge " + statusClass + "\">" + status + "</span></td>" +
                "<td>" + (c.version ? "v" + c.version : "-") + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + action + "</div></td>";
            tbody.appendChild(tr);
        });
    }

    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, totalPages);
    updateSortArrows();
}

function submitForApproval(candidateId) {
    if (!confirm("Submit this proposal for approval?")) return;
    apiPost("ajax/proposal_submit.php", {
        candidate_id: candidateId
    }, function(res) {
        showAlert(res.message || "Proposal submitted for approval!", "success");
        renderPage();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to submit", "error");
    });
}

function renderPage() {
    apiGet("ajax/list_proposals.php", null, function(res) {
        proposalData = res.success && res.data ? res.data : [];
        renderStats();
        renderTable();
    }, function() {
        proposalData = [];
        renderStats();
        renderTable();
    });
}

renderPage();