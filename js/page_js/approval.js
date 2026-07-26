var offersData = [];
var currentTab = "pending";

function renderApprovalTable() {
    var url = "ajax/list_pending.php";
    if (currentTab === "ready") url = "ajax/list_offers.php?status=Approved&with_letter=1&letter_status_not=Sent";
    else if (currentTab === "history") url = "ajax/list_offers.php?status=All&not_status=Pending";

    apiGet(url, null, function(res) {
        offersData = res.success && res.data ? res.data : [];
        var tbody = document.getElementById("approvalTableBody");
        if (offersData.length === 0) {
            tbody.innerHTML = "<tr><td colspan=\"7\"><div class=\"empty-state\"><div class=\"empty-icon\">📋</div><p>No offers found in this section.</p></div></td></tr>";
            return;
        }
        var html = "";
        for (var i = 0; i < offersData.length; i++) {
            var o = offersData[i];
            var statusClass = o.offer_status === "Approved" ? "badge badge-success" : o.offer_status === "Rejected" ? "badge badge-danger" : "badge badge-in-progress";
            var action = "";
            if (currentTab === "pending" && o.offer_status === "Pending") {
                action = "<button class=\"btn btn-sm btn-primary\" onclick=\"openApprovalAction(" + o.id + ")\">Review</button>";
            }
            html += "<tr>" +
                "<td><strong>" + (o.candidate_name || o.first_name + " " + o.last_name) + "</strong></td>" +
                "<td>" + (o.job_title || o.job || "N/A") + "</td>" +
                "<td>" + (o.offered_salary || o.proposed_amount ? formatCurrency(o.offered_salary || o.proposed_amount, "৳") : "-") + "</td>" +
                "<td><span class=\"" + statusClass + "\">" + (o.offer_status || "Pending") + "</span></td>" +
                "<td>" + (o.created_at || "-") + "</td>" +
                "<td>" + action + "</td>" +
            "</tr>";
        }
        tbody.innerHTML = html;
    });
}

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".tab").forEach(function(el) { el.classList.remove("active"); });
    document.getElementById("tab" + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add("active");
    renderApprovalTable();
}

function openCreateOffer() {
    apiGet("ajax/list_proposals.php?status=Finalized", null, function(res) {
        var candidates = res.success && res.data ? res.data : [];
        var select = document.getElementById("createOfferCandidate");
        select.innerHTML = "<option value=\"\">-- Select --</option>";
        for (var i = 0; i < candidates.length; i++) {
            var c = candidates[i];
            var opt = document.createElement("option");
            opt.value = c.id;
            opt.textContent = c.first_name + " " + c.last_name + " (" + (c.job_title || "N/A") + ")";
            select.appendChild(opt);
        }
        if (candidates.length === 0) {
            select.innerHTML = "<option value=\"\">No finalized candidates available</option>";
        }
        openModal("createOfferModal");
    });
}

function submitCreateOffer() {
    var candidateId = document.getElementById("createOfferCandidate").value;
    var salary = document.getElementById("createOfferSalary").value;
    var notes = document.getElementById("createOfferNotes").value;
    if (!candidateId || !salary) { showAlert("Please select a candidate and enter salary", "error"); return; }
    apiPost("ajax/offer_create.php", {
        candidate_id: candidateId,
        offered_salary: salary,
        notes: notes
    }, function(res) {
        showAlert(res.message || "Offer created!", "success");
        closeModal("createOfferModal");
        renderApprovalTable();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to create offer", "error");
    });
}

function openApprovalAction(offerId) {
    var o = null;
    for (var i = 0; i < offersData.length; i++) { if (offersData[i].id === offerId) { o = offersData[i]; break; } }
    if (!o) return;
    document.getElementById("approvalOfferId").value = o.id;
    document.getElementById("approvalCandidateName").value = o.candidate_name || o.first_name + " " + o.last_name;
    document.getElementById("approvalSalary").value = o.offered_salary || o.proposed_amount || "";
    openModal("approvalActionModal");
}

function submitApprovalAction(action) {
    var offerId = document.getElementById("approvalOfferId").value;
    var comments = document.getElementById("approvalComments").value;
    apiPost("ajax/offer_approve.php", {
        offer_id: offerId,
        action: action,
        comments: comments
    }, function(res) {
        showAlert(res.message || "Offer " + action + "d!", "success");
        closeModal("approvalActionModal");
        renderApprovalTable();
    }, function(err) {
        showAlert((typeof err === "object" && err.message) ? err.message : "Failed to process", "error");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    renderApprovalTable();
    document.getElementById("approvalActionModal").querySelectorAll("[data-action]").forEach(function(btn) {
        btn.addEventListener("click", function() { submitApprovalAction(this.dataset.action); });
    });
});