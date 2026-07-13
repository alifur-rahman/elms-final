<?php
$_SERVER["REQUEST_METHOD"] = "GET";
$_SERVER["HTTP_HOST"] = "localhost";
$_SERVER["REQUEST_URI"] = "/offer_management/ajax/list_proposals.php";
$_GET = [];
ob_start();
require "offer_management/ajax/list_proposals.php";
$out = ob_get_clean();
echo substr($out, 0, 500);
