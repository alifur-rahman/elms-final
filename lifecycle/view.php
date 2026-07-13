<?php
$basePath    = '..';
$pageTitle   = 'Lifecycle Timeline';
$activePage  = 'lifecycle';
$breadcrumb  = 'Home / Lifecycle / Timeline';
$pageStyles  = '
.selector-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
.selector-bar label { font-weight: 500; font-size: 13px; color: var(--gray-700); flex-shrink: 0; }
.selector-bar select { min-width: 360px; }
.lifecycle-timeline { position: relative; padding-left: 40px; margin: 32px 0; }
.lifecycle-timeline::before { content: ""; position: absolute; left: 20px; top: 0; bottom: 0; width: 3px; background: var(--gray-200); border-radius: 2px; }
.lt-event { position: relative; padding-bottom: 32px; }
.lt-event:last-child { padding-bottom: 0; }
.lt-event .lt-icon { position: absolute; left: -32px; top: 0; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 3px solid var(--gray-300); background: #fff; z-index: 1; }
.lt-event.all .lt-icon { background: var(--primary); border-color: var(--primary); color: #fff; }
.lt-event .lt-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.08); padding: 14px 18px; margin-left: 16px; border-left: 3px solid var(--primary); }
.lt-event .lt-date { font-size: 11px; color: var(--gray-500); }
.lt-event .lt-label { font-size: 14px; font-weight: 600; color: var(--gray-800); margin-top: 2px; text-transform: capitalize; }
.lt-event .lt-desc { font-size: 12px; color: var(--gray-600); margin-top: 4px; }
.lt-event .lt-status { margin-top: 8px; }
.summary-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); padding: 24px; display: grid; grid-template-columns: auto 1fr; gap: 20px 32px; align-items: start; }
.summary-card .sc-avatar { width: 72px; height: 72px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; grid-row: span 2; flex-shrink: 0; }
.summary-card .sc-details { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
.summary-card .sc-item label { display: block; font-size: 11px; color: var(--gray-500); text-transform: uppercase; letter-spacing: .5px; font-weight: 600; }
.summary-card .sc-item span { font-size: 14px; font-weight: 500; color: var(--gray-900); }
.no-data { text-align: center; padding: 48px 24px; color: var(--gray-500); }
.no-data .nd-icon { font-size: 48px; margin-bottom: 12px; }
.no-data h3 { font-size: 16px; color: var(--gray-700); margin-bottom: 4px; }
.action-icon { margin-right: 6px; font-size: 14px; }
@media (max-width:768px){ .selector-bar select { min-width: 0; width: 100%; } .lifecycle-timeline { padding-left: 32px; } .lt-event .lt-icon { width: 34px; height: 34px; left: -28px; font-size: 13px; } .lt-event .lt-card { padding: 12px 14px; margin-left: 12px; } .lt-event .lt-label { font-size: 13px; } .summary-card { grid-template-columns: 1fr; text-align: center; } .summary-card .sc-avatar { margin: 0 auto; } .summary-card .sc-details { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); } }
@media (max-width:480px){ .lt-event { padding-bottom: 24px; } .lt-event .lt-card { padding: 10px 12px; } .summary-card { padding: 16px; } .summary-card .sc-details { grid-template-columns: 1fr 1fr; gap: 8px; } .no-data { padding: 32px 16px; } .no-data .nd-icon { font-size: 36px; } }
';
$pageScripts = '
var candidatesData = [];

