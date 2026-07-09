// ============================================================
// ERP HRIS - Shared Application Functions
// ============================================================

// Open/Close Modal
function openModal(id) {
    var el = document.getElementById(id);
    if (el) el.style.display = 'flex';
}
function closeModal(id) {
    var el = document.getElementById(id);
    if (el) el.style.display = 'none';
}

// Alert system
function showAlert(message, type) {
    type = type || 'info';
    var container = document.getElementById('alertContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'alertContainer';
        container.style.cssText = 'position:fixed;top:16px;right:16px;z-index:9999;display:flex;flex-direction:column;gap:8px;max-width:400px';
        document.body.appendChild(container);
    }
    var colors = { success: '#1e8e3e', error: '#d93025', warning: '#f9ab00', info: '#1a73e8' };
    var icons = { success: '✓', error: '✕', warning: '⚠', info: 'ℹ' };
    var el = document.createElement('div');
    el.style.cssText = 'padding:12px 16px;background:' + (colors[type] || colors.info) + ';color:#fff;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);font-size:13px;display:flex;align-items:center;gap:8px;animation:slideIn 0.3s ease';
    el.innerHTML = '<span style="font-size:16px;font-weight:700">' + (icons[type] || icons.info) + '</span>' + message;
    container.appendChild(el);
    setTimeout(function() {
        el.style.opacity = '0';
        el.style.transition = 'opacity 0.3s';
        setTimeout(function() { el.remove(); }, 300);
    }, 3000);
}

// Sidebar nav items config
var SIDEBAR_ITEMS = [
    { label: 'Lifecycle', header: true },
    { id: 'dashboard',       text: 'Dashboard',          href: 'dashboard.html' },
    { id: 'lifecycle',       text: 'Lifecycle Timeline',  href: 'lifecycle/view.html' },
    { label: 'Recruitment', header: true },
    { id: 'recruitment',        text: 'Pipeline',         href: 'recruitment/list.html',         badge: 'recruitment' },
    { id: 'candidates',         text: 'Candidates',       href: 'candidates/list.html',          badge: 'candidates' },
    { id: 'screening',          text: 'Screening',        href: 'screening/list.html',           badge: 'screening' },
    { id: 'interview',          text: 'Interview',        href: 'interview/list.html',           badge: 'interview' },
    { id: 'assessment',         text: 'Assessment',       href: 'assessment/list.html',          badge: 'assessment' },
    { id: 'offer_approval',     text: 'Offer Approval',   href: 'offer_approval/list.html',      badge: 'offer_approval' },
    { id: 'offer_letter',       text: 'Offer Letter',     href: 'recruitment/offer_letter/list.html', badge: 'offer_letter' },
    { label: 'Onboarding', header: true },
    { id: 'pre_onboarding',     text: 'Pre-Onboarding',   href: 'pre_onboarding/list.html',      badge: 'pre_onboarding' },
    { id: 'joining',            text: 'Joining',          href: 'joining/list.html',             badge: 'joining_upcoming' },
    { label: 'Probation', header: true },
    { id: 'probation',          text: 'Probation Appraisal', href: 'probation/list.html',        badge: 'probation_pending' },
    { id: 'probation_approve',  text: 'Pending Approval', href: 'probation/approve.html',        badge: 'probation_approve' },
    { label: 'Confirmation', header: true },
    { id: 'confirmation',           text: 'Confirmation',       href: 'confirmation/list.html' },
    { id: 'confirmation_generate',  text: 'Generate Letter',    href: 'confirmation/generate.html' },
    { label: 'Employee', header: true },
    { id: 'employee_master', text: 'Employee Master', href: 'employee_master/list.html' },
    { label: 'Settings', header: true },
    { id: 'templates', text: 'Letter Templates', href: 'templates/list.html' },
    { id: 'reports',   text: 'Reports',          href: 'reports/probation_summary.html' }
];

