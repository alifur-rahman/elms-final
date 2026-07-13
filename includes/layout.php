<?php
// ============================================================
// ERP HRIS - Main Layout
// Assembles header, sidebar, topbar, page content, and footer.
// Pages set variables before including this file:
//   $pageTitle   - Page title
//   $activePage  - Active sidebar item ID
//   $breadcrumb  - Breadcrumb text
//   $pageStyles  - Page-specific CSS
//   $pageScripts - Page-specific JS
//   $content     - Page body content (from ob_start/ob_get_clean)
// ============================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
?>
<?php require_once __DIR__ . '/header.php'; ?>

<?php require_once __DIR__ . '/sidebar.php'; ?>
<div class="sidebar-overlay" onclick="closeSidebar()"></div>

<main class="main-content">
    <?php require_once __DIR__ . '/topbar.php'; ?>

    <div class="page-content">
        <?= $content ?? '' ?>
    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
