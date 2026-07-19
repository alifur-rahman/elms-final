<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
$basePath = $basePath ?? '..';
$pageTitle = $pageTitle ?? 'Register Merge Tags';
$activePage = $activePage ?? 'template_tags';
$pageStyles = $pageStyles ?? '';
$pageScripts = $pageScripts ?? '';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - ERP HRIS</title>
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
    <style>
    .module-card { border:1px solid var(--gray-200); border-radius:8px; overflow:hidden; margin-bottom:24px; }
    .module-card-header { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; background:var(--gray-50); border-bottom:1px solid var(--gray-200); cursor:pointer; }
    .module-card-header h3 { font-size:14px; font-weight:600; margin:0; }
    .module-card-header .render-path { font-size:11px; color:var(--gray-500); font-family:monospace; background:#fff; padding:2px 10px; border-radius:4px; border:1px solid var(--gray-200); }
    .module-card-body { padding:16px 20px; }
    .tag-row { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid var(--gray-100); font-size:13px; }
    .tag-row:last-child { border-bottom:none; }
    .tag-row .tag-code { font-family:monospace; font-weight:600; color:var(--primary); min-width:150px; }
    .tag-row .tag-label { color:var(--gray-700); min-width:130px; }
    .tag-row .tag-desc { color:var(--gray-500); font-size:12px; flex:1; }

    .tag-row .actions { display:flex; gap:4px; }
    .tag-row .actions button { padding:2px 8px; font-size:11px; }
    </style>
</head>
<body>
<?php include $basePath . '/includes/sidebar.php'; ?>
<main class="main-content">
    <?php include $basePath . '/includes/header.php'; ?>
    <div class="page-content">

        <div class="card">
            <div class="card-header">
                <h2>Merge Tags by Module</h2>
                <button class="btn btn-primary" onclick="openAddTag()">+ Add Tag</button>
            </div>
            <div class="card-body" id="modulesContainer">
                <p>Loading modules...</p>
            </div>
        </div>

        <!-- Add/Edit Tag Modal -->
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
                            <label>Module <span class="required">*</span></label>
                            <select class="form-control" id="tagFormType"></select>
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

    </div>
</main>

<?php
$pageScripts = <<<'EOSCRIPT'
var modules = [];
var allTags = [];

function loadModules(callback) {
    apiGet("ajax/migrate_modules.php", null, function(res) {
        // After migration, load modules data
        apiGet("../includes/ajax/list_modules.php", null, function(res2) {
            modules = res2.success && res2.data ? res2.data : [];
            if (callback) callback(modules);
        });
    });
}

function loadAllTags(callback) {
    apiGet("ajax/merge_tags_list.php", null, function(res) {
        allTags = res.success && res.data ? res.data : [];
        if (callback) callback(allTags);
    });
}

function renderModules() {
    loadModules(function(mods) {
        loadAllRuntimeTags(function(tags) {
            var container = document.getElementById("modulesContainer");
            if (mods.length === 0) {
                container.innerHTML = '<div class="empty-state"><div class="empty-icon">📦</div><p>No modules configured.</p></div>';
                return;
            }
            var html = "";
            for (var i = 0; i < mods.length; i++) {
                var m = mods[i];
                var moduleTags = tags.filter(function(t) { return t.type === m.module_name; });
                html += '<div class="module-card">';
                html += '<div class="module-card-header" onclick="toggleModule(this)">';
                html += '<h3>' + m.label + ' <span style="font-weight:400;color:var(--gray-500);font-size:12px">(' + m.module_name + ')</span></h3>';
                html += '<div style="display:flex;align-items:center;gap:10px">';
                html += '<span class="render-path">' + (m.render_file || 'No render file') + '</span>';
                html += '<span style="font-size:12px;color:var(--gray-500)">' + moduleTags.length + ' tags</span>';
                html += '<button class="btn btn-sm btn-outline" onclick="event.stopPropagation();editModuleRender(' + m.id + ')">⚙ Render File</button>';
                html += '<span style="font-size:16px;">▾</span>';
                html += '</div></div>';
                html += '<div class="module-card-body">';
                if (moduleTags.length === 0) {
                    html += '<div style="text-align:center;padding:12px;color:var(--gray-400);font-size:13px">No tags registered for this module.</div>';
                } else {
                    for (var j = 0; j < moduleTags.length; j++) {
                        var t = moduleTags[j];
                        html += '<div class="tag-row">';
                        html += '<span class="tag-code">{{' + t.tag + '}}</span>';
                        html += '<span class="tag-label">' + t.label + '</span>';
                        html += '<span class="tag-desc">' + (t.description || "") + '</span>';
                        html += '<div class="actions">';
                        html += '<button class="btn btn-sm btn-warning" onclick="openEditTag(' + t.id + ')" style="padding:2px 8px;font-size:11px">Edit</button>';
                        html += '<button class="btn btn-sm btn-danger" onclick="deleteTag(' + t.id + ',\'' + t.tag + '\')" style="padding:2px 8px;font-size:11px">Del</button>';
                        html += '</div></div>';
                    }
                }
                html += '</div></div>';
            }
            container.innerHTML = html;
        });
    });
}

function loadAllRuntimeTags(callback) {
    apiGet("ajax/merge_tags_list.php", null, function(res) {
        allTags = res.success && res.data ? res.data : [];
        if (callback) callback(allTags);
    });
}

function toggleModule(header) {
    var body = header.nextElementSibling;
    body.style.display = body.style.display === "none" ? "block" : "none";
}

function editModuleRender(moduleId) {
    var mod = null;
    for (var i = 0; i < modules.length; i++) {
        if (modules[i].id === moduleId) { mod = modules[i]; break; }
    }
    if (!mod) return;
    var path = prompt("Render file path for \"" + mod.label + "\":", mod.render_file || "");
    if (path === null) return;
    if (path.trim() === "") { showAlert("Render file path is required", "error"); return; }
    apiPost("../includes/ajax/update_module_render.php", {
        id: moduleId,
        render_file: path.trim()
    }, function(res) {
        showAlert(res.message || "Updated!", "success");
        renderModules();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to update", "error");
    });
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
        sel.innerHTML += "<option value=\"" + modules[i].module_name + "\">" + modules[i].label + "</option>";
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
        sel.innerHTML += "<option value=\"" + modules[i].module_name + "\">" + modules[i].label + "</option>";
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
        renderModules();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save tag", "error");
    });
}

function deleteTag(id, tag) {
    if (!confirm('Delete merge tag "' + tag + '"?')) return;
    apiPost("ajax/merge_tags_delete.php", { id: id }, function(res) {
        showAlert(res.message || "Tag deleted", "success");
        renderModules();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to delete tag", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderModules();
});
EOSCRIPT;
?>
<?php include $basePath . '/includes/footer.php'; ?>
</body>
</html>