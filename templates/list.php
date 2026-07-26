<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
$basePath    = '..';
$pageTitle   = 'Letter Templates';
$activePage  = 'templates';
$breadcrumb  = 'Home / Templates';
$pageStyles  = '
/* ===== COUNT CARDS ===== */
.cc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:14px;margin-bottom:24px}
.cc-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px;border-left:4px solid var(--gray-200);transition:box-shadow .2s,transform .15s}
.cc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.08);transform:translateY(-1px)}
.cc-card .cc-num{font-size:26px;font-weight:700;line-height:1;color:var(--gray-900)}
.cc-card .cc-label{font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px}
.cc-card.cc-total{border-left-color:var(--primary)}
.cc-card.cc-total .cc-num{color:var(--primary)}
.cc-card.cc-active{border-left-color:var(--success)}
.cc-card.cc-active .cc-num{color:var(--success)}
.cc-card.cc-inactive{border-left-color:var(--danger)}
.cc-card.cc-inactive .cc-num{color:var(--danger)}
.cc-card.cc-offer{border-left-color:#8b5cf6}
.cc-card.cc-offer .cc-num{color:#8b5cf6}

/* ===== HEADER ===== */
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.rp-top h1{font-size:20px;font-weight:700;color:var(--gray-900);margin:0;white-space:nowrap}
.rp-top h1 small{font-size:13px;font-weight:400;color:var(--gray-400);margin-left:8px}
.rp-search{position:relative}
.rp-search input{width:260px;padding:9px 12px 9px 36px;border:1px solid var(--gray-200);border-radius:8px;font-size:13px;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit}
.rp-search input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.rp-search .rp-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:14px;pointer-events:none}
.rp-top-actions{display:flex;gap:10px;align-items:center}

/* ===== TABLE CARD ===== */
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
.rp-table .rp-actions-cell{display:flex;gap:6px;white-space:nowrap;align-items:center}
.rp-btn-text{padding:6px 12px;border-radius:6px;border:1px solid var(--gray-200);background:#fff;cursor:pointer;font-size:12px;font-weight:500;transition:all .15s;color:var(--gray-600);font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;flex:1}
.rp-btn-text:hover{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-btn-text.rp-btn-primary{border-color:var(--primary);color:var(--primary);background:rgba(37,99,235,.04)}
.rp-btn-text.rp-btn-primary:hover{background:var(--primary);color:#fff}
.rp-btn-text.rp-btn-warning{border-color:#f59e0b;color:#b45309;background:rgba(245,158,11,.04)}
.rp-btn-text.rp-btn-warning:hover{background:#f59e0b;color:#fff}
.rp-btn-text.rp-btn-danger{border-color:var(--danger);color:var(--danger);background:rgba(239,68,68,.04)}
.rp-btn-text.rp-btn-danger:hover{background:var(--danger);color:#fff}
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

/* ===== BADGES ===== */
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap}
.badge-active{background:#e6f4ea;color:#188038}
.badge-inactive{background:#fce8e6;color:#c5221f}
.badge-offer{background:#f0e6ff;color:#7c3aed}
.badge-confirmation{background:#e8f0fe;color:#1a73e8}
.badge-probation{background:#fef7e0;color:#e37400}
.badge-general{background:var(--gray-100);color:var(--gray-600)}

/* ===== PREVIEW MODAL ===== */
.preview-wide{max-width:900px!important;width:90%!important;max-height:90vh!important;height:auto!important}
.preview-wide .modal-body{overflow-y:auto;flex:1;padding:20px 24px}
.preview-content-wrap{padding:40px 48px;border:1px solid var(--gray-200);border-radius:8px;min-height:600px;background:#fff;line-height:1.8;font-size:15px}
.preview-content-wrap h1{text-align:center;color:#1a73e8;margin-bottom:4px}
.preview-content-wrap h2{color:#1a73e8;border-bottom:2px solid #1a73e8;padding-bottom:6px;margin:20px 0 10px}
.preview-content-wrap table{width:100%;border-collapse:collapse;margin:10px 0;font-size:13px}
.preview-content-wrap th{background:#f5f5f5;border:1px solid #ddd;padding:6px 10px;text-align:left}
.preview-content-wrap td{border:1px solid #ddd;padding:6px 10px}

/* ===== QUILL EDITOR ===== */
#editorContainer .ql-toolbar{border-color:var(--gray-200);border-radius:6px 6px 0 0;background:#f9fafb}
#editorContainer .ql-container{border-color:var(--gray-200);min-height:280px;font-size:14px;font-family:inherit}
#editorContainer .ql-editor{min-height:280px;line-height:1.8;padding:16px 20px}
#editorContainer .ql-editor p{margin:0 0 8px}
#editorContainer{border:1px solid var(--gray-200);border-radius:6px;overflow:hidden}
.merge-tags-bar{display:flex;flex-wrap:wrap;gap:4px;padding:8px 12px;background:#f8f9fa;border:1px solid var(--gray-200);border-bottom:none;border-radius:6px 6px 0 0;align-items:center}
.merge-tags-bar .mt-label{font-size:11px;color:var(--gray-500);margin-right:6px;font-weight:600;text-transform:uppercase;letter-spacing:.3px;white-space:nowrap}
.merge-tag-btn{font-size:11px;padding:3px 10px;border:1px solid var(--gray-200);border-radius:4px;background:#fff;cursor:pointer;color:var(--primary);font-family:monospace;transition:all .15s}
.merge-tag-btn:hover{border-color:var(--primary);background:#e8f0fe}
.ql-editor .merge-tag{display:inline-block;background:#e8f0fe;color:var(--primary);border-radius:3px;padding:0 4px;font-family:monospace;font-size:13px;font-weight:600}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
 .rp-top h1{font-size:18px}
 .rp-search input{width:200px}
}
@media(max-width:768px){
 .page-content{padding:16px}
 .cc-grid{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;margin-bottom:18px}
 .cc-card{padding:14px;gap:10px}
 .cc-card .cc-num{font-size:22px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
 .rp-top h1 small{display:block;margin:4px 0 0;font-size:12px}
 .rp-search{flex:1}
 .rp-search input{width:100%!important}
 .rp-top-actions{width:100%}
 .rp-top-actions .btn{flex:1}
 .rp-table-wrap{display:none}
 .rp-mobile-list{display:block}
 .rp-mob-card{background:#fff;border-radius:10px;padding:16px;margin:8px 16px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid var(--gray-100)}
 .rp-mob-card:last-child{margin-bottom:16px}
 .rp-mob-top{display:flex;align-items:start;justify-content:space-between;gap:10px;margin-bottom:10px}
 .rp-mob-title{font-size:15px;font-weight:600;color:var(--gray-900);margin:0}
 .rp-mob-title small{font-size:12px;font-weight:400;color:var(--gray-400);display:block;margin-top:2px}
 .rp-mob-badges{display:flex;gap:6px;flex-shrink:0;flex-wrap:wrap}
 .rp-mob-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 16px;margin-bottom:12px}
 .rp-mob-field label{display:block;font-size:11px;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px}
 .rp-mob-field span{font-size:13px;color:var(--gray-700);font-weight:500}
 .rp-mob-footer{display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--gray-100)}
 .rp-mob-footer .rp-actions-cell{width:100%}
 .rp-mob-footer .rp-btn-text{padding:9px 12px;font-size:12px;font-weight:600;min-height:38px}
 .rp-pagination{flex-direction:column;align-items:center;gap:8px}
 .rp-pg-center{flex-wrap:wrap;justify-content:center}
 .rp-pg-rows{width:100%;justify:center}
 .preview-wide{width:95%!important}
 .preview-content-wrap{padding:24px;min-height:400px;font-size:14px}
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
 .rp-mob-footer{flex-direction:column;gap:6px}
 .rp-mob-footer .rp-btn-text{padding:11px 14px;font-size:13px;min-height:42px;border-radius:10px}
 .preview-content-wrap{padding:16px;min-height:300px;font-size:13px;line-height:1.6}
 .preview-content-wrap h1{font-size:16px}
 .preview-content-wrap h2{font-size:14px}
}
';
$pageScripts = <<<'EOSCRIPT'
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
EOSCRIPT;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - ERP HRIS</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
</head>
<body>
<?php include $basePath . '/includes/sidebar.php'; ?>
<main class="main-content">
    <?php include $basePath . '/includes/header.php'; ?>
    <div class="page-content">

        <div class="cc-grid">
            <div class="cc-card cc-total"><div><div class="cc-num" id="cntTotal">0</div><div class="cc-label">Total Templates</div></div></div>
            <div class="cc-card cc-active"><div><div class="cc-num" id="cntActive">0</div><div class="cc-label">Active</div></div></div>
            <div class="cc-card cc-inactive"><div><div class="cc-num" id="cntInactive">0</div><div class="cc-label">Inactive</div></div></div>
            <div class="cc-card cc-offer"><div><div class="cc-num" id="cntOffer">0</div><div class="cc-label">Offer Templates</div></div></div>
        </div>

        <div class="rp-top">
            <h1>Letter Templates <small>Manage templates for all modules</small></h1>
           
            <div class="rp-top-actions">
                 <div class="rp-search">
                    <span class="rp-search-icon">&#128269;</span>
                    <input type="text" id="searchInput" placeholder="Search templates..." onkeyup="pgPage=1;renderView()">
                </div>
                <button class="btn btn-primary" onclick="openNewTemplate()">+ New Template</button>
            </div>
        </div>

        <div class="rp-card">
            <div class="rp-table-wrap">
                <table class="rp-table">
                    <thead><tr>
                        <th class="rp-sortable" onclick="sortBy('name')">Template Name <span class="rp-sort-arrow" id="listSortArrow0" data-col="name">&#8597;</span></th>
                        <th class="rp-sortable" onclick="sortBy('type')">Module <span class="rp-sort-arrow" id="listSortArrow1" data-col="type">&#8597;</span></th>
                        <th class="rp-sortable" onclick="sortBy('status')">Status <span class="rp-sort-arrow" id="listSortArrow2" data-col="status">&#8597;</span></th>
                        <th class="rp-sortable" onclick="sortBy('updated')">Last Updated <span class="rp-sort-arrow" id="listSortArrow3" data-col="updated">&#8597;</span></th>
                        <th>Action</th>
                    </tr></thead>
                    <tbody id="templateTableBody"></tbody>
                </table>
            </div>
            <div id="mobileList" class="rp-mobile-list"></div>
            <div id="pgBar" class="rp-pagination">
                <div id="pgInfo" class="rp-pg-info"></div>
                <div id="pgCenter" class="rp-pg-center"></div>
                <div id="pgRows" class="rp-pg-rows"></div>
            </div>
        </div>

        <!-- Add/Edit Template Modal -->
        <div class="modal-overlay" id="templateFormModal" onclick="if(event.target===this)closeModal('templateFormModal')" style="display:none">
            <div class="modal modal-xl">
                <div class="modal-header modal-header-navy">
                    <h3 id="templateFormTitle">New Template</h3>
                    <button class="modal-close" onclick="closeModal('templateFormModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="templateForm">
                        <input type="hidden" id="templateFormId" value="0">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>Template Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="templateFormName" placeholder="e.g. Standard Offer Letter">
                            </div>
                            <div class="form-group">
                                <label>Module</label>
                                <select class="form-control" id="templateFormType" onchange="onModuleChange()">
                                    <option value="Offer">Offer</option>
                                    <option value="Confirmation">Confirmation</option>
                                    <option value="Probation">Probation</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label>Content <span class="required">*</span></label>
                            <div class="merge-tags-bar" id="mergeTagsBar">
                                <span class="mt-label">Merge Tags:</span>
                                <span id="mergeTagsList"></span>
                            </div>
                            <div id="editorContainer"><div id="templateEditor"></div></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-light">
                    <button type="button" class="btn btn-outline" onclick="closeModal('templateFormModal')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitTemplateForm()">Save Template</button>
                </div>
            </div>
        </div>

        <!-- View Template Modal -->
        <div class="modal-overlay" id="viewTemplateModal" onclick="if(event.target===this)closeModal('viewTemplateModal')" style="display:none">
            <div class="modal preview-wide">
                <div class="modal-header modal-header-navy">
                    <h3 id="viewTemplateTitle">Template Preview</h3>
                    <button class="modal-close" onclick="closeModal('viewTemplateModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="viewTemplateContent" class="preview-content-wrap"></div>
                </div>
                <div class="modal-footer modal-footer-light">
                    <button type="button" class="btn btn-outline" onclick="closeModal('viewTemplateModal')">Close</button>
                </div>
            </div>
        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<?php include $basePath . '/includes/footer.php'; ?>
</body>
</html>
