var approvalData = [];
var sortCol = "";
var sortAsc = true;
var pgPage = 1;
var pgRows = 10;

function loadDepartments() {
    apiGet("../ajax/departments.php", null, function(res) {
        var depts = res.success && res.data ? res.data : [];
        var sel = document.getElementById("deptFilter");
        sel.innerHTML = "<option value=\"\">All Departments</option>";
        for (var i = 0; i < depts.length; i++) {
            sel.innerHTML += "<option value=\"" + depts[i].id + "\">" + depts[i].name + "</option>";
        }
    });
}

function sortBy(col) {
    if (sortCol === col) sortAsc = !sortAsc;
    else { sortCol = col; sortAsc = true; }
    renderApprovals();
}

function getSortVal(a, col) {
    switch(col) {
        case "employee": return (a.employee_name || "").toLowerCase();
        case "department": return (a.dept_name || "").toLowerCase();
        case "score": return a.overall_score || 0;
        case "level": return (a.current_level || "").toLowerCase();
        case "status": return (a.status || "").toLowerCase();
        case "days": return a.days_left != null ? a.days_left : 9999;
        default: return "";
    }
}

function updateSortArrows(count, prefix) {
    for (var i = 0; i < count; i++) {
        var el = document.getElementById(prefix + "SortArrow" + i);
        if (!el) continue;
        if (sortCol === el.getAttribute("data-col")) {
            el.textContent = sortAsc ? "\u25B2" : "\u25BC";
            el.parentElement.classList.add("rp-sorted");
        } else {
            el.textContent = "\u2195";
            el.parentElement.classList.remove("rp-sorted");
        }
    }
}

function renderApprovals() {
    var level = document.getElementById("levelFilter").value;
    var deptId = document.getElementById("deptFilter").value;
    var params = [];
    if (level) params.push("level=" + level);
    if (deptId) params.push("dept_id=" + deptId);
    var qs = params.length ? "?" + params.join("&") : "";
    apiGet("ajax/approval_list.php" + qs, null, function(res) {
        approvalData = res.success && res.data ? res.data : [];
        var search = document.getElementById("searchInput").value.toLowerCase().trim();
        var filtered = search ? approvalData.filter(function(a) {
            return a.employee_name.toLowerCase().includes(search) || a.dept_name.toLowerCase().includes(search) || (a.designation_name || "").toLowerCase().includes(search);
        }) : approvalData.slice();

        if (sortCol) {
            filtered.sort(function(a, b) {
                var va = getSortVal(a, sortCol);
                var vb = getSortVal(b, sortCol);
                if (va < vb) return sortAsc ? -1 : 1;
                if (va > vb) return sortAsc ? 1 : -1;
                return 0;
            });
        }

        var pending = approvalData.filter(function(a) { return a.status === "Pending" || a.status === "In Progress"; }).length;
        var completed = approvalData.filter(function(a) { return a.status === "Completed"; }).length;
        var urgent = approvalData.filter(function(a) { return a.days_left !== null && a.days_left >= 0 && a.days_left <= 7; }).length;

        document.getElementById("statPending").textContent = pending;
        document.getElementById("statApproved").textContent = completed;
        document.getElementById("statUrgent").textContent = urgent;

        var totalPages = Math.max(1, Math.ceil(filtered.length / pgRows));
        if (pgPage > totalPages) pgPage = totalPages;
        var start = (pgPage - 1) * pgRows;
        var paged = filtered.slice(start, start + pgRows);

        renderTable(paged);
        renderMobileCards(paged);
        renderPagination(filtered.length, totalPages);
        updateSortArrows(6, "list");
    });
}

