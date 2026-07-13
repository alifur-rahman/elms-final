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
                    <button class="btn btn-primary" onclick="openModal('templateFormModal')">+ New Template</button>
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
            <div class="modal modal-large">
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
                                <select class="form-control" id="templateFormType">
                                    <option value="Offer">Offer</option>
                                    <option value="Confirmation">Confirmation</option>
                                    <option value="Probation">Probation</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Content <span class="required">*</span></label>
                            <textarea class="form-control" id="templateFormContent" rows="16" style="font-family:monospace;font-size:13px" placeholder="Enter letter HTML with merge tags..."></textarea>
                        </div>
                        <div style="font-size:12px;color:#888;margin-bottom:16px;padding:8px 12px;background:#f8f9fa;border-radius:6px">
                            <strong>Merge Tags:</strong>
                            {{CANDIDATE_NAME}}, {{DESIGNATION}}, {{DEPARTMENT}}, {{SALARY}}, {{JOINING_DATE}}, {{PROBATION_MONTHS}}, {{COMPANY_NAME}}, {{HR_NAME}}, {{DATE}}, {{RESPONSE_DATE}}, {{JOB_TITLE}}
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
            <div class="modal modal-large">
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

function editTemplate(id) {
    apiGet("ajax/templates_get.php?id=" + id, null, function(res) {
        if (res.success && res.data) {
            document.getElementById("templateFormTitle").textContent = "Edit Template";
            document.getElementById("templateFormId").value = res.data.id;
            document.getElementById("templateFormName").value = res.data.name;
            document.getElementById("templateFormType").value = res.data.type;
            document.getElementById("templateFormContent").value = res.data.content;
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
    var content = document.getElementById("templateFormContent").value.trim();
    if (!name) { showAlert("Please enter a template name", "error"); return; }
    if (!content) { showAlert("Please enter template content", "error"); return; }

    var endpoint = id > 0 ? "ajax/templates_update.php" : "ajax/templates_create.php";
    apiPost(endpoint, { id: id, name: name, type: type, content: content }, function(res) {
        showAlert(res.message || "Template saved!", "success");
        closeModal("templateFormModal");
        renderTemplates();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to save template", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderTemplates();
});
EOSCRIPT;
include $basePath . '/includes/footer.php'; ?>
</body>
</html>
