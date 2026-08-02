<?php
/**
 * Seed Demo Data
 * Clears all pipeline data and creates fresh demo data:
 * - 2 requisitions per department (16 total)
 * - 4 Applied candidates per requisition (64 total)
 */
error_reporting(E_ALL);
require_once __DIR__ . '/includes/config.php';

$pdo = getDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== Clearing pipeline data ===\n";
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE elms_offer_approvals");
$pdo->exec("TRUNCATE TABLE elms_offers");
$pdo->exec("TRUNCATE TABLE elms_interview_log");
$pdo->exec("TRUNCATE TABLE elms_screenings");
$pdo->exec("TRUNCATE TABLE elms_candidate_logs");
$pdo->exec("DELETE FROM elms_candidates");
$pdo->exec("ALTER TABLE elms_candidates AUTO_INCREMENT = 1");
$pdo->exec("DELETE FROM elms_requisitions");
$pdo->exec("ALTER TABLE elms_requisitions AUTO_INCREMENT = 1");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
echo "Pipeline data cleared.\n\n";

// ========================================================================
// Requisition definitions: 2 per department
// ========================================================================
$reqs = [
    // Engineering (dept_id=1)
    ['title' => 'Senior Software Engineer', 'dept_id' => 1, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 3, 'sal_min' => 120000, 'sal_max' => 180000, 'priority' => 'High'],
    ['title' => 'Junior Software Developer', 'dept_id' => 1, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 5, 'sal_min' => 40000, 'sal_max' => 70000, 'priority' => 'Medium'],
    // Marketing (dept_id=2)
    ['title' => 'Digital Marketing Manager', 'dept_id' => 2, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 2, 'sal_min' => 80000, 'sal_max' => 120000, 'priority' => 'High'],
    ['title' => 'Content Writer', 'dept_id' => 2, 'loc' => 'Remote', 'type' => 'Contract', 'vac' => 3, 'sal_min' => 25000, 'sal_max' => 40000, 'priority' => 'Low'],
    // Finance (dept_id=3)
    ['title' => 'Financial Analyst', 'dept_id' => 3, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 2, 'sal_min' => 60000, 'sal_max' => 90000, 'priority' => 'High'],
    ['title' => 'Accountant', 'dept_id' => 3, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 1, 'sal_min' => 35000, 'sal_max' => 50000, 'priority' => 'Medium'],
    // Human Resources (dept_id=4)
    ['title' => 'HR Manager', 'dept_id' => 4, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 1, 'sal_min' => 90000, 'sal_max' => 130000, 'priority' => 'High'],
    ['title' => 'Recruitment Specialist', 'dept_id' => 4, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 2, 'sal_min' => 45000, 'sal_max' => 65000, 'priority' => 'Medium'],
    // Design (dept_id=5)
    ['title' => 'Senior UX Designer', 'dept_id' => 5, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 2, 'sal_min' => 100000, 'sal_max' => 150000, 'priority' => 'High'],
    ['title' => 'Graphic Designer', 'dept_id' => 5, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 3, 'sal_min' => 30000, 'sal_max' => 50000, 'priority' => 'Low'],
    // Operations (dept_id=6)
    ['title' => 'Operations Manager', 'dept_id' => 6, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 1, 'sal_min' => 100000, 'sal_max' => 150000, 'priority' => 'High'],
    ['title' => 'Supply Chain Coordinator', 'dept_id' => 6, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 2, 'sal_min' => 40000, 'sal_max' => 60000, 'priority' => 'Medium'],
    // Sales (dept_id=7)
    ['title' => 'Sales Manager', 'dept_id' => 7, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 1, 'sal_min' => 80000, 'sal_max' => 120000, 'priority' => 'High'],
    ['title' => 'Sales Representative', 'dept_id' => 7, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 5, 'sal_min' => 25000, 'sal_max' => 40000, 'priority' => 'Medium'],
    // IT (dept_id=8)
    ['title' => 'System Administrator', 'dept_id' => 8, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 2, 'sal_min' => 50000, 'sal_max' => 80000, 'priority' => 'High'],
    ['title' => 'IT Support Engineer', 'dept_id' => 8, 'loc' => 'Dhaka, Bangladesh', 'type' => 'Full Time', 'vac' => 3, 'sal_min' => 25000, 'sal_max' => 40000, 'priority' => 'Low'],
];

