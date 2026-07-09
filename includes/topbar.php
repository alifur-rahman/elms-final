<header class="top-header">
    <div class="page-title-area">
        <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
        <div class="breadcrumb"><?= $breadcrumb ?? 'Home / ' . ($pageTitle ?? 'Dashboard') ?></div>
    </div>
    <div class="header-right">
        <div class="dropdown">
            <button class="notification-btn" onclick="toggleDropdown()">&#x1F514;<span class="dot"></span></button>
            <div class="dropdown-menu" id="notificationDropdown" style="display:none">
                <div class="dropdown-header">Notifications</div>
                <div class="dropdown-item"><div><strong>Reminder:</strong> John Doe probation ends in 7 days</div></div>
                <div class="dropdown-item"><div><strong>Approval Needed:</strong> Jane Smith appraisal pending</div></div>
                <div class="dropdown-item"><div><strong>Complete:</strong> Mike Johnson confirmed</div></div>
            </div>
        </div>
        <div class="user-info">
            <div class="user-avatar">HR</div>
            <div>
                <div class="user-name">Admin User</div>
                <div class="user-role">HR Manager</div>
            </div>
        </div>
    </div>
</header>
