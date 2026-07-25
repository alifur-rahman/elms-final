<?php
$basePath    = '..';
$pageTitle   = 'Lifecycle Timeline';
$activePage  = 'lifecycle';
$breadcrumb  = 'Home / Lifecycle / Timeline';
$pageStyles  = '
.selector-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
.selector-bar label { font-weight: 500; font-size: 13px; color: var(--gray-700); flex-shrink: 0; }
.search-wrap { position: relative; width: 100%; max-width: 500px; }
.search-wrap input { width: 100%; padding: 10px 14px 10px 36px; border: 1px solid var(--gray-300); border-radius: 8px; font-size: 14px; background: #fff; outline: none; transition: border-color .2s; }
.search-wrap input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.search-wrap .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: 14px; pointer-events: none; }
.search-wrap .search-clear { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 16px; color: var(--gray-400); cursor: pointer; display: none; padding: 2px 4px; line-height: 1; }
.search-wrap .search-clear:hover { color: var(--gray-700); }
.search-results { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid var(--gray-200); border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.12); max-height: 320px; overflow-y: auto; z-index: 50; display: none; margin-top: 4px; }
.search-results.open { display: block; }
.search-results .sr-item { padding: 10px 14px; cursor: pointer; border-bottom: 1px solid var(--gray-100); transition: background .15s; }
.search-results .sr-item:last-child { border-bottom: none; }
.search-results .sr-item:hover, .search-results .sr-item.active { background: var(--gray-50); }
.search-results .sr-item .sr-name { font-size: 14px; font-weight: 600; color: var(--gray-800); }
.search-results .sr-item .sr-meta { font-size: 12px; color: var(--gray-500); margin-top: 2px; }
.search-results .sr-item .sr-badge { display: inline-block; font-size: 11px; padding: 1px 6px; border-radius: 4px; margin-left: 6px; }
.search-results .sr-empty { padding: 16px; text-align: center; color: var(--gray-500); font-size: 13px; }
.search-results .sr-count { padding: 6px 14px; font-size: 11px; color: var(--gray-400); border-bottom: 1px solid var(--gray-100); background: var(--gray-50); position: sticky; top: 0; }
.selected-display { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 8px; font-size: 13px; color: var(--gray-700); }
.selected-display strong { color: var(--gray-900); }
.selected-display .sd-clear { background: none; border: none; color: var(--gray-400); cursor: pointer; font-size: 14px; padding: 0 2px; }
.selected-display .sd-clear:hover { color: #dc2626; }
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
.loading-msg { padding: 24px; text-align: center; color: var(--gray-400); font-size: 13px; }
@media (max-width:768px){ .selector-bar { flex-direction: column; align-items: stretch; } .selector-bar label { margin-bottom: 4px; } .search-wrap { max-width: 100%; } .lifecycle-timeline { padding-left: 32px; } .lt-event .lt-icon { width: 34px; height: 34px; left: -28px; font-size: 13px; } .lt-event .lt-card { padding: 12px 14px; margin-left: 12px; } .lt-event .lt-label { font-size: 13px; } .summary-card { grid-template-columns: 1fr; text-align: center; } .summary-card .sc-avatar { margin: 0 auto; } .summary-card .sc-details { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); } }
@media (max-width:480px){ .lt-event { padding-bottom: 24px; } .lt-event .lt-card { padding: 10px 12px; } .summary-card { padding: 16px; } .summary-card .sc-details { grid-template-columns: 1fr 1fr; gap: 8px; } .no-data { padding: 32px 16px; } .no-data .nd-icon { font-size: 36px; } .search-results { max-height: 260px; } }
';
$pageScripts = '
var candidatesData = [];
var selectedCand = null;
var activeIndex = -1;
var filteredResults = [];

function getBadgeClass(status) {
    if (!status) return "badge-pending";
    var s = status.toLowerCase();
    if (s === "hired" || s === "selected" || s === "shortlisted") return "badge-completed";
    if (s === "rejected") return "badge-rejected";
    if (s === "offered") return "badge-approved";
    return "badge-pending";
}

function formatDateTime(dt) {
    if (!dt) return "-";
    var d = new Date(dt);
    if (isNaN(d)) return dt;
    var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    return d.getDate() + " " + months[d.getMonth()] + " " + d.getFullYear() + ", " +
           ("0"+d.getHours()).slice(-2) + ":" + ("0"+d.getMinutes()).slice(-2);
}

