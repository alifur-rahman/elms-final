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
