<?php
$basePath   = '..';
$pageTitle  = 'Approval Management';
$activePage = 'probation_approve';
$breadcrumb = 'Home / Probation / Approval Management';
$pageStyles = '
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: var(--card-bg); border: 1px solid var(--gray-200); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
.stat-card .stat-icon { font-size: 32px; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
.stat-card .stat-icon.blue { background: #e8f0fe; color: var(--primary); }
.stat-card .stat-icon.green { background: #e6f4ea; color: var(--success); }
.stat-card .stat-icon.orange { background: #fef7e0; color: #e37400; }
.stat-card .stat-icon.purple { background: #f3e8fd; color: #9334e6; }
.stat-card .stat-info h3 { font-size: 28px; font-weight: 700; margin: 0; line-height: 1.2; }
.stat-card .stat-info p { font-size: 12px; color: var(--gray-500); margin: 0; }
.empty-state { text-align: center; padding: 48px 24px; color: var(--gray-500); }
.empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; }
.level-chain { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-bottom: 12px; }
.chain-step { display: flex; align-items: center; gap: 4px; font-size: 11px; padding: 4px 10px; border-radius: 4px; background: var(--gray-100); color: var(--gray-500); }
.chain-step.active { background: #e8f0fe; color: var(--primary); font-weight: 600; }
.chain-step.done { background: #e6f4ea; color: var(--success); }
.chain-step.rejected { background: #fce8e6; color: var(--danger); }
.chain-arrow { color: var(--gray-400); font-size: 12px; }
.profile-card { display: flex; gap: 16px; align-items: center; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 8px; padding: 16px; margin-bottom: 16px; }
.profile-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #1565c0); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; flex-shrink: 0; }
.profile-details { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 8px; }
.detail-item label { display: block; font-size: 10px; color: var(--gray-500); margin-bottom: 1px; }
.detail-item span { font-size: 12px; font-weight: 500; }
.score-card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 8px; }
.score-card { text-align: center; padding: 10px 6px; border: 1px solid var(--gray-200); border-radius: 6px; background: var(--gray-50); }
.score-card .sc-val { font-size: 18px; font-weight: 700; }
.score-card .sc-label { font-size: 10px; color: var(--gray-500); margin-top: 2px; }
.score-card .sc-weight { font-size: 9px; color: var(--gray-400); }
.approval-timeline { position: relative; padding-left: 24px; }
.approval-timeline::before { content: ""; position: absolute; left: 8px; top: 0; bottom: 0; width: 2px; background: var(--gray-200); }
.tl-item { position: relative; padding: 10px 0 10px 20px; }
.tl-item::before { content: ""; position: absolute; left: -18px; top: 14px; width: 10px; height: 10px; border-radius: 50%; background: var(--gray-300); }
.tl-item.done::before { background: var(--success); }
.tl-item.current::before { background: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,0.2); }
.tl-item.rejected::before { background: var(--danger); }
.tl-item .tl-time { font-size: 11px; color: var(--gray-500); }
.tl-item .tl-label { font-size: 13px; font-weight: 500; }
.tl-item .tl-user { font-size: 11px; color: var(--gray-500); }
.tl-item .tl-comment { font-size: 12px; color: var(--gray-600); margin-top: 4px; padding: 8px 12px; background: var(--gray-50); border-radius: 6px; }
.candidate-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:4px 24px; font-size:12px; }
.candidate-info-grid .info-label { color:var(--gray-500); font-size:10px; text-transform:uppercase; letter-spacing:.3px; }
.candidate-info-grid .info-value { font-weight:500; margin-bottom:6px; }
.candidate-info-grid .info-value.empty { color:var(--gray-400); font-style:italic; }
.candidate-info-grid .full { grid-column:1 / -1; }
.candidate-info-grid .half { }
.section-divider { border:none; border-top:1px solid var(--gray-200); margin:14px 0; }
.log-timeline { position:relative; padding-left:28px; }
.log-timeline::before { content:""; position:absolute; left:10px; top:4px; bottom:4px; width:2px; background:var(--gray-200); }
.log-timeline-item { position:relative; padding:6px 0 6px 16px; }
.log-timeline-item::before { content:""; position:absolute; left:-20px; top:10px; width:10px; height:10px; border-radius:50%; background:var(--gray-300); border:2px solid #fff; }
.log-timeline-item.screening::before { background:#1a73e8; }
.log-timeline-item.interview::before { background:#e37400; }
.log-timeline-item.offer::before { background:#188038; }
.log-timeline-item.probation::before { background:#9334e6; }
.log-timeline-item .lt-action { display:inline-block; font-size:10px; font-weight:600; padding:1px 8px; border-radius:10px; margin-right:4px; }
.lt-action.screening { background:#e8f0fe; color:#1a73e8; }
.lt-action.interview { background:#fef7e0; color:#e37400; }
.lt-action.offer { background:#e6f4ea; color:#188038; }
.lt-action.probation { background:#f3e8fd; color:#9334e6; }
.log-timeline-item .lt-time { font-size:10px; color:var(--gray-500); }
.log-timeline-item .lt-notes { font-size:12px; color:var(--gray-700); margin-top:2px; }
.log-timeline-item .lt-user { font-size:10px; color:var(--gray-400); }
.accordion-section { border:1px solid var(--gray-200); border-radius:8px; margin-bottom:12px; overflow:hidden; }
.accordion-header { display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:var(--gray-50); cursor:pointer; font-size:13px; font-weight:600; user-select:none; }
.accordion-header:hover { background:var(--gray-100); }
.accordion-header .acc-arrow { transition:transform .2s; font-size:12px; color:var(--gray-500); }
.accordion-header.open .acc-arrow { transform:rotate(90deg); }
.accordion-body { padding:12px 16px; display:none; }
.accordion-body.open { display:block; }
.screening-score-card { display:flex; align-items:center; gap:20px; background:var(--gray-50); border:1px solid var(--gray-200); border-radius:8px; padding:14px 18px; margin-bottom:12px; }
.screening-score-card .ssc-rating { text-align:center; min-width:70px; }
.screening-score-card .ssc-rating .ssc-val { font-size:28px; font-weight:700; line-height:1; }
.screening-score-card .ssc-rating .ssc-label { font-size:10px; color:var(--gray-500); }
.screening-score-card .ssc-details { flex:1; }
.screening-score-card .ssc-details .ssc-row { display:flex; gap:12px; flex-wrap:wrap; font-size:12px; margin-top:4px; }
.screening-score-card .ssc-details .ssc-row span { display:flex; align-items:center; gap:4px; }
.match-badge { display:inline-block; font-size:10px; font-weight:600; padding:2px 10px; border-radius:10px; }
.match-badge.High { background:#e6f4ea; color:#188038; }
.match-badge.Medium { background:#fef7e0; color:#e37400; }
.match-badge.Low { background:#fce8e6; color:#d93025; }
.interview-round-card { border:1px solid var(--gray-200); border-radius:8px; margin-bottom:10px; overflow:hidden; }
.interview-round-card .irc-header { display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:var(--gray-50); cursor:pointer; font-size:13px; font-weight:600; user-select:none; }
.interview-round-card .irc-header:hover { background:var(--gray-100); }
.interview-round-card .irc-header .irc-arrow { transition:transform .2s; font-size:12px; color:var(--gray-500); }
.interview-round-card .irc-header.open .irc-arrow { transform:rotate(90deg); }
.interview-round-card .irc-body { padding:12px 14px; display:none; }
.interview-round-card .irc-body.open { display:block; }
.interview-round-card .irc-meta { display:flex; gap:16px; font-size:12px; margin-bottom:8px; flex-wrap:wrap; }
.interview-round-card .irc-meta label { color:var(--gray-500); }
.irc-score-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:8px; margin:8px 0; }
.irc-score-item { text-align:center; padding:8px 6px; border:1px solid var(--gray-200); border-radius:6px; background:var(--card-bg); }
.irc-score-item .irc-sval { font-size:18px; font-weight:700; }
.irc-score-item .irc-slabel { font-size:10px; color:var(--gray-500); }
.decision-option { display: flex; align-items: center; gap: 8px; padding: 12px 20px; border: 2px solid var(--gray-200); border-radius: 8px; cursor: pointer; transition: all .2s; }
.decision-option:hover { border-color: var(--primary); }
.decision-option input[type="radio"] { width: 16px; height: 16px; accent-color: var(--primary); }
.audit-warning { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; background: #fef7e0; border: 1px solid #f9ab00; border-radius: 6px; font-size: 12px; color: var(--gray-700); }
.audit-warning .aw-icon { font-size: 18px; flex-shrink: 0; }
';
$pageScripts = <<<'EOSCRIPT'
var approvalData = [];

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

function renderApprovals() {
    var level = document.getElementById("levelFilter").value;
    var deptId = document.getElementById("deptFilter").value;
    var params = [];
    if (level) params.push("level=" + level);
    if (deptId) params.push("dept_id=" + deptId);
    var qs = params.length ? "?" + params.join("&") : "";
    apiGet("ajax/approval_list.php" + qs, null, function(res) {
        approvalData = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("approvalBody");
        var search = document.getElementById("searchInput").value.toLowerCase().trim();
        var filtered = search ? approvalData.filter(function(a) { return a.employee_name.toLowerCase().includes(search) || a.dept_name.toLowerCase().includes(search); }) : approvalData;
        var pending = approvalData.filter(function(a) { return a.status === "Pending" || a.status === "In Progress"; }).length;
        var urgent = approvalData.filter(function(a) { return a.days_left !== null && a.days_left >= 0 && a.days_left <= 7; }).length;
        document.getElementById("statPending").textContent = pending;
        document.getElementById("statUrgent").textContent = urgent;
        document.getElementById("statApproved").textContent = approvalData.filter(function(a) { return a.status === "Completed"; }).length;
        if (filtered.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No approvals pending.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < filtered.length; i++) {
            var a = filtered[i];
            var daysBadge = "";
            if (a.days_left !== null) {
                if (a.days_left < 0) daysBadge = "<span class=\"badge badge-danger\">Overdue</span>";
                else if (a.days_left <= 7) daysBadge = "<span class=\"badge badge-warning\">" + a.days_left + "d</span>";
                else daysBadge = "<span class=\"badge badge-completed\">" + a.days_left + "d</span>";
            }
            var score = a.overall_score !== null ? a.overall_score.toFixed(2) : "-";
            var statusBadge = a.status === "Completed" ? "<span class=\"badge badge-completed\">Completed</span>"
                : a.status === "In Progress" ? "<span class=\"badge badge-in-progress\">In Progress</span>"
                : "<span class=\"badge badge-pending\">" + a.status + "</span>";
            var levelHtml = a.current_level
                ? "<span class=\"level-pill current\" style=\"display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:500;background:#e8f0fe;color:var(--primary)\"><span style=\"width:6px;height:6px;border-radius:50%;background:var(--primary);display:inline-block\"></span>" + a.current_level + "</span>"
                : "-";
            html += "<tr>" +
                "<td><strong>" + a.employee_name + "</strong><br><small style='color:#888'>" + a.designation_name + "</small></td>" +
                "<td>" + a.dept_name + "</td>" +
                "<td>" + score + "</td>" +
                "<td>" + levelHtml + "</td>" +
                "<td>" + statusBadge + "</td>" +
                "<td>" + daysBadge + "</td>" +
                "<td><button class=\"btn btn-sm btn-primary\" onclick=\"openApproval(" + a.id + ")\">Review &amp; Approve</button></td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
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
        // Find current level index
        var curIdx = 0;
        for (var i = 0; i < LEVELS.length; i++) {
            if (LEVELS[i] === a.current_level) { curIdx = i; break; }
        }
        // Profile
        var initials = (a.employee_name || "?").split(" ").map(function(s) { return s.charAt(0); }).join("").slice(0, 2).toUpperCase();
        document.getElementById("actProfileCard").innerHTML =
            '<div class="profile-avatar">' + initials + '</div>' +
            '<div class="profile-details">' +
                '<div class="detail-item"><label>Employee</label><span>' + a.employee_name + '</span></div>' +
                '<div class="detail-item"><label>Department</label><span>' + (a.dept_name || "N/A") + '</span></div>' +
                '<div class="detail-item"><label>Score</label><span>' + (a.overall_score !== null ? a.overall_score.toFixed(2) : "-") + ' / 5</span></div>' +
                '<div class="detail-item"><label>End Date</label><span>' + (a.end_date || "N/A") + '</span></div>' +
            '</div>';
        // Level chain
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
        // Candidate Personal Info
        var infoFields = [
            ["Candidate Code", a.candidate_code, "half"], ["Status", a.candidate_status, "half"],
            ["Email", a.email, "full"], ["Phone", a.phone, "half"],
            ["Gender", a.gender || "—", "half"], ["Date of Birth", a.dob || "—", "half"],
            ["Blood Group", a.blood_group || "—", "half"], ["NID", a.nid || "—", "half"],
            ["Present Address", a.present_address || "—", "full"],
            ["Permanent Address", a.permanent_address || "—", "full"],
            ["Applied At", a.applied_at || "—", "half"], ["Current Salary", a.current_salary != null ? "৳" + Number(a.current_salary).toFixed(2) : "—", "half"],
        ];
        document.getElementById("actPersonalInfo").innerHTML = infoFields.map(function(f) {
            var valClass = f[1] === "—" || f[1] === "" ? "info-value empty" : "info-value";
            return '<div class="' + f[2] + '"><div class="info-label">' + f[0] + '</div><div class="' + valClass + '">' + f[1] + '</div></div>';
        }).join("");

        // Job & Offer Info
        var jobFields = [
            ["Job Title", a.job_title, "half"], ["Department", a.dept_name, "half"],
            ["Designation", a.designation_name, "half"], ["Job Location", a.job_location || "—", "half"],
            ["Job Type", a.job_type || "—", "half"], ["Education", a.job_education || "—", "full"],
            ["Experience", a.job_experience !== null ? a.job_experience + " yrs" : "—", "half"],
            ["Salary Range", a.job_salary_min !== null ? "$" + a.job_salary_min.toFixed(2) + " - $" + a.job_salary_max.toFixed(2) : "—", "half"],
            ["Skills", a.job_skills || "—", "full"],
            ["Offer Salary", a.offer_salary !== null ? "$" + a.offer_salary.toFixed(2) : "—", "half"],
            ["Offer Sent", a.offer_sent_at || "—", "half"],
            ["Offer Accepted", a.offer_accepted_at || "—", "half"],
            ["Offer Notes", a.offer_notes || "—", "full"],
            ["Offer Response Notes", a.offer_response_notes || "—", "full"],
        ];
        document.getElementById("actJobOfferInfo").innerHTML = jobFields.map(function(f) {
            var valClass = f[1] === "—" || f[1] === "" ? "info-value empty" : "info-value";
            return '<div class="' + f[2] + '"><div class="info-label">' + f[0] + '</div><div class="' + valClass + '">' + f[1] + '</div></div>';
        }).join("");

        // Screening Details
        var scrHtml = "";
        if (a.screenings && a.screenings.length) {
            for (var si = 0; si < a.screenings.length; si++) {
                var sc = a.screenings[si];
                var ratingColor = sc.rating >= 4 ? "var(--success)" : sc.rating >= 3 ? "var(--warning)" : "var(--danger)";
                var commPct = sc.communication_score !== null ? (sc.communication_score / 5 * 100) : 0;
                var matchCls = function(v) { return "match-badge " + (v || "Low"); };
                scrHtml += '<div class="screening-score-card">' +
                    '<div class="ssc-rating">' +
                        '<div class="ssc-val" style="color:' + ratingColor + '">' + (sc.rating !== null ? sc.rating.toFixed(1) : "—") + '</div>' +
                        '<div class="ssc-label">Overall Rating</div>' +
                    '</div>' +
                    '<div class="ssc-details">' +
                        '<div style="font-size:12px;font-weight:500">' + (sc.screened_by || "—") +
                        ' <span style="color:var(--gray-500);font-weight:400">on ' + (sc.date || "—") + '</span></div>' +
                        '<div class="ssc-row">' +
                            '<span>&#128172; Communication: <strong>' + (sc.communication_score !== null ? sc.communication_score.toFixed(1) + "/5" : "—") + '</strong></span>' +
                            '<span>&#127891; Education: <span class="' + matchCls(sc.education_match) + '">' + (sc.education_match || "—") + '</span></span>' +
                            '<span>&#128188; Experience: <span class="' + matchCls(sc.experience_match) + '">' + (sc.experience_match || "—") + '</span></span>' +
                            '<span>&#128736; Skills: <span class="' + matchCls(sc.skill_match) + '">' + (sc.skill_match || "—") + '</span></span>' +
                        '</div>' +
                        (sc.remarks ? '<div style="font-size:12px;color:var(--gray-600);margin-top:4px">&#128221; ' + sc.remarks + '</div>' : "") +
                        '<div style="font-size:11px;color:var(--gray-400);margin-top:4px">Result: <strong>' + (sc.result || "—") + '</strong> &middot; ' + sc.created_at + '</div>' +
                    '</div>' +
                '</div>';
            }
        } else {
            scrHtml = "<div style='text-align:center;padding:16px;color:#888'>No screening data available</div>";
        }
        document.getElementById("actScreeningInfo").innerHTML = scrHtml;

        // Interview Rounds
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
                            '<span><label>Type:</label> ' + (iv.type || "—") + '</span>' +
                            '<span><label>Interviewer:</label> ' + (iv.interviewer || "—") + '</span>' +
                            '<span><label>Date:</label> ' + (iv.date || "—") + '</span>' +
                            (iv.is_final_round ? '<span class="badge badge-completed" style="font-size:10px">Final Round</span>' : "") +
                        '</div>' +
                        '<div class="irc-score-grid">' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_technical >= 4 ? "var(--success)" : iv.score_technical >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_technical !== null ? iv.score_technical.toFixed(1) : "—") + '</div><div class="irc-slabel">Technical</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_communication >= 4 ? "var(--success)" : iv.score_communication >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_communication !== null ? iv.score_communication.toFixed(1) : "—") + '</div><div class="irc-slabel">Communication</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_problem_solving >= 4 ? "var(--success)" : iv.score_problem_solving >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_problem_solving !== null ? iv.score_problem_solving.toFixed(1) : "—") + '</div><div class="irc-slabel">Problem Solving</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.score_attitude >= 4 ? "var(--success)" : iv.score_attitude >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.score_attitude !== null ? iv.score_attitude.toFixed(1) : "—") + '</div><div class="irc-slabel">Attitude</div></div>' +
                            '<div class="irc-score-item"><div class="irc-sval" style="color:' + (iv.rating >= 4 ? "var(--success)" : iv.rating >= 3 ? "var(--warning)" : "var(--danger)") + '">' + (iv.rating !== null ? iv.rating.toFixed(1) : "—") + '</div><div class="irc-slabel">Overall</div></div>' +
                        '</div>' +
                        (iv.feedback ? '<div style="font-size:12px;color:var(--gray-600);margin-top:6px;padding:8px 10px;background:var(--gray-50);border-radius:6px">&#128221; ' + iv.feedback + '</div>' : "") +
                        (iv.reject_reason ? '<div style="font-size:12px;color:var(--danger);margin-top:4px">&#10060; ' + iv.reject_reason + '</div>' : "") +
                    '</div>' +
                '</div>';
            }
        } else {
            intHtml = "<div style='text-align:center;padding:16px;color:#888'>No interview data available</div>";
        }
        document.getElementById("actInterviewInfo").innerHTML = intHtml;

        // Summary
        var recColors = { Confirm: "badge-confirm", Extend: "badge-extend", Transfer: "badge-transfer", Terminate: "badge-terminate" };
        var recClass = recColors[a.recommendation] || "badge-pending";
        document.getElementById("actSummary").innerHTML =
            '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">' +
                '<span><strong>Probation Period:</strong> ' + (a.joining_date || "-") + ' to ' + (a.end_date || "-") + '</span>' +
                '<span class="badge ' + recClass + '" style="font-size:13px;padding:4px 16px">' + (a.recommendation || "Pending") + '</span>' +
            '</div>' +
            '<p><strong>Status:</strong> ' + a.status + '</p>' +
            '<p><strong>Remarks:</strong> ' + (a.remarks || "No remarks yet") + '</p>';
        // Score cards
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
        // History / Timeline
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
        // Reset decision
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
EOSCRIPT;
ob_start();
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">&#9200;</div>
        <div class="stat-info"><h3 id="statPending">0</h3><p>Pending Approval</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#10004;</div>
        <div class="stat-info"><h3 id="statApproved">0</h3><p>Completed</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">&#9888;</div>
        <div class="stat-info"><h3 id="statUrgent">0</h3><p>Urgent (&lt; 7 days)</p></div>
    </div>
</div>

<div class="card mb-24">
    <div class="card-header">
        <h2>Approval Queue</h2>
        <div class="filter-bar">
            <select class="form-control" id="levelFilter" onchange="renderApprovals()" style="width:180px">
                <option value="">All Levels</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Dept Head">Dept Head</option>
                <option value="HR Review">HR Review</option>
                <option value="GM">GM</option>
                <option value="Managing Director">Managing Director</option>
            </select>
            <select class="form-control" id="deptFilter" onchange="renderApprovals()" style="width:180px"><option value="">All Departments</option></select>
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="searchInput" placeholder="Search employee..." onkeyup="renderApprovals()">
            </div>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-container">
            <table id="approvalTable">
                <thead><tr><th>Employee</th><th>Department</th><th>Score</th><th>Current Level</th><th>Status</th><th>Days Left</th><th>Action</th></tr></thead>
                <tbody id="approvalBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Approval Action Modal -->
<div class="modal-overlay" id="approvalModal" onclick="if(event.target===this)closeModal('approvalModal')" style="display:none">
    <div class="modal modal-lg modal-sticky">
        <div class="modal-header modal-header-navy">
            <h3>Approval Action</h3>
            <button class="modal-close" onclick="closeModal('approvalModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="actProbationId">

            <div id="actProfileCard" class="profile-card"></div>
            <div id="actLevelChain" class="level-chain"></div>

            <!-- Candidate Profile Accordion -->
            <div class="accordion-section">
                <div class="accordion-header open" onclick="toggleAccordion(this)">
                    &#128100; Candidate Profile
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body open">
                    <h4 style="font-size:12px;color:var(--gray-500);margin:0 0 8px;text-transform:uppercase;letter-spacing:.5px">Personal Information</h4>
                    <div id="actPersonalInfo" class="candidate-info-grid"></div>
                    <hr class="section-divider">
                    <h4 style="font-size:12px;color:var(--gray-500);margin:0 0 8px;text-transform:uppercase;letter-spacing:.5px">Job &amp; Offer Details</h4>
                    <div id="actJobOfferInfo" class="candidate-info-grid"></div>
                </div>
            </div>

            <!-- Screening Details Accordion -->
            <div class="accordion-section">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    &#128269; Screening Details
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body">
                    <div id="actScreeningInfo"></div>
                </div>
            </div>

            <!-- Interview Rounds Accordion -->
            <div class="accordion-section">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    &#128172; Interview Rounds
                    <span class="acc-arrow">&#9654;</span>
                </div>
                <div class="accordion-body">
                    <div id="actInterviewInfo"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128221; Appraisal Summary</h2></div>
                <div class="card-body" style="font-size:13px">
                    <div id="actSummary"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128202; Section Scores</h2></div>
                <div class="card-body">
                    <div id="actScoreCards" class="score-card-grid"></div>
                </div>
            </div>

            <div class="card mb-16">
                <div class="card-header"><h2>&#128220; Approval History</h2></div>
                <div class="card-body">
                    <div id="actHistory" class="approval-timeline"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Your Decision <span class="required">*</span></label>
                <div style="display:flex;gap:16px;margin-top:8px;flex-wrap:wrap">
                    <label class="decision-option" id="actApproveLabel" onclick="selectDecision('Approved')" style="border-color:var(--success);background:#e6f4ea">
                        <input type="radio" name="actDecision" value="Approved" checked>
                        <span style="font-weight:600;color:var(--success)">&#10004; Approve</span>
                    </label>
                    <label class="decision-option" id="actSendBackLabel" onclick="selectDecision('Send Back')">
                        <input type="radio" name="actDecision" value="Send Back">
                        <span style="font-weight:600;color:var(--warning)">&#8635; Send Back</span>
                    </label>
                    <label class="decision-option" id="actRejectLabel" onclick="selectDecision('Rejected')">
                        <input type="radio" name="actDecision" value="Rejected">
                        <span style="font-weight:600;color:var(--danger)">&#10008; Reject</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Comments <span class="required">*</span></label>
                <textarea id="actComments" class="form-control textarea-full" placeholder="Enter comments for this approval decision..."></textarea>
            </div>

            <div class="audit-warning mb-16">
                <span class="aw-icon">&#9888;</span>
                <div><strong>Audit Trail:</strong> All decisions are logged with timestamp and user identity. This action will be recorded in the employee's permanent lifecycle audit trail and cannot be undone.</div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px">
                <button class="btn btn-outline" onclick="closeModal('approvalModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitApproval()">Submit Decision</button>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../includes/layout.php';
