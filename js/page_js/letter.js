var lettersData = [];
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
        case 2: return o.salary || 0;
        case 3: return (o.letter_status || "Not Generated").toLowerCase();
        case 4: return o.letter_generated_at || "";
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
    for (var i = 0; i <= 4; i++) {
        var el = document.getElementById("sortArrow" + i);
        if (!el) continue;
        if (i === sortCol) {
            el.textContent = sortDir === "asc" ? "\\u25B2" : "\\u25BC";
            el.parentNode.classList.add("rp-sorted");
        } else {
            el.textContent = "\\u2195";
            el.parentNode.classList.remove("rp-sorted");
        }
    }
}

function getFiltered() {
    var q = document.getElementById("searchLetter").value.trim().toLowerCase();
    return sortData(lettersData.filter(function(o) {
        var name = ((o.candidate_name || "") + " " + (o.job_title || "")).toLowerCase();
        return !q || name.indexOf(q) !== -1;
    }));
}

function renderPaginationBar(total, totalPages) {
    var c = document.getElementById("paginationLetter");
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
    document.getElementById("searchLetterClear").style.display = document.getElementById("searchLetter").value.length > 0 ? "block" : "none";
    currentPage = 1;
    renderTable();
}
function clearSearch() {
    document.getElementById("searchLetter").value = "";
    document.getElementById("searchLetterClear").style.display = "none";
    currentPage = 1;
    renderTable();
}

function renderStats() {
    var total = lettersData.length;
    var draft = 0, generated = 0, sent = 0;
    lettersData.forEach(function(o) {
        var ls = o.letter_status;
        if (ls === "Sent") sent++;
        else if (ls === "Generated") generated++;
        else draft++;
    });
    document.getElementById("ccTotal").textContent = total;
    document.getElementById("ccDraft").textContent = draft;
    document.getElementById("ccGenerated").textContent = generated;
    document.getElementById("ccSent").textContent = sent;
}

function renderMobileCards(pageData) {
    var c = document.getElementById("letterMobileList");
    if (!pageData.length) {
        c.innerHTML = "<div class=\"rp-empty\"><div class=\"rp-empty-icon\">\\uD83D\\uDCC4</div><h3>No letters found</h3><p>Approve a salary proposal first</p></div>";
        return;
    }
    var h = "";
    pageData.forEach(function(o) {
        var idx = lettersData.indexOf(o);
        var ls = o.letter_status || "Not Generated";
        var lsClass = ls === "Sent" ? "badge badge-success" : ls === "Generated" ? "badge badge-in-progress" : "badge";
        var actions = "";
        if (ls === "Generated") {
            actions = "<a class=\"btn btn-primary btn-sm\" href=\"generate_letter.php?offer_id=" + o.id + "\">Edit</a>";
            actions += "<button class=\"btn btn-outline btn-sm\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
            actions += "<button class=\"btn btn-success btn-sm\" onclick=\"openSendEmail(" + idx + ")\">Send</button>";
        } else if (ls === "Sent") {
            actions = "<button class=\"btn btn-outline btn-sm\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
        } else {
            actions = "<a class=\"btn btn-primary btn-sm\" href=\"generate_letter.php?offer_id=" + o.id + "\">Generate</a>";
        }
        h += "<div class=\"rp-mob-card\">";
        h += "<div class=\"rp-mob-top\"><div class=\"rp-mob-title\">" + (o.candidate_name || "") + "<small>" + (o.job_title || "N/A") + "</small></div>";
        h += "<div class=\"rp-mob-badges\"><span class=\"" + lsClass + "\">" + ls + "</span></div></div>";
        h += "<div class=\"rp-mob-grid\">";
        h += "<div class=\"rp-mob-field\"><label>Salary</label><span>" + (o.salary ? formatCurrency(o.salary, "\\u09F3") : "-") + "</span></div>";
        h += "<div class=\"rp-mob-field\"><label>Generated</label><span>" + (o.letter_generated_at || "-") + "</span></div>";
        h += "</div><div class=\"rp-mob-footer\">" + actions + "</div></div>";
    });
    c.innerHTML = h;
}

