<?php
$basePath    = '..';
$pageTitle   = 'Lifecycle Timeline';
$activePage  = 'lifecycle';
$breadcrumb  = 'Home / Lifecycle / Timeline';
$pageCss = 'css/page_css/lifecycle-view.css';
$pageJs = 'js/page_js/lifecycle-view.js';

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
