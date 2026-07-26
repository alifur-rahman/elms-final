var modules = [];
var allTags = [];
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;

function loadData() {
    apiGet("ajax/merge_tags_list.php", null, function(res) {
        allTags = res.success && res.data ? res.data : [];
        updateCounts();
        renderView();
    });
    apiGet("ajax/migrate_modules.php", null, function() {
        apiGet("../includes/ajax/list_modules.php", null, function(res) {
            modules = res.success && res.data ? res.data : [];
        });
    });
}

function updateCounts() {
    var total = allTags.length;
    var counts = {};
    for (var i = 0; i < allTags.length; i++) {
        var t = allTags[i].type;
        counts[t] = (counts[t] || 0) + 1;
    }
    document.getElementById("cntTotal").textContent = total;
    document.getElementById("cntOffer").textContent = counts["Offer"] || 0;
    document.getElementById("cntConfirmation").textContent = counts["Confirmation"] || 0;
    document.getElementById("cntProbation").textContent = counts["Probation"] || 0;
    document.getElementById("cntGeneral").textContent = counts["General"] || 0;
}

function getFiltered() {
    var q = (document.getElementById("searchInput").value || "").toLowerCase();
    var filtered = allTags;
    if (q) {
        filtered = filtered.filter(function(t) {
            return (t.tag || "").toLowerCase().indexOf(q) > -1 ||
                   (t.label || "").toLowerCase().indexOf(q) > -1 ||
                   (t.type || "").toLowerCase().indexOf(q) > -1 ||
                   (t.description || "").toLowerCase().indexOf(q) > -1;
        });
    }
    return filtered;
}

function sortBy(col) {
    if (sortCol === col) sortAsc = !sortAsc;
    else { sortCol = col; sortAsc = true; }
    renderView();
}

function getSortVal(t, col) {
    if (col === "tag") return (t.tag || "").toLowerCase();
    if (col === "label") return (t.label || "").toLowerCase();
    if (col === "type") return (t.type || "").toLowerCase();
    if (col === "desc") return (t.description || "").toLowerCase();
    return "";
}

function updateSortArrows(count) {
    for (var i = 0; i < count; i++) {
        var el = document.getElementById("listSortArrow" + i);
        if (!el) continue;
        var c = el.getAttribute("data-col");
        if (c === sortCol) el.innerHTML = sortAsc ? "&#9650;" : "&#9660;";
        else el.innerHTML = "&#8597;";
    }
}

function getModuleBadge(type) {
    var cls = "badge-general";
    if (type === "Offer") cls = "badge-offer";
    else if (type === "Confirmation") cls = "badge-confirmation";
    else if (type === "Probation") cls = "badge-probation";
    return '<span class="badge ' + cls + '">' + type + '</span>';
}

function renderView() {
    var filtered = getFiltered();
    if (sortCol) {
        filtered.sort(function(a, b) {
            var va = getSortVal(a, sortCol);
            var vb = getSortVal(b, sortCol);
            if (va < vb) return sortAsc ? -1 : 1;
            if (va > vb) return sortAsc ? 1 : -1;
            return 0;
        });
    }
    updateSortArrows(4);

    var total = filtered.length;
    var totalPages = Math.max(1, Math.ceil(total / pgRows));
    if (pgPage > totalPages) pgPage = totalPages;
    var start = (pgPage - 1) * pgRows;
    var pageData = filtered.slice(start, start + pgRows);

    renderTable(pageData);
    renderMobileList(pageData);
    renderPagination(total, totalPages);
}

function renderTable(data) {
    var tbody = document.getElementById("tagTableBody");
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5"><div class="rp-empty"><div class="rp-empty-icon">&#127991;</div><h3>No merge tags found</h3><p>Click "+ Add Tag" to register your first merge tag.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < data.length; i++) {
        var t = data[i];
        html += '<tr>' +
            '<td><span class="tag-code">{{' + t.tag + '}}</span></td>' +
            '<td><strong>' + t.label + '</strong></td>' +
            '<td>' + getModuleBadge(t.type) + '</td>' +
            '<td>' + (t.description || '<span style="color:var(--gray-400)">-</span>') + '</td>' +
            '<td><div class="rp-actions-cell">' +
                '<button class="rp-btn-text rp-btn-warning" onclick="openEditTag(' + t.id + ')">Edit</button>' +
                '<button class="rp-btn-text rp-btn-danger" onclick="deleteTag(' + t.id + ',\'' + t.tag.replace(/'/g,"\\'") + '\')">Delete</button>' +
            '</div></td>' +
        '</tr>';
    }
    tbody.innerHTML = html;
}