function filterCandidates(query) {
    if (!query || query.length < 1) return candidatesData;
    var q = query.toLowerCase();
    return candidatesData.filter(function(c) {
        return (c.first_name + " " + c.last_name).toLowerCase().indexOf(q) !== -1 ||
               (c.code || "").toLowerCase().indexOf(q) !== -1 ||
               (c.job_title || "").toLowerCase().indexOf(q) !== -1 ||
               (c.status_name || "").toLowerCase().indexOf(q) !== -1;
    });
}

function renderDropdown(results) {
    var box = document.getElementById("searchResults");
    filteredResults = results;
    activeIndex = -1;

    if (results.length === 0) {
        box.innerHTML = "<div class=\"sr-empty\">No candidates found</div>";
        box.classList.add("open");
        return;
    }

    var html = "<div class=\"sr-count\">" + results.length + " candidate" + (results.length !== 1 ? "s" : "") + " found</div>";
    for (var i = 0; i < results.length; i++) {
        var c = results[i];
        var name = c.first_name + " " + c.last_name;
        var badgeCls = getBadgeClass(c.status_name);
        html += "<div class=\"sr-item\" data-id=\"" + c.id + "\" data-idx=\"" + i + "\">";
        html += "<div class=\"sr-name\">" + name + " <span style=\"font-weight:400;color:var(--gray-400)\">(" + (c.code || "N/A") + ")</span>";
        html += " <span class=\"sr-badge " + badgeCls + "\">" + (c.status_name || "") + "</span></div>";
        html += "<div class=\"sr-meta\">" + (c.job_title || "N/A") + " &middot; Applied " + formatDateTime(c.applied_at) + "</div>";
        html += "</div>";
    }
    box.innerHTML = html;
    box.classList.add("open");

    var items = box.querySelectorAll(".sr-item");
    for (var j = 0; j < items.length; j++) {
        items[j].addEventListener("mousedown", function(e) {
            e.preventDefault();
            var id = parseInt(this.getAttribute("data-id"));
            selectCandidate(id);
        });
    }
}

function selectCandidate(id) {
    var cand = null;
    for (var i = 0; i < candidatesData.length; i++) {
        if (candidatesData[i].id === id) { cand = candidatesData[i]; break; }
    }
    if (!cand) return;

    selectedCand = cand;
    var name = cand.first_name + " " + cand.last_name;
    var input = document.getElementById("candSearch");
    var clearBtn = document.getElementById("searchClear");
    var box = document.getElementById("searchResults");

    input.value = name + " (" + (cand.code || "N/A") + ")";
    clearBtn.style.display = "inline";
    box.classList.remove("open");

    document.getElementById("selectedInfo").innerHTML =
        "<div class=\"selected-display\">" +
        "<strong>" + name + "</strong> &mdash; " + (cand.job_title || "N/A") +
        " <span class=\"badge " + getBadgeClass(cand.status_name) + "\">" + (cand.status_name || "") + "</span>" +
        " <button class=\"sd-clear\" onclick=\"clearSearch()\" title=\"Clear\">&times;</button>" +
        "</div>";

    window.renderTimeline(id);
}

function clearSearch() {
    selectedCand = null;
    document.getElementById("candSearch").value = "";
    document.getElementById("searchClear").style.display = "none";
    document.getElementById("selectedInfo").innerHTML = "";
    document.getElementById("timelineContainer").innerHTML =
        "<div class=\"no-data\"><div class=\"nd-icon\">&#x1F50D;</div><h3>Select a Candidate</h3><p>Type a name, code, or job title to search</p></div>";
}

