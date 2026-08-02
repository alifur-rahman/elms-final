<?php
// Public candidate response page - accessed via unique link
require_once __DIR__ . '/../includes/config.php';

$pdo = getDB();
$token = trim($_GET['token'] ?? '');

// Look up the token
$stmt = $pdo->prepare("
    SELECT ort.*, o.candidate_id, o.salary, o.joining_date, o.probation_months,
           o.response_status, o.response_deadline,
           c.first_name, c.last_name,
           des.name AS designation_name,
           d.name AS dept_name,
           r.title AS job_title
    FROM elms_offer_response_tokens ort
    JOIN elms_offers o ON o.id = ort.offer_id
    LEFT JOIN elms_candidates c ON c.id = o.candidate_id
    LEFT JOIN elms_designations des ON des.id = o.designation_id
    LEFT JOIN elms_departments d ON d.id = o.dept_id
    LEFT JOIN elms_requisitions r ON r.id = c.job_id
    WHERE ort.token = :token
");
$stmt->execute([':token' => $token]);
$rec = $stmt->fetch();

$state = 'invalid';   // invalid, expired, already_used, open
$msg = '';

if (!$rec) {
    $state = 'invalid';
    $msg = 'This response link is invalid or has expired.';
} else {
    $deadline = $rec['deadline_date'];
    if ($deadline && strtotime($deadline . ' 23:59:59') < time()) {
        $state = 'expired';
        $msg = 'The response deadline for this offer was ' . date('F j, Y', strtotime($deadline)) . '. Please contact the HR department for assistance.';
    } elseif ($rec['is_used']) {
        $state = 'already_used';
    } else {
        $state = 'open';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $state === 'open') {
    $decision = $_POST['decision'] ?? '';
    $notes = trim($_POST['notes'] ?? '');
    $token = $_POST['token'] ?? '';

    if ($decision === 'accept' || $decision === 'decline') {
        $status = $decision === 'accept' ? 'Accepted' : 'Rejected';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $pdo->beginTransaction();
        try {
            $updO = $pdo->prepare("UPDATE elms_offers SET response_status = :st, responded_at = NOW(), response_notes = :notes WHERE id = :id");
            $updO->execute([':st' => $status, ':notes' => $notes, ':id' => $rec['offer_id']]);

            $updT = $pdo->prepare("UPDATE elms_offer_response_tokens SET is_used = 1, used_at = NOW(), response_status = :st, response_notes = :notes, candidate_ip = :ip, candidate_user_agent = :ua WHERE id = :tid");
            $updT->execute([':st' => $status, ':notes' => $notes, ':ip' => $ip, ':ua' => $ua, ':tid' => $rec['id']]);

            if (function_exists('addCandidateLog')) {
                addCandidateLog($pdo, (int)$rec['candidate_id'],
                    $decision === 'accept' ? 'offer_accepted' : 'offer_rejected_candidate',
                    null, null,
                    ($decision === 'accept' ? 'Candidate accepted the offer' : 'Candidate rejected the offer') . ' via response link');
            }
            $pdo->commit();
            $state = $decision === 'accept' ? 'accepted' : 'declined';
        } catch (Exception $e) {
            $pdo->rollBack();
            $state = 'error';
            $msg = 'Sorry, there was a problem saving your response. Please contact the HR department.';
        }
    }
}

$candidateName = trim(($rec['first_name'] ?? '') . ' ' . ($rec['last_name'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Response</title>
    <link rel="stylesheet" href="<?= appBaseUrl() ?>/css/style.css">
    <style>
        body { background:#f1f3f4; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif; }
        .resp-wrap { max-width:560px; margin:48px auto; padding:0 16px; }
        .resp-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.08); overflow:hidden; }
        .resp-head { background:#1E3A8A; color:#fff; padding:24px 28px; }
        .resp-head h1 { margin:0; font-size:20px; font-weight:600; }
        .resp-head p { margin:6px 0 0; font-size:13px; opacity:.85; }
        .resp-body { padding:28px; }
        .resp-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f1f3f4; font-size:14px; }
        .resp-row:last-child { border-bottom:none; }
        .resp-row span:first-child { color:#5f6368; }
        .resp-row span:last-child { font-weight:600; color:#202124; text-align:right; }
        .resp-actions { display:flex; gap:12px; margin-top:24px; }
        .resp-actions .btn { flex:1; padding:12px; font-size:14px; }
        .resp-note { font-size:12px; color:#9aa0a6; margin-top:16px; text-align:center; }
        .resp-info { display:flex; align-items:flex-start; gap:12px; padding:16px; border-radius:10px; margin-bottom:16px; }
        .resp-info.info { background:#e8f0fe; color:#174ea6; }
        .resp-info.success { background:#e6f4ea; color:#1e8e3e; }
        .resp-info.warn { background:#fce8e6; color:#c5221f; }
        .resp-info .icon { font-size:22px; flex-shrink:0; }
        .resp-info h3 { margin:0 0 4px; font-size:15px; font-weight:600; }
        .resp-info p { margin:0; font-size:13px; line-height:1.5; }
        .resp-contact { background:#f8f9fa; border-radius:10px; padding:16px; margin-top:16px; font-size:13px; }
        .resp-contact .row { display:flex; gap:8px; align-items:center; padding:4px 0; }
        .resp-contact .row span:first-child { color:#5f6368; width:50px; }
        .resp-contact .row span:last-child { font-weight:500; color:#202124; }
        textarea.resp-notes { width:100%; box-sizing:border-box; border:1px solid #dadce0; border-radius:8px; padding:10px 12px; font-size:13px; margin-top:16px; font-family:inherit; resize:vertical; min-height:80px; }
        .hidden { display:none; }
        .badge { display:inline-block; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:600; }
    </style>
</head>
<body>
<div class="resp-wrap">
    <div class="resp-card">
        <div class="resp-head">
            <h1>&#x1F4E8; Offer Letter Response</h1>
            <p><?= APP_NAME ?> &middot; Employee Lifecycle Management</p>
        </div>
        <div class="resp-body">

        <?php if ($state === 'invalid'): ?>
            <div class="resp-info warn">
                <span class="icon">&#x26A0;&#xFE0F;</span>
                <div><h3>Invalid Link</h3><p><?= htmlspecialchars($msg) ?></p></div>
            </div>
            <div class="resp-contact">
                <div class="row"><span>&#x1F4E7;</span><span>hr@company.com</span></div>
                <div class="row"><span>&#x1F4DE;</span><span>+880-XXXX-XXXXXX</span></div>
            </div>

        <?php elseif ($state === 'expired'): ?>
            <div class="resp-info warn">
                <span class="icon">&#x23F3;</span>
                <div><h3>Response Deadline Passed</h3><p><?= htmlspecialchars($msg) ?></p></div>
            </div>
            <div class="resp-contact">
                <div class="row"><span>&#x1F4E7;</span><span>hr@company.com</span></div>
                <div class="row"><span>&#x1F4DE;</span><span>+880-XXXX-XXXXXX</span></div>
            </div>

        <?php elseif ($state === 'already_used'): ?>
            <div class="resp-info info">
                <span class="icon">&#x2705;</span>
                <div>
                    <h3>Response Already Submitted</h3>
                    <p>You have already submitted your response to this offer letter. If you experienced any issues during submission or need to make changes, please contact us at:</p>
                </div>
            </div>
            <div class="resp-contact">
                <div class="row"><span>&#x1F4E7;</span><span>hr@company.com</span></div>
                <div class="row"><span>&#x1F4DE;</span><span>+880-XXXX-XXXXXX</span></div>
                <div style="margin-top:8px;font-size:12px;color:#5f6368">Your response was recorded on <?= $rec['used_at'] ? date('F j, Y', strtotime($rec['used_at'])) : 'record' ?>.</div>
            </div>

        <?php elseif ($state === 'accepted' || $state === 'declined'): ?>
            <div class="resp-info success">
                <span class="icon"><?= $state === 'accepted' ? '&#x1F389;' : '&#x1F44B;' ?></span>
                <div>
                    <h3><?= $state === 'accepted' ? 'Thank You! Offer Accepted' : 'Thank You for Letting Us Know' ?></h3>
                    <p><?= $state === 'accepted'
                        ? 'Congratulations! You have accepted the offer. Our HR team will contact you shortly with the next steps.'
                        : 'We appreciate your response. We wish you the best in your future endeavors.' ?></p>
                </div>
            </div>
            <div class="resp-contact">
                <div class="row"><span>&#x1F4E7;</span><span>hr@company.com</span></div>
                <div class="row"><span>&#x1F4DE;</span><span>+880-XXXX-XXXXXX</span></div>
            </div>

        <?php elseif ($state === 'error'): ?>
            <div class="resp-info warn">
                <span class="icon">&#x274C;</span>
                <div><h3>Submission Error</h3><p><?= htmlspecialchars($msg) ?></p></div>
            </div>
            <div class="resp-contact">
                <div class="row"><span>&#x1F4E7;</span><span>hr@company.com</span></div>
                <div class="row"><span>&#x1F4DE;</span><span>+880-XXXX-XXXXXX</span></div>
            </div>

        <?php elseif ($state === 'open'): ?>
            <div style="text-align:center;margin-bottom:20px">
                <div style="font-size:40px">&#x1F389;</div>
                <h2 style="margin:8px 0 4px;font-size:18px">You've Received an Offer!</h2>
                <p style="margin:0;font-size:13px;color:#5f6368"><?= htmlspecialchars($candidateName) ?>, please review your offer details below.</p>
            </div>

            <div>
                <div class="resp-row"><span>Position</span><span><?= htmlspecialchars($rec['designation_name'] ?? 'N/A') ?></span></div>
                <div class="resp-row"><span>Department</span><span><?= htmlspecialchars($rec['dept_name'] ?? 'N/A') ?></span></div>
                <div class="resp-row"><span>Job Title</span><span><?= htmlspecialchars($rec['job_title'] ?? 'N/A') ?></span></div>
                <div class="resp-row"><span>Salary</span><span>&#x9F3; <?= number_format((float)$rec['salary'], 2) ?> BDT</span></div>
                <div class="resp-row"><span>Joining Date</span><span><?= $rec['joining_date'] ? date('F j, Y', strtotime($rec['joining_date'])) : 'N/A' ?></span></div>
                <div class="resp-row"><span>Probation</span><span><?= (int)$rec['probation_months'] ?> months</span></div>
                <div class="resp-row"><span>Response Deadline</span><span><span class="badge" style="background:#fef7e0;color:#e37400"><?= date('M j, Y', strtotime($rec['deadline_date'])) ?></span></span></div>
            </div>

            <form method="POST" action="respond.php" onsubmit="return confirmSubmit()">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <textarea name="notes" class="resp-notes" placeholder="Optional: Add a note with your response (e.g. questions about the offer, preferred joining date, etc.)"></textarea>
                <div class="resp-actions">
                    <button type="submit" name="decision" value="decline" class="btn" style="border:1px solid #dadce0;color:#d93025;background:#fff">&#x274C; Decline Offer</button>
                    <button type="submit" name="decision" value="accept" class="btn btn-primary">&#x2705; Accept Offer</button>
                </div>
                <div class="resp-note">After submitting, you will not be able to change your response using this link.</div>
            </form>
        <?php endif; ?>

        </div>
    </div>
</div>

<script>
function confirmSubmit() {
    var btn = document.activeElement;
    if (btn && btn.value === 'accept') {
        return confirm('Are you sure you want to ACCEPT this offer?');
    }
    if (btn && btn.value === 'decline') {
        return confirm('Are you sure you want to DECLINE this offer?');
    }
    return true;
}
</script>
</body>
</html>
