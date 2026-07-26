var allTemplates = [];
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;
var quillEditor = null;
var mergeTagsCache = {};

function loadData() {
    apiGet("ajax/templates_list.php", null, function(res) {
        allTemplates = res.success && res.data ? res.data : [];
        updateCounts();
        renderView();
    });
}

function updateCounts() {
    var total = allTemplates.length;
    var active = 0, inactive = 0, offer = 0;
    for (var i = 0; i < allTemplates.length; i++) {
        var t = allTemplates[i];
        if (t.status === "Active") active++;
        else inactive++;
        if (t.type === "Offer") offer++;
    }
    document.getElementById("cntTotal").textContent = total;
    document.getElementById("cntActive").textContent = active;
    document.getElementById("cntInactive").textContent = inactive;
    document.getElementById("cntOffer").textContent = offer;
}

function getFiltered() {
    var q = (document.getElementById("searchInput").value || "").toLowerCase();
    var filtered = allTemplates;
    if (q) {
        filtered = filtered.filter(function(t) {
            return (t.name || "").toLowerCase().indexOf(q) > -1 ||
                   (t.type || "").toLowerCase().indexOf(q) > -1 ||
                   (t.status || "").toLowerCase().indexOf(q) > -1;
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
    if (col === "name") return (t.name || "").toLowerCase();
    if (col === "type") return (t.type || "").toLowerCase();
    if (col === "status") return (t.status || "").toLowerCase();
    if (col === "updated") return t.updated_at || "";
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

function getStatusBadge(status) {
    if (status === "Active") return '<span class="badge badge-active">Active</span>';
    return '<span class="badge badge-inactive">Inactive</span>';
}

function getTypeBadge(type) {
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
    var tbody = document.getElementById("templateTableBody");
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5"><div class="rp-empty"><div class="rp-empty-icon">&#128196;</div><h3>No templates found</h3><p>Click "+ New Template" to create your first template.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < data.length; i++) {
        var t = data[i];
        var updated = t.updated_at ? new Date(t.updated_at).toLocaleDateString("en-US", {year:"numeric",month:"short",day:"numeric"}) : "-";
        html += '<tr>' +
            '<td><strong>' + t.name + '</strong></td>' +
            '<td>' + getTypeBadge(t.type) + '</td>' +
            '<td>' + getStatusBadge(t.status) + '</td>' +
            '<td>' + updated + '</td>' +
            '<td><div class="rp-actions-cell">' +
                '<button class="rp-btn-text rp-btn-primary" onclick="viewTemplate(' + t.id + ')">View</button>' +
                '<button class="rp-btn-text rp-btn-warning" onclick="editTemplate(' + t.id + ')">Edit</button>' +
                '<button class="rp-btn-text rp-btn-danger" onclick="deleteTemplate(' + t.id + ',\'' + t.name.replace(/'/g,"\\'") + '\')">Delete</button>' +
            '</div></td>' +
        '</tr>';
    }
    tbody.innerHTML = html;
}

function renderMobileList(data) {
    var container = document.getElementById("mobileList");
    if (data.length === 0) {
        container.innerHTML = '<div class="rp-empty" style="margin:32px 16px"><div class="rp-empty-icon">&#128196;</div><h3>No templates found</h3><p>Click "+ New Template" to create your first template.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < data.length; i++) {
        var t = data[i];
        var updated = t.updated_at ? new Date(t.updated_at).toLocaleDateString("en-US", {year:"numeric",month:"short",day:"numeric"}) : "-";
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + t.name + '<small>' + t.type + '</small></div>' +
                '<div class="rp-mob-badges">' + getStatusBadge(t.status) + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Module</label><span>' + t.type + '</span></div>' +
                '<div class="rp-mob-field"><label>Last Updated</label><span>' + updated + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer"><div class="rp-actions-cell">' +
                '<button class="rp-btn-text rp-btn-primary" onclick="viewTemplate(' + t.id + ')">View</button>' +
                '<button class="rp-btn-text rp-btn-warning" onclick="editTemplate(' + t.id + ')">Edit</button>' +
                '<button class="rp-btn-text rp-btn-danger" onclick="deleteTemplate(' + t.id + ',\'' + t.name.replace(/'/g,"\\'") + '\')">Delete</button>' +
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

function initEditor() {
    if (quillEditor) return;
    quillEditor = new Quill("#templateEditor", {
        theme: "snow",
        placeholder: "Compose your letter template here...",
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                [{ list: "ordered" }, { list: "bullet" }],
                [{ align: [] }],
                ["blockquote", "code-block"],
                [{ indent: "-1" }, { indent: "+1" }],
                ["clean"]
            ]
        }
    });
}

function setEditorContent(html) {
    initEditor();
    quillEditor.root.innerHTML = html;
}

function getEditorContent() {
    if (!quillEditor) return "";
    return quillEditor.root.innerHTML;
}

function insertMergeTag(tag) {
    initEditor();
    var range = quillEditor.getSelection(true);
    quillEditor.clipboard.dangerouslyPasteHTML(range.index, "<span class=\"merge-tag\">{{" + tag + "}}</span>");
    quillEditor.setSelection(range.index + 1);
}

function loadMergeTags(type, callback) {
    var url = "ajax/merge_tags_list.php" + (type ? "?type=" + encodeURIComponent(type) : "");
    apiGet(url, null, function(res) {
        var tags = res.success && res.data ? res.data : [];
        if (type) mergeTagsCache[type] = tags;
        if (callback) callback(tags);
    });
}

function onModuleChange() {
    var type = document.getElementById("templateFormType").value;
    renderMergeTagButtons(type);
}

function renderMergeTagButtons(type) {
    var container = document.getElementById("mergeTagsList");
    container.innerHTML = "";
    if (mergeTagsCache[type]) {
        renderTagButtons(mergeTagsCache[type], container);
    } else {
        loadMergeTags(type, function(tags) {
            renderTagButtons(tags, container);
        });
    }
}

function renderTagButtons(tags, container) {
    container.innerHTML = "";
    for (var i = 0; i < tags.length; i++) {
        var btn = document.createElement("button");
        btn.type = "button";
        btn.className = "merge-tag-btn";
        btn.title = tags[i].description || tags[i].label;
        btn.textContent = "{{" + tags[i].tag + "}}";
        btn.setAttribute("onclick", "insertMergeTag('" + tags[i].tag + "')");
        container.appendChild(btn);
    }
}

function viewTemplate(id) {
    apiGet("ajax/templates_get.php?id=" + id, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("viewTemplateTitle").textContent = res.data.name;
            document.getElementById("viewTemplateContent").innerHTML = res.data.content;
            openModal("viewTemplateModal");
        } else {
            showAlert("Template not found", "error");
        }
    });
}

function openNewTemplate() {
    document.getElementById("templateFormTitle").textContent = "New Template";
    document.getElementById("templateFormId").value = 0;
    document.getElementById("templateFormName").value = "";
    document.getElementById("templateFormType").value = "Offer";
    setEditorContent("");
    renderMergeTagButtons("Offer");
    openModal("templateFormModal");
}

function editTemplate(id) {
    apiGet("ajax/templates_get.php?id=" + id, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("templateFormTitle").textContent = "Edit Template";
            document.getElementById("templateFormId").value = res.data.id;
            document.getElementById("templateFormName").value = res.data.name;
            document.getElementById("templateFormType").value = res.data.type;
            setEditorContent(res.data.content);
            renderMergeTagButtons(res.data.type);
            openModal("templateFormModal");
        } else {
            showAlert("Template not found", "error");
        }
    });
}

function deleteTemplate(id, name) {
    if (!confirm("Delete template \"" + name + "\"? This cannot be undone.")) return;
    apiPost("ajax/templates_delete.php", { id: id }, function(res) {
        showAlert(res.message || "Template deleted", "success");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to delete", "error");
    });
}

function submitTemplateForm() {
    var id = document.getElementById("templateFormId").value;
    var name = document.getElementById("templateFormName").value.trim();
    var type = document.getElementById("templateFormType").value;
    var content = getEditorContent().trim();
    if (!name) { showAlert("Please enter a template name", "error"); return; }
    if (!content || content === "<p><br></p>" || content === "<br>") { showAlert("Please enter template content", "error"); return; }

    var endpoint = id > 0 ? "ajax/templates_update.php" : "ajax/templates_create.php";
    apiPost(endpoint, { id: id, name: name, type: type, content: content }, function(res) {
        showAlert(res.message || "Template saved!", "success");
        closeModal("templateFormModal");
        loadData();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save template", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    loadData();
    loadMergeTags("Offer", function(tags) {
        mergeTagsCache["Offer"] = tags;
    });
});