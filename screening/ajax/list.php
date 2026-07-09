<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';

try {
    $pdo = getDB();

    // Return all candidates with optional screening data (LEFT JOIN)
    $stmt = $pdo->query("
        SELECT
            c.id AS candidate_id, c.first_name, c.last_name, c.email, c.phone,
            c.job_id, c.status AS candidate_status, c.applied_at,
            r.title AS job_title, d.name AS dept_name,
            s.id AS screening_id, s.rating, s.communication_score,
            s.experience_match, s.skill_match, s.education_match,
            s.result AS screening_result, s.remarks AS screening_remarks,
            s.screened_by, s.date AS screening_date
        FROM candidates c
        LEFT JOIN screenings s ON s.candidate_id = c.id
        LEFT JOIN requisitions r ON r.id = c.job_id
        LEFT JOIN departments d ON d.id = r.dept_id
        WHERE c.status = 'Applied'
        ORDER BY c.created_at DESC
    ");

    $rows = $stmt->fetchAll();

    $data = [];
    foreach ($rows as $r) {
        $data[] = [
            'candidate_id'      => (int) $r['candidate_id'],
            'first_name'        => $r['first_name'],
            'last_name'         => $r['last_name'],
            'email'             => $r['email'],
            'phone'             => $r['phone'] ?? '',
            'job_id'            => (int) $r['job_id'],
            'job_title'         => $r['job_title'] ?? 'N/A',
            'dept_name'         => $r['dept_name'] ?? 'N/A',
            'candidate_status'  => $r['candidate_status'],
            'applied_at'        => $r['applied_at'],

            'screening_id'         => $r['screening_id'] ? (int) $r['screening_id'] : null,
            'rating'               => $r['rating'] ? (float) $r['rating'] : null,
            'communication_score'  => $r['communication_score'] ? (float) $r['communication_score'] : null,
            'experience_match'     => $r['experience_match'] ?? null,
            'skill_match'          => $r['skill_match'] ?? null,
            'education_match'      => $r['education_match'] ?? null,
            'screening_result'     => $r['screening_result'] ?? null,
            'screening_remarks'    => $r['screening_remarks'] ?? null,
            'screened_by'          => $r['screened_by'] ?? null,
            'screening_date'       => $r['screening_date'] ?? null,
        ];
    }

    echo json_encode(['success' => true, 'data' => $data]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
