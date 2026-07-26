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