echo "=== Creating requisitions ===\n";
$reqStmt = $pdo->prepare("INSERT INTO elms_requisitions (title, dept_id, location, type, vacancies, deadline, salary_min, salary_max, priority, status) VALUES (:title, :dept_id, :loc, :type, :vac, :deadline, :sal_min, :sal_max, :priority, 'Active')");
$reqIds = [];
foreach ($reqs as $r) {
    $deadline = date('Y-m-d', strtotime('+' . rand(30, 120) . ' days'));
    $reqStmt->execute([
        ':title'    => $r['title'],
        ':dept_id'  => $r['dept_id'],
        ':loc'      => $r['loc'],
        ':type'     => $r['type'],
        ':vac'      => $r['vac'],
        ':deadline' => $deadline,
        ':sal_min'  => $r['sal_min'],
        ':sal_max'  => $r['sal_max'],
        ':priority' => $r['priority'],
    ]);
    $rid = $pdo->lastInsertId();
    $reqIds[] = $rid;
    echo "  [{$rid}] {$r['title']} ({$r['dept_id']})\n";
}
echo count($reqs) . " requisitions created.\n\n";

// ========================================================================
// Candidates: 4 per requisition = 64 total, all Applied
// ========================================================================
$firstNames = ['Rahul', 'Priya', 'Arif', 'Sadia', 'Tanvir', 'Nusrat', 'Hasan', 'Farzana', 'Kazi', 'Sharmin', 'Mehedi', 'Tahmina', 'Jubayer', 'Sultana', 'Rakib', 'Nasrin', 'Imran', 'Shahnaz', 'Sohel', 'Nazma', 'Shakil', 'Rokeya', 'Mamun', 'Rashida', 'Emon', 'Nargis', 'Fahim', 'Laily', 'Shamim', 'Khadija', 'Tareq', 'Rowshan', 'Rashed', 'Sajeda', 'Milon', 'Jahanara', 'Himel', 'Sharmin', 'Mahbub', 'Saleha', 'Nayeem', 'Shamima', 'Rafi', 'Maksuda', 'Shohel', 'Aysha', 'Jamil', 'Shahnoor', 'Anik', 'Ferdousi', 'Bappy', 'Morsheda', 'Nadim', 'Shahida', 'Riaz', 'Hosne', 'Zahir', 'Sabina', 'Faruk', 'Suriya', 'Kawser', 'Rina', 'Jahid', 'Nasima'];

$lastNames  = ['Islam', 'Khan', 'Hossain', 'Ahmed', 'Rahman', 'Mollah', 'Chowdhury', 'Das', 'Sarker', 'Roy', 'Haque', 'Sen', 'Mondal', 'Biswas', 'Pal', 'Ghosh', 'Datta', 'Chakraborty', 'Bose', 'Majumder', 'Saha', 'Nath', 'Mistry', 'Kundu', 'Sikder', 'Gain', 'Barman', 'Malaker', 'Shil', 'Dhar', 'Poddar', 'Sutradhar'];

$candStmt = $pdo->prepare("INSERT INTO elms_candidates (job_id, code, first_name, last_name, email, phone, status_id, current_salary, expected_salary, applied_at) VALUES (:job_id, :code, :fname, :lname, :email, :phone, 1, :cur_sal, :exp_sal, :applied_at)");

echo "=== Creating candidates ===\n";
$count = 0;
$fnCount = count($firstNames);
$lnCount = count($lastNames);
foreach ($reqs as $ri => $r) {
    $reqId = $reqIds[$ri];
    for ($ci = 0; $ci < 4; $ci++) {
        $idx = $count % $fnCount;
        $fname = $firstNames[$idx];
        $lname = $lastNames[array_rand($lastNames)];
        $email = strtolower($fname . '.' . $lname . rand(10, 99) . '@email.com');
        $phone = '+88017' . rand(10000000, 99999999);
        $code = 'CND-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        $curSal = rand(20000, 150000);
        $expSal = $curSal + rand(5000, 60000);
        $daysAgo = rand(1, 60);
        $appliedAt = date('Y-m-d H:i:s', strtotime("-{$daysAgo} days"));

        $candStmt->execute([
            ':job_id'    => $reqId,
            ':code'      => $code,
            ':fname'     => $fname,
            ':lname'     => $lname,
            ':email'     => $email,
            ':phone'     => $phone,
            ':cur_sal'   => $curSal,
            ':exp_sal'   => $expSal,
            ':applied_at' => $appliedAt,
        ]);

        $candId = $pdo->lastInsertId();

        // Add log for Applied
        $logStmt = $pdo->prepare("INSERT INTO elms_candidate_logs (candidate_id, action, from_status, to_status, notes, created_by, created_at) VALUES (:cid, 'applied', NULL, 'Applied', 'Application submitted online', 'Admin User', :ca)");
        $logStmt->execute([':cid' => $candId, ':ca' => $appliedAt]);

        $count++;
    }
    echo "  {$r['title']}: 4 candidates\n";
}
echo "Total: {$count} candidates created.\n";

echo "\n=== DONE ===\n";
