<?php
$basePath    = '..';
$pageTitle   = 'Generate Offer Letter';
$activePage  = 'offer_letter';
$breadcrumb  = 'Home / Recruitment / Offer Management / Offer Letter / Generate';

require_once __DIR__ . '/../includes/config.php';

$pdo = getDB();
$offerId = (int)($_GET['offer_id'] ?? 0);

$offer = null;
$existingContent = '';

if ($offerId > 0) {
    $stmt = $pdo->prepare("
        SELECT o.*,
               c.first_name, c.last_name, c.email,
               r.title AS job_title,
               d.name AS dept_name,
               des.name AS designation_name
        FROM offers o
        LEFT JOIN candidates c ON c.id = o.candidate_id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = o.dept_id
        LEFT JOIN designations des ON des.id = o.designation_id
        WHERE o.id = :id
    ");
    $stmt->execute([':id' => $offerId]);
    $offer = $stmt->fetch();

    if ($offer) {
        $lStmt = $pdo->prepare("SELECT letter_content FROM offer_letters WHERE offer_id = :oid ORDER BY id DESC LIMIT 1");
        $lStmt->execute([':oid' => $offerId]);
        $existing = $lStmt->fetch();
        if ($existing) $existingContent = $existing['letter_content'];
    }
}

$candidateName = $offer ? trim(($offer['first_name'] ?? '') . ' ' . ($offer['last_name'] ?? '')) : '';
$existingContentJson = json_encode($existingContent);

$pageStyles  = '
@import url("https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css");
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap}
.rp-top h1{font-size:20px;font-weight:700;color:var(--gray-900);margin:0;white-space:nowrap}
.rp-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.rp-card{background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);overflow:hidden;padding:24px}
.rp-empty{text-align:center;padding:48px 24px;color:var(--gray-400)}
.rp-empty-icon{font-size:40px;margin-bottom:8px}
.rp-empty h3{font-size:15px;color:var(--gray-600);margin:0 0 4px}
.rp-empty p{font-size:13px;margin:0}
.sb-section{margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--gray-100)}
.sb-section:last-of-type{border-bottom:none;margin-bottom:0;padding-bottom:0}
.sb-section-title{font-size:14px;font-weight:700;color:var(--gray-800);margin-bottom:16px;display:flex;align-items:center;gap:8px}
.sb-section-title .sb-icon{width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.sb-section-title .sb-icon.blue{background:#e8f0fe;color:var(--primary)}
.sb-section-title .sb-icon.purple{background:#f0e6ff;color:#7c3aed}
.vc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.vc-item{margin-bottom:8px}
.vc-item label{display:block;font-size:11px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px}
.vc-item span{font-size:14px;color:var(--gray-800);font-weight:500}
#quillEditor{height:400px}
.sb-btn-row{display:flex;justify-content:flex-end;gap:10px;padding-top:20px;border-top:1px solid var(--gray-200)}
@media(max-width:768px){
 .page-content{padding:16px}
 .rp-top{flex-direction:column;align-items:stretch;gap:12px}
 .rp-top h1{font-size:18px}
 .rp-card{padding:18px}
 .vc-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:480px){
 .page-content{padding:12px}
 .rp-card{padding:16px}
 .vc-grid{grid-template-columns:1fr}
 .sb-btn-row{flex-direction:column}
 .sb-btn-row .btn{width:100%;justify-content:center}
}
';

$pageScripts = <<<EOSCRIPT
var quill = null;
var existingContent = $existingContentJson;
var offerId = $offerId;

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
    if (typeof Quill === "undefined") {
        setTimeout(waitForQuill, 100);
        return;
    }
    initQuillInstance();
}

function loadTemplates() {
    apiGet("../templates/ajax/templates_list.php?type=Offer", null, function(res) {
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
    });
}

function applyTemplate() {
    var sel = document.getElementById("templateSelect");
    var option = sel.options[sel.selectedIndex];
    if (!option || !option.value || !quill) return;
    var content = option.dataset.content;
    if (!content) return;
    if (!offerId) {
        quill.setText("");
        quill.clipboard.dangerouslyPasteHTML(0, content);
        return;
    }
    apiPost("ajax/render_template.php", {
        offer_id: offerId,
        content: content
    }, function(res) {
        if (res.success && res.data && res.data.rendered_content) {
            quill.setText("");
            quill.clipboard.dangerouslyPasteHTML(0, res.data.rendered_content);
            showAlert("Template loaded: " + option.textContent, "success");
        } else {
            quill.setText("");
            quill.clipboard.dangerouslyPasteHTML(0, content);
            showAlert("Template loaded (tags unresolved)", "info");
        }
    }, function() {
        quill.setText("");
        quill.clipboard.dangerouslyPasteHTML(0, content);
        showAlert("Template loaded (tags unresolved)", "info");
    });
}

function saveLetter() {
    var letterContent = quill ? quill.root.innerHTML : "";
    if (!letterContent || letterContent === "<p><br></p>") { showAlert("Please write letter content", "error"); return; }
    apiPost("ajax/letter_generate.php", {
        offer_id: offerId,
        letter_content: letterContent
    }, function(res) {
        showAlert(res.message || "Letter generated!", "success");
        setTimeout(function() { window.location.href = "letter.php"; }, 600);
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
    <h1>Generate Offer Letter</h1>
    <div class="rp-actions">
        <a href="letter.php" class="btn btn-sm btn-outline">&larr; Back to List</a>
    </div>
</div>

<div class="rp-card">
    <?php if (!$offer): ?>
        <div class="rp-empty">
            <div class="rp-empty-icon">&#x1F4C4;</div>
            <h3>Offer not found</h3>
            <p><a href="letter.php">Go back to offer list</a>.</p>
        </div>
    <?php else: ?>
    <form autocomplete="off">
        <div class="sb-section">
            <div class="sb-section-title">
                <span class="sb-icon blue">&#x1F464;</span> Offer Details
            </div>
            <div class="vc-grid">
                <div class="vc-item"><label>Candidate</label><span><?= htmlspecialchars($candidateName) ?></span></div>
                <div class="vc-item"><label>Position</label><span><?= htmlspecialchars($offer['job_title'] ?? 'N/A') ?></span></div>
                <div class="vc-item"><label>Offered Salary</label><span><?= htmlspecialchars(number_format((float)($offer['salary'] ?? 0), 2)) ?> BDT</span></div>
                <div class="vc-item"><label>Department</label><span><?= htmlspecialchars($offer['dept_name'] ?? 'N/A') ?></span></div>
                <div class="vc-item"><label>Designation</label><span><?= htmlspecialchars($offer['designation_name'] ?? 'N/A') ?></span></div>
                <div class="vc-item"><label>Joining Date</label><span><?= $offer['joining_date'] ? htmlspecialchars((new DateTime($offer['joining_date']))->format('F j, Y')) : 'N/A' ?></span></div>
                <div class="vc-item"><label>Email</label><span><?= htmlspecialchars($offer['email'] ?? '-') ?></span></div>
                <div class="vc-item"><label>Probation</label><span><?= (int)($offer['probation_months'] ?? 0) ?> months</span></div>
            </div>
        </div>

        <div class="sb-section">
            <div class="sb-section-title">
                <span class="sb-icon purple">&#x1F4C3;</span> Template & Editor
            </div>
            <select class="form-control" id="templateSelect" onchange="applyTemplate()" style="margin-bottom:14px"></select>
            <div id="quillEditor"></div>
        </div>

        <div class="sb-btn-row">
            <a href="letter.php" class="btn btn-outline">Cancel</a>
            <button type="button" class="btn btn-primary" onclick="saveLetter()">Generate Letter</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