window.renderTimeline = function(candId) {
    var container = document.getElementById("timelineContainer");
    if (!candId) {
        container.innerHTML = "<div class=\"no-data\"><div class=\"nd-icon\">&#x1F50D;</div><h3>Select a Candidate</h3><p>Type a name, code, or job title to search</p></div>";
        return;
    }
    candId = parseInt(candId);
    var cand = null;
    for (var i = 0; i < candidatesData.length; i++) { if (candidatesData[i].id === candId) { cand = candidatesData[i]; break; } }
    if (!cand) {
        container.innerHTML = "<div class=\"no-data\"><div class=\"nd-icon\">&#x26A0;&#xFE0F;</div><h3>Candidate Not Found</h3></div>";
        return;
    }

    container.innerHTML = "<div class=\"loading-msg\">Loading timeline...</div>";

    apiGet("ajax/logs.php?candidate_id=" + candId, null, function(res) {
        var logs = res.success && res.data ? res.data : [];
        var icons = {
            candidate_created: "\uD83D\uDC64", candidate_updated: "\u270F\uFE0F", candidate_deleted: "\uD83D\uDDD1\uFE0F",
            screening_shortlist: "\uD83D\uDD0D", screening_reject: "\u2715", screening_hold: "\u23F3",
            screening_shortlist_update: "\uD83D\uDD0D", screening_reject_update: "\u2715", screening_hold_update: "\u23F3",
            interview_scheduled: "\uD83E\uDD1D", interview_pass: "\u2705", interview_fail: "\u274C",
            salary_proposed: "\uD83D\uDCB0", salary_finalized: "\u2705",
            offer_created: "\uD83D\uDCC4", offer_approved: "\u2705", offer_rejected: "\u274C",
            offer_letter_generated: "\uD83D\uDCDD", offer_sent: "\u2709\uFE0F",
            offer_accepted: "\uD83C\uDF89", offer_rejected_candidate: "\u274C"
        };
        var labels = {
            candidate_created: "Applied", candidate_updated: "Profile Updated", candidate_deleted: "Deleted",
            screening_shortlist: "Screening Shortlisted", screening_reject: "Screening Rejected", screening_hold: "Screening Hold",
            screening_shortlist_update: "Screening Updated (Shortlisted)",
            screening_reject_update: "Screening Updated (Rejected)",
            screening_hold_update: "Screening Updated (Hold)",
            interview_scheduled: "Interview Scheduled", interview_pass: "Interview Passed", interview_fail: "Interview Failed",
            salary_proposed: "Salary Proposed", salary_finalized: "Salary Finalized",
            offer_created: "Offer Created", offer_approved: "Offer Approved", offer_rejected: "Offer Rejected",
            offer_letter_generated: "Letter Generated", offer_sent: "Offer Sent",
            offer_accepted: "Offer Accepted", offer_rejected_candidate: "Offer Rejected"
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

        var name = cand.first_name + " " + cand.last_name;
        var initials = cand.first_name.charAt(0) + cand.last_name.charAt(0);
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

(function() {
    var input = document.getElementById("candSearch");
    var box = document.getElementById("searchResults");
    var clearBtn = document.getElementById("searchClear");
    var debounceTimer = null;

    input.addEventListener("input", function() {
        var val = this.value.trim();
        clearBtn.style.display = val.length > 0 ? "inline" : "none";
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            var results = filterCandidates(val);
            renderDropdown(results);
        }, 150);
    });

    input.addEventListener("focus", function() {
        var val = this.value.trim();
        var results = filterCandidates(val);
        renderDropdown(results);
    });

    input.addEventListener("keydown", function(e) {
        var items = box.querySelectorAll(".sr-item");
        if (!items.length) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            activeIndex = Math.min(activeIndex + 1, items.length - 1);
            updateActive(items);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
            updateActive(items);
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (activeIndex >= 0 && activeIndex < filteredResults.length) {
                selectCandidate(filteredResults[activeIndex].id);
            } else if (filteredResults.length === 1) {
                selectCandidate(filteredResults[0].id);
            }
        } else if (e.key === "Escape") {
            box.classList.remove("open");
            input.blur();
        }
    });

    function updateActive(items) {
        for (var i = 0; i < items.length; i++) {
            items[i].classList.toggle("active", i === activeIndex);
        }
        if (activeIndex >= 0 && items[activeIndex]) {
            items[activeIndex].scrollIntoView({ block: "nearest" });
        }
    }

    document.addEventListener("click", function(e) {
        if (!e.target.closest(".search-wrap")) {
            box.classList.remove("open");
        }
    });

    document.getElementById("searchClear").addEventListener("click", function() {
        clearSearch();
        input.focus();
    });
})();

function loadCandidates() {
    var box = document.getElementById("searchResults");
    box.innerHTML = "<div class=\"sr-empty\">Loading candidates...</div>";
    box.classList.add("open");

    apiGet("ajax/candidates.php", null, function(res) {
        if (res.success && res.data) {
            candidatesData = res.data;
            box.classList.remove("open");
        }
    }, function() {
        box.innerHTML = "<div class=\"sr-empty\">Failed to load candidates</div>";
    });
}

loadCandidates();
';

ob_start();
?>
<div class="selector-bar card" style="padding:16px 24px;margin-bottom:24px">
    <label for="candSearch">Search Candidate:</label>
    <div class="search-wrap">
        <span class="search-icon">&#x1F50D;</span>
        <input type="text" id="candSearch" placeholder="Type name, code, job title, or status..." autocomplete="off">
        <button class="search-clear" id="searchClear" title="Clear">&times;</button>
        <div class="search-results" id="searchResults"></div>
    </div>
</div>
<div id="selectedInfo"></div>

<div id="timelineContainer">
    <div class="no-data">
        <div class="nd-icon">&#x1F50D;</div>
        <h3>Select a Candidate</h3>
        <p>Type a name, code, or job title to search</p>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
