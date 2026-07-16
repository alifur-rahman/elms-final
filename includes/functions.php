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
    $stmt = $pdo->prepare("INSERT INTO candidate_logs (candidate_id, action, from_status, to_status, notes, created_by) VALUES (?, ?, ?, ?, ?, 'Admin User')");
    $stmt->execute([$candidateId, $action, $fromStatus, $toStatus, $notes]);
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