function renderTable(rows) {
    var tbody = document.getElementById("approvalBody");
    if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="rp-empty"><div class="rp-empty-icon">\uD83D\uDCCB</div><h3>No approvals found</h3><p>No items match your current filters.</p></div></td></tr>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var a = rows[i];
        var daysBadge = "";
        if (a.days_left !== null) {
            if (a.days_left < 0) daysBadge = '<span class="badge badge-danger">Overdue</span>';
            else if (a.days_left <= 7) daysBadge = '<span class="badge badge-warning">' + a.days_left + 'd</span>';
            else daysBadge = '<span class="badge badge-completed">' + a.days_left + 'd</span>';
        }
        var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
        var statusBadge = a.status === "Completed" ? '<span class="badge badge-completed">Completed</span>'
            : a.status === "In Progress" ? '<span class="badge badge-pending">In Progress</span>'
            : '<span class="badge badge-pending">' + a.status + '</span>';
        var levelHtml = a.current_level
            ? '<span class="level-pill current">' + a.current_level + '</span>'
            : "-";
        html += "<tr>" +
            "<td><strong>" + a.employee_name + "</strong><br><small style='color:#888'>" + (a.designation_name || "") + "</small></td>" +
            "<td>" + a.dept_name + "</td>" +
            "<td>" + score + "</td>" +
            "<td>" + levelHtml + "</td>" +
            "<td>" + statusBadge + "</td>" +
            "<td>" + daysBadge + "</td>" +
            '<td><div class="rp-actions-cell"><button class="rp-btn-text rp-btn-primary" onclick="openApproval(' + a.id + ')">Review</button></div></td>' +
        "</tr>";
    }
    tbody.innerHTML = html;
}

function renderMobileCards(rows) {
    var container = document.getElementById("mobileList");
    if (rows.length === 0) {
        container.innerHTML = '<div class="rp-empty"><div class="rp-empty-icon">\uD83D\uDCCB</div><h3>No approvals found</h3><p>No items match your current filters.</p></div>';
        return;
    }
    var html = "";
    for (var i = 0; i < rows.length; i++) {
        var a = rows[i];
        var daysBadge = "";
        if (a.days_left !== null) {
            if (a.days_left < 0) daysBadge = '<span class="badge badge-danger">Overdue</span>';
            else if (a.days_left <= 7) daysBadge = '<span class="badge badge-warning">' + a.days_left + 'd</span>';
            else daysBadge = '<span class="badge badge-completed">' + a.days_left + 'd</span>';
        }
        var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
        var statusBadge = a.status === "Completed" ? '<span class="badge badge-completed">Completed</span>'
            : a.status === "In Progress" ? '<span class="badge badge-pending">In Progress</span>'
            : '<span class="badge badge-pending">' + a.status + '</span>';
        var levelPill = a.current_level ? '<span class="level-pill current">' + a.current_level + '</span>' : "";
        html += '<div class="rp-mob-card">' +
            '<div class="rp-mob-top">' +
                '<div class="rp-mob-title">' + a.employee_name + '<small>' + (a.designation_name || "") + '</small></div>' +
                '<div class="rp-mob-badges">' + statusBadge + daysBadge + '</div>' +
            '</div>' +
            '<div class="rp-mob-grid">' +
                '<div class="rp-mob-field"><label>Department</label><span>' + a.dept_name + '</span></div>' +
                '<div class="rp-mob-field"><label>Score</label><span>' + score + ' / 5</span></div>' +
                '<div class="rp-mob-field"><label>Current Level</label><span>' + (levelPill || '-') + '</span></div>' +
                '<div class="rp-mob-field"><label>Days Left</label><span>' + (a.days_left !== null ? a.days_left + ' days' : '-') + '</span></div>' +
            '</div>' +
            '<div class="rp-mob-footer"><button class="rp-btn-text rp-btn-primary" onclick="openApproval(' + a.id + ')">Review &amp; Approve</button></div>' +
        '</div>';
    }
    container.innerHTML = html;
}

function renderPagination(total, totalPages) {
    var pgBar = document.querySelector(".rp-pagination");
    if (total <= pgRows) { pgBar.style.display = "none"; return; }
    pgBar.style.display = "";
    var info = document.getElementById("pgInfo");
    var center = document.getElementById("pgCenter");
    var rowsInfo = document.getElementById("pgRowsInfo");
    var start = total === 0 ? 0 : (pgPage - 1) * pgRows + 1;
    var end = Math.min(pgPage * pgRows, total);
    info.textContent = "Showing " + start + "\u2013" + end + " of " + total;

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
    renderApprovals();
}

function changeRows(val) {
    pgRows = parseInt(val) || 10;
    pgPage = 1;
    renderApprovals();
}

function toggleAccordion(header) {
    header.classList.toggle("open");
    var body = header.nextElementSibling;
    if (body) body.classList.toggle("open");
}