window.renderTimeline = function(candId) {
    var container = document.getElementById("timelineContainer");
    if (!candId) {
        container.innerHTML = "<div class=\"no-data\"><div class=\"nd-icon\">&#x1F50D;</div><h3>Select a Candidate</h3><p>Choose a candidate from the dropdown above to view their lifecycle timeline</p></div>";
        return;
    }
    candId = parseInt(candId);
    var cand = null;
    for (var i = 0; i < candidatesData.length; i++) { if (candidatesData[i].id === candId) { cand = candidatesData[i]; break; } }
    if (!cand) {
        container.innerHTML = "<div class=\"no-data\"><div class=\"nd-icon\">&#x26A0;&#xFE0F;</div><h3>Candidate Not Found</h3></div>";
        return;
    }

    apiGet("ajax/logs.php?candidate_id=" + candId, null, function(res) {
        var logs = res.success && res.data ? res.data : [];

        // Status event icons
        var icons = {
            candidate_created: "\uD83D\uDC64", candidate_updated: "\u270F\uFE0F", candidate_deleted: "\uD83D\uDDD1\uFE0F",
            screening_shortlist: "\uD83D\uDD0D", screening_reject: "\u2715", screening_hold: "\u23F3",
            screening_shortlist_update: "\uD83D\uDD0D", screening_reject_update: "\u2715", screening_hold_update: "\u23F3",
            interview_scheduled: "\uD83E\uDD1D", interview_pass: "\u2705", interview_fail: "\u274C",
            assessment_completed: "\uD83D\uDCDD", offer_created: "\uD83D\uDCC4"
        };
        var labels = {
            candidate_created: "Applied", candidate_updated: "Profile Updated", candidate_deleted: "Deleted",
            screening_shortlist: "Screening Shortlisted", screening_reject: "Screening Rejected", screening_hold: "Screening Hold",
            screening_shortlist_update: "Screening Updated (Shortlisted)",
            screening_reject_update: "Screening Updated (Rejected)",
            screening_hold_update: "Screening Updated (Hold)",
            interview_scheduled: "Interview Scheduled", interview_pass: "Interview Passed", interview_fail: "Interview Failed",
            assessment_completed: "Assessment Completed", offer_created: "Offer Created"
        };

        var html = "<div class=\"lifecycle-timeline\">";

        if (logs.length === 0) {
            html += "<p style=\"color:var(--gray-500);padding:24px;text-align:center\">No lifecycle logs found for this candidate.</p>";
        } else {
            for (var i = 0; i < logs.length; i++) {
                var log = logs[i];
                var icon = icons[log.action] || "\u2022";
                var label = labels[log.action] || log.action.replace(/_/g, " ");
                var fromStr = log.from_status ? log.from_status + " \u2192 " : "";
                var toStr = log.to_status || "";
                var desc = fromStr + toStr;
                if (log.notes) desc += (desc ? " | " : "") + log.notes;

                html += "<div class=\"lt-event all\">";
                html += "<div class=\"lt-icon\">" + icon + "</div>";
                html += "<div class=\"lt-card\">";
                html += "<div class=\"lt-date\">" + formatDateTime(log.created_at) + "</div>";
                html += "<div class=\"lt-label\"><span class=\"action-icon\">" + icon + "</span>" + label + "</div>";
                html += "<div class=\"lt-desc\">" + desc + "</div>";
                html += "<div class=\"lt-status\"><span class=\"badge badge-completed\">\u2713 " + (toStr || "Done") + "</span></div>";
                html += "</div></div>";
            }
        }
        html += "</div>";

        // Summary card
        var name = cand.first_name + " " + cand.last_name;
        var initials = cand.first_name.charAt(0) + cand.last_name.charAt(0);
        var statusBadge = "badge-" + (cand.status_name ? cand.status_name.toLowerCase() : "pending");
        html += "<div class=\"summary-card mt-24\">";
        html += "<div class=\"sc-avatar\">" + initials + "</div>";
        html += "<div class=\"sc-details\">";
        html += "<div class=\"sc-item\"><label>Candidate</label><span>" + name + "</span></div>";
        html += "<div class=\"sc-item\"><label>Code</label><span>" + (cand.code || "-") + "</span></div>";
        html += "<div class=\"sc-item\"><label>Job</label><span>" + (cand.job_title || "N/A") + "</span></div>";
        html += "<div class=\"sc-item\"><label>Status</label><span><span class=\"badge " + getBadgeClass(cand.status_name) + "\">" + (cand.status_name || "Unknown") + "</span></span></div>";
        html += "<div class=\"sc-item\"><label>Applied At</label><span>" + formatDateTime(cand.applied_at) + "</span></div>";
        html += "</div></div>";

        container.innerHTML = html;
    }, function() {
        container.innerHTML = "<div class=\"no-data\"><div class=\"nd-icon\">&#x26A0;&#xFE0F;</div><h3>Failed to Load Logs</h3></div>";
    });
};

function loadCandidateSelect() {
    var sel = document.getElementById("candSelect");
    sel.innerHTML = "<option value=\"\">-- Select Candidate --</option>";
    apiGet("ajax/candidates.php", null, function(res) {
        if (res.success && res.data) {
            candidatesData = res.data;
            candidatesData.forEach(function(c) {
                var opt = document.createElement("option");
                opt.value = c.id;
                opt.textContent = c.first_name + " " + c.last_name + " (" + (c.code || "N/A") + ") - " + (c.job_title || "") + " [" + (c.status_name || "") + "]";
                sel.appendChild(opt);
            });
        }
    }, function() {});
}

loadCandidateSelect();
';

ob_start();
?>
<div class="selector-bar card" style="padding:16px 24px;margin-bottom:24px">
    <label for="candSelect">Select Candidate:</label>
    <select class="form-control" id="candSelect" onchange="window.renderTimeline(this.value)" style="max-width:500px">
        <option value="">-- Select Candidate --</option>
    </select>
</div>

<div id="timelineContainer">
    <div class="no-data">
        <div class="nd-icon">&#x1F50D;</div>
        <h3>Select a Candidate</h3>
        <p>Choose a candidate from the dropdown above to view their lifecycle timeline</p>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
