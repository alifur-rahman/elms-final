<?php
$basePath    = '..';
$pageTitle   = 'Generate Confirmation Letter';
$activePage  = 'confirmation_list';
$breadcrumb  = 'Home / Confirmation / Generate Letter';

require_once __DIR__ . '/../includes/config.php';

$pdo = getDB();
$employeeId = (int)($_GET['employee_id'] ?? 0);
$appraisalId = (int)($_GET['appraisal_id'] ?? 0);

$emp = null;
$existingContent = '';
$existingType = 'Probationer';

if ($employeeId > 0) {
    $stmt = $pdo->prepare("
        SELECT pbi.*,
               d.name AS dept_name,
               des.name AS designation_name,
               o.id AS offer_id, o.salary, o.joining_date, o.probation_months
        FROM personnel_basic_info pbi
        LEFT JOIN departments d ON d.id = pbi.dept_id
        LEFT JOIN designations des ON des.id = pbi.DESG_ID
        LEFT JOIN offers o ON o.id = pbi.offer_id
        WHERE pbi.PBI_ID = :id
    ");
    $stmt->execute([':id' => $employeeId]);
    $emp = $stmt->fetch();

    if ($emp) {
        $lStmt = $pdo->prepare("SELECT letter_content, employment_type FROM confirmation_letters WHERE employee_id = :eid ORDER BY id DESC LIMIT 1");
        $lStmt->execute([':eid' => $employeeId]);
        $existing = $lStmt->fetch();
        if ($existing) {
            $existingContent = $existing['letter_content'];
            $existingType = $existing['employment_type'] ?: 'Probationer';
        }
    }
}

$empName = $emp ? trim(($emp['PBI_NAME'] ?? '') . ' ' . ($emp['last_name'] ?? '')) : '';
$existingContentJson = json_encode($existingContent);

$pageCss = 'css/page_css/confirmation-generate.css';

$pageScripts = <<<EOSCRIPT
var quill = null;
var existingContent = $existingContentJson;
var employeeId = $employeeId;
var appraisalId = $appraisalId;

function initQuillInstance() {
    var editorEl = document.getElementById("quillEditor");
    if (!editorEl) return;
    quill = new Quill("#quillEditor", {
        theme: "snow",
        placeholder: "Select a template above or start composing...",
        modules: {
            toolbar: [
                ["bold","italic","underline","strike"],
                [{list:"ordered"},{list:"bullet"}],
                ["clean"]
            ]
        }
    });
    if (existingContent) {
        quill.clipboard.dangerouslyPasteHTML(0, existingContent);
    }
}

function waitForQuill() {
    if (typeof Quill === "undefined") { setTimeout(waitForQuill, 100); return; }
    initQuillInstance();
}

function loadTemplates() {
    apiGet("../templates/ajax/templates_list.php?type=Confirmation", null, function(res) {
        var sel = document.getElementById("templateSelect");
        sel.innerHTML = "<option value=\"\">-- Select Template --</option>";
        if (res.success && res.data) {
            for (var i = 0; i < res.data.length; i++) {
                var t = res.data[i];
                var opt = document.createElement("option");
                opt.value = t.id;
                opt.textContent = t.name;
                opt.dataset.content = t.content;
                sel.appendChild(opt);
            }
        }
        if (sel.options.length <= 1) {
            sel.innerHTML += "<option value=\"_default\" data-content=\"\">Default Confirmation Template</option>";
        }
    });
}

function applyTemplate() {
    var sel = document.getElementById("templateSelect");
    var option = sel.options[sel.selectedIndex];
    if (!option || !option.value || !quill) return;
    var content = option.dataset.content;
    if (!content && option.value !== "_default") return;
    if (!employeeId) {
        quill.setText("");
        if (content) quill.clipboard.dangerouslyPasteHTML(0, content);
        return;
    }
    apiPost("ajax/render_template.php", {
        employee_id: employeeId,
        content: content
    }, function(res) {
        if (res.success && res.data && res.data.rendered_content) {
            quill.setText("");
            quill.clipboard.dangerouslyPasteHTML(0, res.data.rendered_content);
            showAlert("Template loaded: " + option.textContent, "success");
        } else {
            quill.setText("");
            if (content) quill.clipboard.dangerouslyPasteHTML(0, content);
            showAlert("Template loaded (tags unresolved)", "info");
        }
    }, function() {
        quill.setText("");
        if (content) quill.clipboard.dangerouslyPasteHTML(0, content);
        showAlert("Template loaded (tags unresolved)", "info");
    });
}

function saveLetter() {
    var letterContent = quill ? quill.root.innerHTML : "";
    if (!letterContent || letterContent === "<p><br></p>") { showAlert("Please write letter content", "error"); return; }
    var empType = document.getElementById("employmentType").value;
    apiPost("ajax/letter_generate.php", {
        employee_id: employeeId,
        appraisal_id: appraisalId,
        letter_content: letterContent,
        employment_type: empType
    }, function(res) {
        showAlert(res.message || "Letter generated!", "success");
        setTimeout(function() { window.location.href = "confirmation_letters.php"; }, 600);
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to generate letter", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    waitForQuill();
    loadTemplates();
});
EOSCRIPT;

ob_start();
?>
<div class="rp-top">
    <h1>Generate Confirmation Letter</h1>
    <a href="list.php" class="rp-back">&larr; Back to List</a>
</div>

<?php if (!$emp): ?>
<div class="rp-card">
    <div class="rp-empty">
        <div class="rp-empty-icon">&#128100;</div>
        <h3>Employee not found</h3>
        <p><a href="list.php" style="color:var(--primary)">Go back to list</a></p>
    </div>
</div>
<?php else: ?>
<div class="rp-card">
    <div class="sb-section">
        <div class="sb-section-title">
            <span class="sb-icon blue">&#128100;</span> Employee Details
        </div>
        <div class="vc-grid">
            <div class="vc-item">
                <label>Employee Name</label>
                <span><?= htmlspecialchars($empName) ?></span>
            </div>
            <div class="vc-item">
                <label>Employee Code</label>
                <span><?= htmlspecialchars($emp['PBI_CODE'] ?? 'N/A') ?></span>
            </div>
            <div class="vc-item">
                <label>Job Title</label>
                <span><?= htmlspecialchars($emp['job_title'] ?? 'N/A') ?></span>
            </div>
            <div class="vc-item">
                <label>Department</label>
                <span><?= htmlspecialchars($emp['dept_name'] ?? 'N/A') ?></span>
            </div>
            <div class="vc-item">
                <label>Designation</label>
                <span><?= htmlspecialchars($emp['designation_name'] ?? 'N/A') ?></span>
            </div>
            <div class="vc-item">
                <label>Salary</label>
                <span><?= number_format((float)($emp['salary'] ?? 0), 2) ?> BDT</span>
            </div>
            <div class="vc-item">
                <label>Current Type</label>
                <span><?= htmlspecialchars($emp['EMPLOYMENT_TYPE'] ?? 'Probationer') ?></span>
            </div>
            <div class="vc-item">
                <label>Probation Period</label>
                <span><?= (int)($emp['probation_months'] ?? 0) ?> months</span>
            </div>
        </div>
    </div>

    <div class="sb-section">
        <div class="sb-section-title">
            <span class="sb-icon green">&#128196;</span> Employment Type
        </div>
        <div style="max-width:300px">
            <div class="form-group">
                <label>Employee Type After Confirmation</label>
                <select class="form-control" id="employmentType">
                    <?php
                    $types = ['Casual','Apprentice','Probationer','Temporary','Replace','Permanent','Seasonal','Contractual','Regular'];
                    foreach ($types as $t):
                        $sel = ($t === $existingType) ? 'selected' : '';
                        $defaultSel = ($t === 'Permanent' && $existingType === 'Probationer') ? 'selected' : '';
                    ?>
                    <option value="<?= $t ?>" <?= $t === 'Permanent' && $existingType === 'Probationer' ? 'selected' : $sel ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="sb-section">
        <div class="sb-section-title">
            <span class="sb-icon purple">&#128203;</span> Letter Template
        </div>
        <div class="template-row">
            <div class="form-group">
                <label>Choose Template</label>
                <select class="form-control" id="templateSelect" onchange="applyTemplate()"></select>
            </div>
        </div>
        <div id="quillEditor"></div>
    </div>

    <div class="bottom-actions">
        <a href="list.php" class="rp-back">&larr; Back to List</a>
        <button type="button" class="btn btn-primary" onclick="saveLetter()">Generate Letter</button>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
