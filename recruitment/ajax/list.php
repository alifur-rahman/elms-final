<?php
// ============================================================
// ERP HRIS - AJAX: List Requisitions
// ============================================================

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';

$conn = get_mysqli();

$res = $conn->query("
    SELECT r.*, d.name AS dept_name
    FROM elms_requisitions r
    LEFT JOIN elms_departments d ON d.id = r.dept_id
    ORDER BY r.created_at DESC
");

$jobs = [];
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $jobs[] = [
            'id'            => (int) $r['id'],
            'title'         => $r['title'],
            'dept_id'       => (int) $r['dept_id'],
            'dept_name'     => $r['dept_name'] ?? 'N/A',
            'location'      => $r['location'] ?? '',
            'type'          => $r['type'],
            'vacancies'     => (int) $r['vacancies'],
            'filled'        => 0,
            'experience'    => (float) $r['experience'],
            'education'     => $r['education'] ?? '',
            'deadline'      => $r['deadline'] ?? '',
            'salary_min'    => (float) $r['salary_min'],
            'salary_max'    => (float) $r['salary_max'],
            'skills'        => $r['skills'] ?? '',
            'description'   => $r['description'] ?? '',
            'responsibilities' => $r['responsibilities'] ?? '',
            'benefits'      => $r['benefits'] ?? '',
            'priority'      => $r['priority'],
            'status'        => $r['status'],
            'created_at'    => $r['created_at'],
        ];
    }
}

echo json_encode(['success' => true, 'data' => $jobs]);