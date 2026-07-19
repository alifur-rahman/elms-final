<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
$basePath = $basePath ?? '..';
$pageTitle = $pageTitle ?? 'Letter Templates';
$activePage = $activePage ?? 'templates';
$pageStyles = $pageStyles ?? '';
$pageScripts = $pageScripts ?? '';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - ERP HRIS</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
    #editorContainer .ql-toolbar { border-color:var(--gray-200); border-radius:6px 6px 0 0; background:#f9fafb; }
    #editorContainer .ql-container { border-color:var(--gray-200); min-height:320px; font-size:14px; font-family:inherit; }
    #editorContainer .ql-editor { min-height:320px; line-height:1.8; padding:16px 20px; }
    #editorContainer .ql-editor p { margin:0 0 8px; }
    #editorContainer { border:1px solid var(--gray-200); border-radius:6px; overflow:hidden; }
    .merge-tags-bar { display:flex; flex-wrap:wrap; gap:4px; padding:8px 12px; background:#f8f9fa; border:1px solid var(--gray-200); border-bottom:none; border-radius:6px 6px 0 0; align-items:center; }
    .merge-tags-bar .mt-label { font-size:11px; color:var(--gray-500); margin-right:6px; font-weight:600; text-transform:uppercase; letter-spacing:.3px; white-space:nowrap; }
    .merge-tag-btn { font-size:11px; padding:3px 10px; border:1px solid var(--gray-200); border-radius:4px; background:#fff; cursor:pointer; color:var(--primary); font-family:monospace; transition:all .15s; }
    .merge-tag-btn:hover { border-color:var(--primary); background:#e8f0fe; }
    .ql-editor .merge-tag { display:inline-block; background:#e8f0fe; color:var(--primary); border-radius:3px; padding:0 4px; font-family:monospace; font-size:13px; font-weight:600; }

    .tag-row { display:flex; align-items:center; gap:8px; padding:8px 0; border-bottom:1px solid var(--gray-100); font-size:13px; }
    .tag-row:last-child { border-bottom:none; }
    .tag-row .tag-code { font-family:monospace; font-weight:600; color:var(--primary); min-width:160px; }
    .tag-row .tag-label { color:var(--gray-700); min-width:140px; }
    .tag-row .tag-desc { color:var(--gray-500); font-size:12px; flex:1; }
    .mt-filter-bar { display:flex; gap:8px; align-items:center; margin-bottom:12px; }
    </style>
</head>
<body>
<?php include $basePath . '/includes/sidebar.php'; ?>
<main class="main-content">
    <?php include $basePath . '/includes/header.php'; ?>
    <div class="page-content">

        <div class="card">
            <div class="card-header">
                <h2>Letter Templates</h2>
                <div class="filter-bar">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="templateSearch" placeholder="Search templates..." onkeyup="filterTable('templateSearch','templateTable',0)">
                    </div>
                    <button class="btn btn-primary" onclick="openNewTemplate()">+ New Template</button>
                </div>
            </div>
            <div class="card-body" style="padding:0">
                <div class="table-container">
                    <table id="templateTable">
                        <thead>
                            <tr><th>Template Name</th><th>Module</th><th>Status</th><th>Last Updated</th><th>Action</th></tr>
                        </thead>
                        <tbody id="templateTableBody"></tbody>
                    </table>
                </div>
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

        <!-- Add/Edit Merge Tag Modal -->
        <div class="modal-overlay" id="tagFormModal" onclick="if(event.target===this)closeModal('tagFormModal')" style="display:none">
            <div class="modal modal-sm">
                <div class="modal-header modal-header-navy">
                    <h3 id="tagFormTitle">Add Merge Tag</h3>
                    <button class="modal-close" onclick="closeModal('tagFormModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="tagForm">
                        <input type="hidden" id="tagFormId" value="0">
                        <div class="form-group">
                            <label>Module</label>
                            <select class="form-control" id="tagFormType">
                                <option value="Offer">Offer</option>
                                <option value="Confirmation">Confirmation</option>
                                <option value="Probation">Probation</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tag Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="tagFormTag" placeholder="e.g. CANDIDATE_NAME" style="font-family:monospace;text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label>Label <span class="required">*</span></label>
                            <input type="text" class="form-control" id="tagFormLabel" placeholder="e.g. Candidate Name">
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label>Description</label>
                            <input type="text" class="form-control" id="tagFormDesc" placeholder="e.g. Full name of the candidate">
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-light">
                    <button type="button" class="btn btn-outline" onclick="closeModal('tagFormModal')">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitTagForm()">Save Tag</button>
                </div>
            </div>
        </div>

        <!-- View Template Modal -->
        <div class="modal-overlay" id="viewTemplateModal" onclick="if(event.target===this)closeModal('viewTemplateModal')" style="display:none">
            <div class="modal modal-xl">
                <div class="modal-header modal-header-navy">
                    <h3 id="viewTemplateTitle">Template Preview</h3>
                    <button class="modal-close" onclick="closeModal('viewTemplateModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="viewTemplateContent" style="border:1px solid #e0e0e0;border-radius:8px;padding:24px;background:#fff;font-family:serif;line-height:1.8"></div>
                </div>
                <div class="modal-footer modal-footer-light">
                    <button type="button" class="btn" onclick="closeModal('viewTemplateModal')">Close</button>
                </div>
            </div>
        </div>

    </div>
</main>

<?php
$pageScripts = <<<'EOSCRIPT'
var quillEditor = null;
var mergeTagsCache = {};

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

function renderTemplates() {
    apiGet("ajax/templates_list.php", null, function(res) {
        var tbody = document.getElementById("templateTableBody");
        var data = res.success && res.data ? res.data : [];
        if (data.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"5\"><div class=\"empty-state\"><div class=\"empty-icon\">📄</div><p>No templates found. Click \"+ New Template\" to create one.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < data.length; i++) {
            var t = data[i];
            var statusBadge = t.status === "Active"
                ? "<span class=\"badge badge-completed\">Active</span>"
                : "<span class=\"badge badge-pending\">Inactive</span>";
            var updated = t.updated_at ? new Date(t.updated_at).toLocaleDateString("en-US", {year:"numeric",month:"short",day:"numeric"}) : "-";
            html += "<tr>" +
                "<td><strong>" + t.name + "</strong></td>" +
                "<td>" + t.type + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td>" + updated + "</td>" +
                "<td><div class=\"btn-group\">" +
                    "<button class=\"btn btn-sm btn-primary\" onclick=\"viewTemplate(" + t.id + ")\">View</button>" +
                    "<button class=\"btn btn-sm btn-warning\" onclick=\"editTemplate(" + t.id + ")\">Edit</button>" +
                    "<button class=\"btn btn-sm btn-danger\" onclick=\"deleteTemplate(" + t.id + ",'" + t.name.replace(/'/g,"\\'") + "')\">Delete</button>" +
                "</div></td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
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
        renderTemplates();
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
        renderTemplates();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save template", "error");
    });
}

function openAddTag() {
    document.getElementById("tagFormTitle").textContent = "Add Merge Tag";
    document.getElementById("tagFormId").value = 0;
    document.getElementById("tagFormTag").value = "";
    document.getElementById("tagFormLabel").value = "";
    document.getElementById("tagFormDesc").value = "";
    openModal("tagFormModal");
}

function openEditTag(id) {
    apiGet("ajax/merge_tags_list.php", null, function(res) {
        var tags = res.success && res.data ? res.data : [];
        var found = null;
        for (var i = 0; i < tags.length; i++) {
            if (tags[i].id === id) { found = tags[i]; break; }
        }
        if (!found) { showAlert("Tag not found", "error"); return; }
        document.getElementById("tagFormTitle").textContent = "Edit Merge Tag";
        document.getElementById("tagFormId").value = found.id;
        document.getElementById("tagFormType").value = found.type;
        document.getElementById("tagFormTag").value = found.tag;
        document.getElementById("tagFormLabel").value = found.label;
        document.getElementById("tagFormDesc").value = found.description || "";
        openModal("tagFormModal");
    });
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
        delete mergeTagsCache[type];
        loadMergeTagsList();
        onModuleChange();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save tag", "error");
    });
}

function deleteTag(id, tag) {
    if (!confirm("Delete merge tag \"" + tag + "\"? Templates using this tag will still show the placeholder.")) return;
    apiPost("ajax/merge_tags_delete.php", { id: id }, function(res) {
        showAlert(res.message || "Tag deleted", "success");
        var type = document.getElementById("mtTypeFilter").value;
        delete mergeTagsCache[type];
        loadMergeTagsList();
        onModuleChange();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to delete tag", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderTemplates();
    loadMergeTags("Offer", function(tags) {
        mergeTagsCache["Offer"] = tags;
    });
});
EOSCRIPT;
?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<?php include $basePath . '/includes/footer.php'; ?>
</body>
</html>