// Render sidebar
function renderSidebar(activeId) {
    var sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    var base = typeof BASE_URL !== 'undefined' ? BASE_URL : '.';
    var html = '<aside class="sidebar">';
    html += '<div class="sidebar-brand"><div class="logo">ERP</div><div><span>HRIS</span><small>Employee Lifecycle</small></div></div>';
    html += '<nav class="sidebar-nav">';
    for (var i = 0; i < SIDEBAR_ITEMS.length; i++) {
        var item = SIDEBAR_ITEMS[i];
        if (item.header) {
            html += '<div class="nav-label">' + item.label + '</div>';
        } else {
            var isActive = activeId === item.id;
            var cnt = 0;
            if (item.badge && DATA && DATA.counts && DATA.counts[item.badge] !== undefined) {
                cnt = DATA.counts[item.badge];
            }
            html += '<a href="' + base + '/' + item.href + '" class="' + (isActive ? 'active' : '') + '">';
            html += '<span class="nav-dot"></span><span>' + item.text + '</span>';
            if (cnt > 0) html += '<span class="badge">' + cnt + '</span>';
            html += '</a>';
        }
    }
    html += '</nav>';
    html += '<div class="sidebar-footer">';
    html += '<a href="#"><span class="nav-dot"></span><span>Settings</span></a>';
    html += '<a href="#"><span class="nav-dot"></span><span>Logout</span></a>';
    html += '</div></aside>';
    sidebar.innerHTML = html;
}

// Header
function renderHeader(title, breadcrumb) {
    var header = document.getElementById('header');
    if (!header) return;
    header.innerHTML = '<header class="top-header">' +
        '<div class="page-title-area">' +
            '<h1>' + title + '</h1>' +
            '<div class="breadcrumb">' + (breadcrumb || 'Home / ' + title) + '</div>' +
        '</div>' +
        '<div class="header-right">' +
            '<div class="dropdown">' +
                '<button class="notification-btn" onclick="toggleDropdown()">🔔<span class="dot"></span></button>' +
                '<div class="dropdown-menu" id="notificationDropdown" style="display:none">' +
                    '<div class="dropdown-header">Notifications</div>' +
                    '<div class="dropdown-item"><div><strong>Reminder:</strong> John Doe probation ends in 7 days</div></div>' +
                    '<div class="dropdown-item"><div><strong>Approval Needed:</strong> Jane Smith appraisal pending</div></div>' +
                    '<div class="dropdown-item"><div><strong>Complete:</strong> Mike Johnson confirmed</div></div>' +
                '</div>' +
            '</div>' +
            '<div class="user-info">' +
                '<div class="user-avatar">HR</div>' +
                '<div><div class="user-name">Admin User</div><div class="user-role">HR Manager</div></div>' +
            '</div>' +
        '</div>' +
    '</header>';
}

// Notification dropdown
function toggleDropdown() {
    var el = document.getElementById('notificationDropdown');
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    var dd = document.getElementById('notificationDropdown');
    if (dd && !e.target.closest('.dropdown')) dd.style.display = 'none';
});

// Table filters
function filterTable(inputId, tableId) {
    var input = document.getElementById(inputId);
    var table = document.getElementById(tableId);
    if (!input || !table) return;
    var q = input.value.toLowerCase();
    var rows = table.querySelectorAll('tbody tr');
    for (var i = 0; i < rows.length; i++) {
        var found = false;
        var cells = rows[i].querySelectorAll('td');
        for (var j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(q) !== -1) { found = true; break; }
        }
        rows[i].style.display = found ? '' : 'none';
    }
}

function filterBySelect(selectId, tableId, colIndex) {
    var sel = document.getElementById(selectId);
    var table = document.getElementById(tableId);
    if (!sel || !table) return;
    var val = sel.value.toLowerCase();
    var rows = table.querySelectorAll('tbody tr');
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].querySelectorAll('td');
        if (!val || (cells[colIndex] && cells[colIndex].textContent.toLowerCase().trim() === val)) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

// Inject nav-dot CSS + slideIn animation
(function() {
    var s = document.createElement('style');
    s.textContent = '.nav-dot{display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--gray-400);flex-shrink:0;margin-right:10px;transition:all .2s}.sidebar-nav a:hover .nav-dot,.sidebar-nav a.active .nav-dot{background:var(--primary);box-shadow:0 0 6px rgba(26,115,232,0.4)}@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
    document.head.appendChild(s);
})();
