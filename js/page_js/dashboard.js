function renderDashboard() {
    var probs = DATA.probations;
    var pending = probs.filter(function(p){ return p.status === "Pending" || p.status === "In Progress"; });
    var completed = probs.filter(function(p){ return p.status === "Completed"; });
    var dueCount = 0;
    var now = new Date();
    var monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    pending.forEach(function(p){
        var end = new Date(p.end_date);
        if (end >= now && end <= monthEnd) dueCount++;
    });

    document.getElementById("statPending").textContent = pending.length;
    document.getElementById("statCompleted").textContent = completed.length;
    document.getElementById("statDueMonth").textContent = dueCount;
    document.getElementById("statConfirm").textContent = DATA.confirmations.filter(function(c){ return c.status === "Generated"; }).length;

    var tbody = document.getElementById("probationBody");
    tbody.innerHTML = "";
    var emps = DATA.employees;
    probs.forEach(function(p){
        var emp = null;
        for (var i = 0; i < emps.length; i++) { if (emps[i].id === p.employee_id) { emp = emps[i]; break; } }
        if (!emp) return;
        var days = getDaysRemaining(p.end_date);
        var statusText = days > 0 ? (days <= 7 ? "Urgent" : "In Progress") : "Completed";
        var statusDays = days > 0 ? days + " days" : "Completed";
        var badgeClass = days > 7 ? "badge-in-progress" : (days > 0 ? "badge-pending" : "badge-completed");
        var row = document.createElement("tr");
        row.innerHTML = "<td><strong>" + emp.first_name + " " + emp.last_name + "</strong><br><span class=\"text-muted\">" + emp.code + "</span></td>" +
            "<td>" + getDeptName(emp.designation_id ? emp.designation_id : emp.dept_id) + "</td>" +
            "<td>" + getDesignationName(emp.designation_id) + "</td>" +
            "<td>" + formatDate(p.joining_date) + "</td>" +
            "<td>" + formatDate(p.end_date) + "</td>" +
            "<td><strong>" + (days > 0 ? days : "0") + "</strong> days</td>" +
            "<td><span class=\"badge " + badgeClass + "\">" + statusText + "</span></td>" +
            "<td><button class=\"btn btn-sm btn-primary\" onclick=\"window.location=\'probation/list.html\'\">View</button></td>";
        tbody.appendChild(row);
    });

    if (probs.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"8\" class=\"text-center text-muted\" style=\"padding:32px\">No probation records found</td></tr>";
    }

    var recentContainer = document.getElementById("recentTimeline");
    recentContainer.innerHTML = "";
    var logs = DATA.lifecycleLogs.slice().reverse().slice(0, 6);
    var icons = {
        candidate_applied: "\uD83D\uDC64", screening_shortlisted: "\uD83D\uDD0D", interview_scheduled: "\uD83E\uDD1D",
        interview_passed: "\uD83E\uDD1D", offer_created: "\uD83D\uDCC4", offer_approved: "\u2705", offer_accepted: "\uD83D\uDCC4",
        pre_onboarding: "\uD83D\uDCCB", employee_created: "\uD83D\uDC64", joined: "\uD83D\uDEAA",
        probation_started: "\uD83D\uDCDD", probation_completed: "\uD83D\uDCDD", confirmed: "\uD83C\uDF89"
    };
    logs.forEach(function(log){
        var item = document.createElement("div");
        item.className = "timeline-item";
        item.innerHTML = "<div class=\"tl-time\">" + formatDateTime(log.date) + "</div>" +
            "<div class=\"tl-title\">" + (icons[log.event] || "\u2022") + " " + log.label + "</div>" +
            "<div class=\"tl-desc\">" + log.desc + "</div>";
        recentContainer.appendChild(item);
    });
    if (logs.length === 0) {
        recentContainer.innerHTML = "<div class=\"text-muted\" style=\"padding:16px 0\">No recent activity</div>";
    }

    document.getElementById("lcRecruitment").textContent = DATA.jobs.filter(function(j){ return j.status === "Active"; }).length;
    document.getElementById("lcCandidates").textContent = DATA.candidates.length;
    document.getElementById("lcScreening").textContent = DATA.candidates.filter(function(c){ return c.status === "Applied"; }).length;
    document.getElementById("lcInterview").textContent = DATA.candidates.filter(function(c){ return c.status === "Interview" || c.status === "Shortlisted"; }).length;
    document.getElementById("lcOffer").textContent = DATA.offers.filter(function(o){ return o.status === "Pending"; }).length;
    document.getElementById("lcPreOnboard").textContent = DATA.candidates.filter(function(c){ return c.status === "Offered"; }).length;
    document.getElementById("lcProbation").textContent = probs.length;
    document.getElementById("lcConfirm").textContent = DATA.confirmations.length;

    var upcomingEl = document.getElementById("upcomingConfirmations");
    upcomingEl.innerHTML = "";
    var confs = DATA.confirmations;
    if (confs.length === 0) {
        upcomingEl.innerHTML = "<div class=\"text-muted\" style=\"padding:8px 0\">No upcoming confirmations</div>";
    } else {
        confs.forEach(function(c){
            var emp = null;
            for (var i = 0; i < emps.length; i++) { if (emps[i].id === c.employee_id) { emp = emps[i]; break; } }
            if (!emp) return;
            var item = document.createElement("div");
            item.className = "upcoming-item";
            var initials = emp.first_name.charAt(0) + emp.last_name.charAt(0);
            item.innerHTML = "<div class=\"ui-avatar\">" + initials + "</div>" +
                "<div class=\"ui-info\"><div class=\"ui-name\">" + emp.first_name + " " + emp.last_name + "</div>" +
                "<div class=\"ui-detail\">" + c.designation + " \u2022 " + c.department + "</div></div>" +
                "<div class=\"ui-date\">" + formatDate(c.eff_date) + "</div>";
            upcomingEl.appendChild(item);
        });
    }

    document.getElementById("sysEmpCount").textContent = emps.length;
    document.getElementById("sysJobCount").textContent = DATA.jobs.filter(function(j){ return j.status === "Active"; }).length;
}
var BASE_URL = ".";
renderDashboard();