function renderTable() {
    var filtered = getFiltered();
    var tp = Math.ceil(filtered.length / perPage) || 1;
    if (currentPage > tp) currentPage = tp;
    var start = (currentPage - 1) * perPage;
    var pageData = filtered.slice(start, start + perPage);
    var tbody = document.getElementById("letterTableBody");
    tbody.innerHTML = "";
    if (!pageData.length) {
        tbody.innerHTML = "<tr><td colspan=\"6\"><div class=\"rp-empty\"><div class=\"rp-empty-icon\">\\uD83D\\uDCC4</div><h3>No letters found</h3><p>Approve a salary proposal first</p></div></td></tr>";
    } else {
        pageData.forEach(function(o) {
            var idx = lettersData.indexOf(o);
            var ls = o.letter_status || "Not Generated";
            var lsClass = ls === "Sent" ? "badge badge-success" : ls === "Generated" ? "badge badge-in-progress" : "badge";
            var actions = "";
            if (ls === "Generated") {
                actions = "<a class=\"rp-btn-text rp-btn-primary\" href=\"generate_letter.php?offer_id=" + o.id + "\">Edit</a>";
                actions += " <button class=\"rp-btn-text rp-btn-view\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
                actions += " <button class=\"rp-btn-text rp-btn-warning\" onclick=\"openSendEmail(" + idx + ")\">Send</button>";
            } else if (ls === "Sent") {
                actions = "<button class=\"rp-btn-text rp-btn-view\" onclick=\"previewLetter(" + o.id + ")\">Preview</button>";
            } else {
                actions = "<a class=\"rp-btn-text rp-btn-primary\" href=\"generate_letter.php?offer_id=" + o.id + "\">Generate</a>";
            }
            var tr = document.createElement("tr");
            tr.innerHTML =
                "<td><strong>" + (o.candidate_name || "") + "</strong></td>" +
                "<td>" + (o.job_title || "N/A") + "</td>" +
                "<td>" + (o.salary ? formatCurrency(o.salary, "\\u09F3") : "-") + "</td>" +
                "<td><span class=\"" + lsClass + "\">" + ls + "</span></td>" +
                "<td>" + (o.letter_generated_at || "-") + "</td>" +
                "<td><div class=\"rp-actions-cell\">" + actions + "</div></td>";
            tbody.appendChild(tr);
        });
    }
    renderMobileCards(pageData);
    renderPaginationBar(filtered.length, tp);
    updateSortArrows();
}

function openSendEmail(idx) {
    var o = lettersData[idx];
    if (!o) return;
    document.getElementById("sendOfferId").value = o.id;
    document.getElementById("sendCandidateName").value = o.candidate_name || "";
    document.getElementById("sendEmail").value = o.email || "";
    document.getElementById("sendSubject").value = "Offer Letter - " + (o.job_title || "Position");
    document.getElementById("sendBody").value = "";
    openModal("sendEmailModal");
}

function submitSendEmail() {
    var email = document.getElementById("sendEmail").value;
    if (!email) { showAlert("Please enter recipient email", "error"); return; }
    apiPost("ajax/letter_send.php", {
        offer_id: document.getElementById("sendOfferId").value,
        recipient: email,
        subject: document.getElementById("sendSubject").value,
        body: document.getElementById("sendBody").value
    }, function(res) {
        showAlert(res.message || "Email sent!", "success");
        closeModal("sendEmailModal");
        renderPage();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to send email", "error");
    });
}

function previewLetter(offerId) {
    apiGet("ajax/letter_view.php?offer_id=" + offerId, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("previewContent").innerHTML = res.data.letter_content || "<p>No content</p>";
            openModal("previewLetterModal");
        } else {
            showAlert("Could not load letter preview", "error");
        }
    });
}

function renderPage() {
    apiGet("ajax/list_offers.php?status=Approved", null, function(res) {
        lettersData = res.success && res.data ? res.data : [];
        renderStats();
        renderTable();
    }, function() {
        lettersData = [];
        renderStats();
        renderTable();
    });
}

renderPage();