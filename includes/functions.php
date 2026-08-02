<?php
// ============================================================
// ERP HRIS - Global Helper Functions
// ============================================================

function getPageTitle($title) {
    return $title . ' | ' . APP_NAME;
}

function isActive($page, $active) {
    return $page === $active ? 'active' : '';
}

function formatDate($dateStr) {
    if (!$dateStr) return '-';
    return date('M j, Y', strtotime($dateStr));
}

function formatDateTime($dateStr) {
    if (!$dateStr) return '-';
    return date('M j, Y H:i', strtotime($dateStr));
}

function formatCurrency($amount, $symbol = '$') {
    if (!$amount) return '-';
    return $symbol . number_format((float)$amount, 2);
}

function addCandidateLog(PDO $pdo, int $candidateId, string $action, ?string $fromStatus = null, ?string $toStatus = null, ?string $notes = null): void {
    $stmt = $pdo->prepare("INSERT INTO elms_candidate_logs (candidate_id, action, from_status, to_status, notes, created_by) VALUES (?, ?, ?, ?, ?, 'Admin User')");
    $stmt->execute([$candidateId, $action, $fromStatus, $toStatus, $notes]);
}

function appBaseUrl(): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ROOT_PATH));
    $appRoot = str_replace('\\', '/', ROOT_PATH);
    $rel = '';
    if ($docRoot && strpos($appRoot, $docRoot) === 0) {
        $rel = rtrim(substr($appRoot, strlen($docRoot)), '/');
    }
    return $scheme . '://' . $host . $rel;
}

function getOfferResponseToken(PDO $pdo, int $offerId): ?string {
    $stmt = $pdo->prepare("SELECT response_token FROM elms_offers WHERE id = :id");
    $stmt->execute([':id' => $offerId]);
    $token = $stmt->fetchColumn();
    if (!$token) {
        $token = bin2hex(random_bytes(32));
        $upd = $pdo->prepare("UPDATE elms_offers SET response_token = :token WHERE id = :id");
        $upd->execute([':token' => $token, ':id' => $offerId]);
    }
    return $token;
}

function getOfferResponseLink(PDO $pdo, int $offerId): ?string {
    $token = getOfferResponseToken($pdo, $offerId);
    if (!$token) return null;
    return appBaseUrl() . '/offer_management/respond.php?token=' . $token;
}

/* ── WaterAid DB Helpers (mysqli-based, matching NOC style) ─────── */
function get_mysqli(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int)DB_PORT);
        if ($conn->connect_error) {
            die('DB connection failed: ' . $conn->connect_error);
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

function db_query(string $sql) {
    return get_mysqli()->query($sql);
}

function find_a_field(string $table, string $field, string $where = '1=1') {
    $sql = "SELECT {$field} FROM {$table} WHERE {$where} LIMIT 1";
    $res = db_query($sql);
    if ($res && $row = $res->fetch_assoc()) {
        $vals = array_values($row);
        return $vals[0] ?? null;
    }
    return null;
}

function find_all_field(string $table, string $fields = '*', string $where = '1=1') {
    $sql = "SELECT {$fields} FROM {$table} WHERE {$where} LIMIT 1";
    $res = db_query($sql);
    if ($res && $row = $res->fetch_assoc()) {
        return (object)$row;
    }
    return null;
}