var LEVELS = ["Self Assessment", "Supervisor", "Dept Head", "HR Review", "GM", "Managing Director"];

function actionLabel(action) {
    var labels = {
        screening_shortlist: "Shortlisted", screening_reject: "Screening Rejected", screening_hold: "Screening Hold",
        interview_scheduled: "Interview Scheduled", interview_pass: "Interview Passed", interview_hold: "Interview Hold", interview_fail: "Interview Failed",
        offer_created: "Offer Created", offer_letter_generated: "Offer Letter Generated", offer_sent: "Offer Sent", offer_approved: "Offer Approved",
        salary_proposed: "Salary Proposed", salary_finalized: "Salary Finalized",
        probation_submitted: "Probation Submitted", probation_updated: "Probation Updated", probation_approved: "Probation Approved", probation_rejected: "Probation Rejected"
    };
    return labels[action] || action.replace(/_/g, " ").replace(/\b\w/g, function(c){return c.toUpperCase();});
}
function actionGroup(action) {
    if (action.indexOf("screening_") === 0) return "screening";
    if (action.indexOf("interview_") === 0) return "interview";
    if (action.indexOf("offer_") === 0 || action.indexOf("salary_") === 0) return "offer";
    return "probation";
}

function openApproval(id) {
    apiGet("ajax/appraisals_get.php?id=" + id, null, function(res) {
        if (!res.success || !res.data) { showAlert("Not found", "error"); return; }
        var a = res.data;
        document.getElementById("actProbationId").value = a.id;
        var curIdx = 0;
        for (var i = 0; i < LEVELS.length; i++) {
            if (LEVELS[i] === a.current_level) { curIdx = i; break; }
        }
        var initials = (a.employee_name || "?").split(" ").map(function(s) { return s.charAt(0); }).join("").slice(0, 2).toUpperCase();
        document.getElementById("actProfileCard").innerHTML =
            '<div class="profile-avatar">' + initials + '</div>' +
            '<div class="profile-details">' +
                '<div class="detail-item"><label>Employee</label><span>' + a.employee_name + '</span></div>' +
                '<div class="detail-item"><label>Department</label><span>' + (a.dept_name || "N/A") + '</span></div>' +
                '<div class="detail-item"><label>Score</label><span>' + (a.overall_score !== null ? a.overall_score.toFixed(2) : "-") + ' / 5</span></div>' +
                '<div class="detail-item"><label>End Date</label><span>' + (a.end_date || "N/A") + '</span></div>' +
            '</div>';
        var chainHtml = "";
        for (var i = 0; i < LEVELS.length; i++) {
            var l = LEVELS[i];
            var cls = "";
            if (i < curIdx) cls = "done";
            else if (i === curIdx && a.status !== "Completed") cls = "active";
            else if (a.status === "Completed") cls = "done";
            var check = cls === "done" ? "\u2713 " : "";
            chainHtml += "<div class=\"chain-step " + cls + "\">" + check + l + "</div>";
            if (i < LEVELS.length - 1) chainHtml += "<span class=\"chain-arrow\">\u2192</span>";
        }
        document.getElementById("actLevelChain").innerHTML = chainHtml;

        var infoFields = [
            ["Candidate Code", a.candidate_code, "half"], ["Status", a.candidate_status, "half"],
            ["Email", a.email, "full"], ["Phone", a.phone, "half"],
            ["Gender", a.gender || "\u2014", "half"], ["Date of Birth", a.dob || "\u2014", "half"],
            ["Blood Group", a.blood_group || "\u2014", "half"], ["NID", a.nid || "\u2014", "half"],
            ["Present Address", a.present_address || "\u2014", "full"],
            ["Permanent Address", a.permanent_address || "\u2014", "full"],
            ["Applied At", a.applied_at || "\u2014", "half"], ["Current Salary", a.current_salary != null ? "\u09F3" + Number(a.current_salary).toFixed(2) : "\u2014", "half"],
        ];
        document.getElementById("actPersonalInfo").innerHTML = infoFields.map(function(f) {
            var valClass = f[1] === "\u2014" || f[1] === "" ? "info-value empty" : "info-value";
            return '<div class="' + f[2] + '"><div class="info-label">' + f[0] + '</div><div class="' + valClass + '">' + f[1] + '</div></div>';
        }).join("");

        var jobFields = [
            ["Job Title", a.job_title, "half"], ["Department", a.dept_name, "half"],
            ["Designation", a.designation_name, "half"], ["Job Location", a.job_location || "\u2014", "half"],
            ["Job Type", a.job_type || "\u2014", "half"], ["Education", a.job_education || "\u2014", "full"],
            ["Experience", a.job_experience !== null ? a.job_experience + " yrs" : "\u2014", "half"],
            ["Salary Range", a.job_salary_min !== null ? "$" + a.job_salary_min.toFixed(2) + " - $" + a.job_salary_max.toFixed(2) : "\u2014", "half"],
            ["Skills", a.job_skills || "\u2014", "full"],
            ["Offer Salary", a.offer_salary !== null ? "$" + a.offer_salary.toFixed(2) : "\u2014", "half"],
            ["Offer Sent", a.offer_sent_at || "\u2014", "half"],
            ["Offer Accepted", a.offer_accepted_at || "\u2014", "half"],
            ["Offer Notes", a.offer_notes || "\u2014", "full"],
            ["Offer Response Notes", a.offer_response_notes || "\u2014", "full"],
        ];
        document.getElementById("actJobOfferInfo").innerHTML = jobFields.map(function(f) {
            var valClass = f[1] === "\u2014" || f[1] === "" ? "info-value empty" : "info-value";
            return '<div class="' + f[2] + '"><div class="info-label">' + f[0] + '</div><div class="' + valClass + '">' + f[1] + '</div></div>';
        }).join("");

        var scrHtml = "";
        if (a.screenings && a.screenings.length) {
            for (var si = 0; si < a.screenings.length; si++) {
                var sc = a.screenings[si];
                var ratingColor = sc.rating >= 4 ? "var(--success)" : sc.rating >= 3 ? "var(--warning)" : "var(--danger)";
                var matchCls = function(v) { return "match-badge " + (v || "Low"); };
                scrHtml += '<div class="screening-score-card">' +
                    '<div class="ssc-rating">' +
                        '<div class="ssc-val" style="color:' + ratingColor + '">' + (sc.rating !== null ? sc.rating.toFixed(1) : "\u2014") + '</div>' +
                        '<div class="ssc-label">Overall Rating</div>' +
                    '</div>' +
                    '<div class="ssc-details">' +
                        '<div style="font-size:12px;font-weight:500">' + (sc.screened_by || "\u2014") +
                        ' <span style="color:var(--gray-500);font-weight:400">on ' + (sc.date || "\u2014") + '</span></div>' +
                        '<div class="ssc-row">' +
                            '<span>\uD83D\uDCAC Communication: <strong>' + (sc.communication_score !== null ? sc.communication_score.toFixed(1) + "/5" : "\u2014") + '</strong></span>' +
                            '<span>\uD83C\uDF93 Education: <span class="' + matchCls(sc.education_match) + '">' + (sc.education_match || "\u2014") + '</span></span>' +
                            '<span>\uD83C\uDFE2 Experience: <span class="' + matchCls(sc.experience_match) + '">' + (sc.experience_match || "\u2014") + '</span></span>' +
                            '<span>\uD83D\uDD27 Skills: <span class="' + matchCls(sc.skill_match) + '">' + (sc.skill_match || "\u2014") + '</span></span>' +
                        '</div>' +
                        (sc.remarks ? '<div style="font-size:12px;color:var(--gray-600);margin-top:4px">\uD83D\uDCDD ' + sc.remarks + '</div>' : "") +
                        '<div style="font-size:11px;color:var(--gray-400);margin-top:4px">Result: <strong>' + (sc.result || "\u2014") + '</strong> &middot; ' + sc.created_at + '</div>' +
                    '</div>' +
                '</div>';
            }
        } else {
            scrHtml = "<div style='text-align:center;padding:16px;color:#888'>No screening data available</div>";
        }
        document.getElementById("actScreeningInfo").innerHTML = scrHtml;

        var intHtml = "";
        if (a.interviews && a.interviews.length) {
            for (var ii = 0; ii < a.interviews.length; ii++) {
                var iv = a.interviews[ii];
                var decColor = iv.decision === "Pass" ? "var(--success)" : iv.decision === "Fail" ? "var(--danger)" : "var(--warning)";
                var decBg = iv.decision === "Pass" ? "#e6f4ea" : iv.decision === "Fail" ? "#fce8e6" : "#fef7e0";
                var isOpen = ii === 0 ? "open" : "";
                intHtml += '<div class="interview-round-card">' +
                    '<div class="irc-header ' + isOpen + '" onclick="this.classList.toggle(\'open\');var b=this.nextElementSibling;if(b)b.classList.toggle(\'open\')">' +
                        '<span>Round ' + iv.round + ': ' + (iv.round_name || "Interview") + '</span>' +
                        '<span><span class="badge" style="background:' + decBg + ';color:' + decColor + ';font-size:10px;padding:2px 10px">' + (iv.decision || "Pending") + '</span> <span class="irc-arrow">&#9654;</span></span>' +
                    '</div>' +
                    '<div class="irc-body ' + isOpen + '">' +
                        '<div class="irc-meta">' +
                            '<span><label>Type:</label> ' + (iv.type || "\u2014") + '</span>' +
                            '<span><label>Interviewer:</label> ' + (iv.interviewer || "\u2014") + '</span>' +
                            '<span><label>Date:</label> ' + (iv.date || "\u2014") + '</span>' +
                            (iv.is_final_round ? '<span class="badge badge-completed" style="font-size:10px">Final Round</span>' : "") +
                        '</div>' +
                        '<div class="irc-score-grid">' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_technical >= 4 ? "var(--success)" : iv.score_technical >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_technical !== null ? iv.score_technical.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Technical</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_communication >= 4 ? "var(--success)" : iv.score_communication >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_communication !== null ? iv.score_communication.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Communication</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_problem_solving >= 4 ? "var(--success)" : iv.score_problem_solving >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_problem_solving !== null ? iv.score_problem_solving.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Problem Solving</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_attitude >= 4 ? "var(--success)" : iv.score_attitude >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_attitude !== null ? iv.score_attitude.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Attitude</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.rating >= 4 ? "var(--success)" : iv.rating >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.rating !== null ? iv.rating.toFixed(1) : "\u2014") + '</div><div class="irc-slabel">Overall</div></div>' +
                        '</div>' +
                        (iv.feedback ? '<div style="font-size:12px;color:var(--gray-600);margin-top:6px;padding:8px 10px;background:var(--gray-50);border-radius:6px">\uD83D\uDCDD ' + iv.feedback + '</div>' : "") +
                        (iv.reject_reason ? '<div style="font-size:12px;color:var(--danger);margin-top:4px">\u274C ' + iv.reject_reason + '</div>' : "") +
                    '</div>' +
                '</div>';
            }
        } else {
            intHtml = "<div style='text-align:center;padding:16px;color:#888'>No interview data available</div>";
        }
        document.getElementById("actInterviewInfo").innerHTML = intHtml;

        var recColors = { Confirm: "badge-confirm", Extend: "badge-extend", Transfer: "badge-transfer", Terminate: "badge-terminate" };
        var recClass = recColors[a.recommendation] || "badge-pending";
        document.getElementById("actSummary").innerHTML =
            '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:8px">' +
                '<span style="font-size:13px"><strong>Probation Period:</strong> ' + (a.joining_date || "-") + ' to ' + (a.end_date || "-") + '</span>' +
                '<span class="badge ' + recClass + '" style="font-size:13px;padding:4px 16px">' + (a.recommendation || "Pending") + '</span>' +
            '</div>' +
            '<p style="font-size:13px;margin:4px 0"><strong>Status:</strong> ' + a.status + '</p>' +
            '<p style="font-size:13px;margin:4px 0"><strong>Remarks:</strong> ' + (a.remarks || "No remarks yet") + '</p>';

        var scoreHtml = "";
        if (a.scores && a.scores.length) {
            for (var i = 0; i < a.scores.length; i++) {
                var s = a.scores[i];
                var pct = (s.score / 5 * 100);
                var color = pct >= 80 ? "var(--success)" : pct >= 60 ? "var(--warning)" : "var(--danger)";
                scoreHtml += '<div class="score-card">' +
                    '<div class="sc-val" style="color:' + color + '">' + s.score.toFixed(1) + '</div>' +
                    '<div class="sc-weight">' + s.weight + '%</div>' +
                    '<div class="sc-label">' + s.category_name + '</div>' +
                '</div>';
            }
        } else {
            scoreHtml = "<div style='text-align:center;padding:16px;color:#888'>No scores</div>";
        }
        document.getElementById("actScoreCards").innerHTML = scoreHtml;

        var histHtml = "";
        if (a.workflow && a.workflow.length) {
            for (var i = 0; i < LEVELS.length; i++) {
                var lvl = LEVELS[i];
                var wf = null;
                for (var j = 0; j < a.workflow.length; j++) {
                    if (a.workflow[j].level === lvl) { wf = a.workflow[j]; break; }
                }
                var hcls = "";
                var decision = "Awaiting";
                if (wf) {
                    if (wf.status === "Approved") { hcls = "done"; decision = "Approved"; }
                    else if (wf.status === "Rejected") { hcls = "rejected"; decision = "Rejected"; }
                    else if (wf.status === "Sent Back") { hcls = "done"; decision = "Sent Back"; }
                    else { hcls = "current"; decision = "Pending Review"; }
                }
                var userInfo = wf && wf.approved_by ? wf.level + " \u2014 " + wf.approved_by : lvl + " \u2014 Pending";
                var actionTime = wf && wf.action_at ? wf.action_at : "Pending";
                histHtml += '<div class="tl-item ' + hcls + '">' +
                    '<div class="tl-time">' + actionTime + '</div>' +
                    '<div class="tl-label">' + userInfo + '</div>' +
                    '<div class="tl-user">Decision: ' + decision + '</div>' +
                    (wf && wf.comments ? '<div class="tl-comment">"' + wf.comments + '"</div>' : '') +
                '</div>';
            }
        } else {
            histHtml = "<div style='text-align:center;padding:16px;color:#888'>No approval history yet</div>";
        }
        document.getElementById("actHistory").innerHTML = histHtml;

        document.querySelectorAll(".decision-option").forEach(function(el) {
            el.style.borderColor = "var(--gray-200)";
            el.style.background = "transparent";
        });
        document.getElementById("actComments").value = "";
        selectDecision("Approved");
        openModal("approvalModal");
    });
}