function renderMobileList(data) {
    var container = document.getElementById("mobileList");
    if (data.length === 0) {
        container.innerHTML = '<div class="rp-empty" style="margin:32px 16px"><div class="rp-empty-icon">&#127991;</div><h3>No merge tags found</h3><p>Click "+ Add Tag" to register your first merge tag.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < data.length; i++) {
        var t = data[i];
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + t.label + '<small>{{' + t.tag + '}}</small></div>' +
                '<div class="rp-mob-badges">' + getModuleBadge(t.type) + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Tag</label><span class="tag-code">{{' + t.tag + '}}</span></div>' +
                '<div class="rp-mob-field"><label>Description</label><span>' + (t.description || '-') + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer"><div class="rp-actions-cell">' +
                '<button class="rp-btn-text rp-btn-warning" onclick="openEditTag(' + t.id + ')">Edit</button>' +
                '<button class="rp-btn-text rp-btn-danger" onclick="deleteTag(' + t.id + ',\'' + t.tag.replace(/'/g,"\\'") + '\')">Delete</button>' +
            '</div></div>' +
        '</div>';
    }
    container.innerHTML = html;
}

function renderPagination(total, totalPages) {
    var pgBar = document.getElementById("pgBar");
    if (total <= pgRows) { pgBar.style.display = "none"; return; }
    pgBar.style.display = "";
    var info = document.getElementById("pgInfo");
    var center = document.getElementById("pgCenter");
    var rowsInfo = document.getElementById("pgRows");
    info.textContent = "Showing " + (total === 0 ? 0 : (pgPage - 1) * pgRows + 1) + "-" + Math.min(pgPage * pgRows, total) + " of " + total;

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

function openAddTag() {
    document.getElementById("tagFormTitle").textContent = "Add Merge Tag";
    document.getElementById("tagFormId").value = 0;
    document.getElementById("tagFormTag").value = "";
    document.getElementById("tagFormLabel").value = "";
    document.getElementById("tagFormDesc").value = "";
    var sel = document.getElementById("tagFormType");
    sel.innerHTML = "";
    for (var i = 0; i < modules.length; i++) {
        sel.innerHTML += '<option value="' + modules[i].module_name + '">' + modules[i].label + '</option>';
    }
    openModal("tagFormModal");
}

function openEditTag(id) {
    var found = null;
    for (var i = 0; i < allTags.length; i++) {
        if (allTags[i].id === id) { found = allTags[i]; break; }
    }
    if (!found) { showAlert("Tag not found", "error"); return; }
    document.getElementById("tagFormTitle").textContent = "Edit Merge Tag";
    document.getElementById("tagFormId").value = found.id;
    var sel = document.getElementById("tagFormType");
    sel.innerHTML = "";
    for (var i = 0; i < modules.length; i++) {
        sel.innerHTML += '<option value="' + modules[i].module_name + '">' + modules[i].label + '</option>';
    }
    sel.value = found.type;
    document.getElementById("tagFormTag").value = found.tag;
    document.getElementById("tagFormLabel").value = found.label;
    document.getElementById("tagFormDesc").value = found.description || "";
    openModal("tagFormModal");
}

function submitTagForm() {
    var id = parseInt(document.getElementById("tagFormId").value);
    var type = document.getElementById("tagFormType").value;
    var tag = document.getElementById("tagFormTag").value.trim().toUpperCase();
    var label = document.getElementById("tagFormLabel").value.trim();
    var desc = document.getElementById("tagFormDesc").value.trim();
    if (!tag) { showAlert("Tag name is required", "error"); return; }
    if (!label) { showAlert("Label is required", "error"); return; }
    var payload = { type: type, tag: tag, label: label, description: desc };
    var endpoint = id > 0 ? "ajax/merge_tags_update.php" : "ajax/merge_tags_create.php";
    if (id > 0) payload.id = id;
    apiPost(endpoint, payload, function(res) {
        showAlert(res.message || "Tag saved!", "success");
        closeModal("tagFormModal");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save tag", "error");
    });
}

function deleteTag(id, tag) {
    if (!confirm('Delete merge tag "' + tag + '"?')) return;
    apiPost("ajax/merge_tags_delete.php", { id: id }, function(res) {
        showAlert(res.message || "Tag deleted", "success");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to delete tag", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    loadData();
});