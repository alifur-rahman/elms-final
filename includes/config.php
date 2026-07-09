<?php
// ============================================================
// ERP HRIS - Application Configuration
// ============================================================

define('APP_NAME', 'ERP HRIS');
define('APP_SHORT', 'HRIS');
define('APP_VERSION', '2.0.0');
define('BASE_URL', '.');
define('ASSETS_URL', BASE_URL . '/assets');

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'elms');
define('DB_USER', 'root');
define('DB_PASS', '');

// Path constants
define('INCLUDES_PATH', __DIR__);
define('ROOT_PATH', dirname(__DIR__));

// Database connection helper
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

// Load global helper functions
require_once __DIR__ . '/functions.php';