function selectDecision(val) {
    var colors = { Approved: "var(--success)", "Send Back": "var(--warning)", Rejected: "var(--danger)" };
    var bgColors = { Approved: "#e6f4ea", "Send Back": "#fef7e0", Rejected: "#fce8e6" };
    var labels = ["actApproveLabel", "actSendBackLabel", "actRejectLabel"];
    for (var i = 0; i < labels.length; i++) {
        var el = document.getElementById(labels[i]);
        if (!el) continue;
        el.style.borderColor = "var(--gray-200)";
        el.style.background = "transparent";
    }
    var key = val === "Send Back" ? "Send Back" : val;
    var selEl = val === "Approved" ? document.getElementById("actApproveLabel") : val === "Send Back" ? document.getElementById("actSendBackLabel") : document.getElementById("actRejectLabel");
    if (selEl) {
        selEl.style.borderColor = colors[key] || "var(--gray-200)";
        selEl.style.background = bgColors[key] || "transparent";
    }
    var rb = document.querySelector('input[name="actDecision"][value="' + val + '"]');
    if (rb) rb.checked = true;
}

function submitApproval() {
    var id = parseInt(document.getElementById("actProbationId").value);
    var decision = document.querySelector('input[name="actDecision"]:checked');
    if (!decision) { showAlert("Please select a decision", "error"); return; }
    var comments = document.getElementById("actComments").value.trim();
    if (!comments) { showAlert("Please enter comments", "error"); return; }
    apiPost("ajax/approval_action.php", { appraisal_id: id, action: decision.value === "Approved" ? "approve" : decision.value === "Send Back" ? "sendback" : "reject", comments: comments }, function(res) {
        showAlert(res.message || "Decision recorded!", "success");
        closeModal("approvalModal");
        renderApprovals();
    });
}

document.addEventListener("DOMContentLoaded", function() {
    loadDepartments();
    renderApprovals();
});