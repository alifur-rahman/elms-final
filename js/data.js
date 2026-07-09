// ============================================================
// ERP HRIS - Demo Data Store
// All data from the database schema, available for all HTML pages
// ============================================================

var DATA = {};

// ============================================================
// DEPARTMENTS
// ============================================================
DATA.departments = [
    { id: 1, name: 'Engineering', code: 'ENG' },
    { id: 2, name: 'Marketing', code: 'MKT' },
    { id: 3, name: 'Finance', code: 'FIN' },
    { id: 4, name: 'Human Resources', code: 'HR' },
    { id: 5, name: 'Design', code: 'DES' },
    { id: 6, name: 'Operations', code: 'OPS' },
    { id: 7, name: 'Sales', code: 'SAL' },
    { id: 8, name: 'IT', code: 'IT' }
];

// ============================================================
// DESIGNATIONS
// ============================================================
DATA.designations = [
    { id: 1, name: 'Software Engineer', code: 'SE', dept_id: 1 },
    { id: 2, name: 'Senior Developer', code: 'SD', dept_id: 1 },
    { id: 3, name: 'Marketing Specialist', code: 'MS', dept_id: 2 },
    { id: 4, name: 'Financial Analyst', code: 'FA', dept_id: 3 },
    { id: 5, name: 'HR Executive', code: 'HRE', dept_id: 4 },
    { id: 6, name: 'UX Designer', code: 'UXD', dept_id: 5 },
    { id: 7, name: 'Operations Manager', code: 'OM', dept_id: 6 },
    { id: 8, name: 'Sales Representative', code: 'SR', dept_id: 7 },
    { id: 9, name: 'IT Support', code: 'ITS', dept_id: 8 },
    { id: 10, name: 'Team Lead', code: 'TL', dept_id: 1 }
];

// ============================================================
// RECRUITMENT JOBS
// ============================================================
DATA.jobs = [
    {
        id: 1, title: 'Senior PHP Developer', dept_id: 1, location: 'Dhaka, Bangladesh',
        skills: 'PHP, Laravel, MySQL, JavaScript, Vue.js, REST APIs, Git',
        type: 'Full Time', education: 'Bachelor', vacancies: 3, filled: 1,
        experience: 3.5, deadline: '2026-08-31', salary_min: 45000, salary_max: 65000,
        currency: 'BDT',
        description: 'We are looking for an experienced PHP Developer to join our Engineering team. You will be responsible for building and maintaining web applications using modern PHP frameworks.',
        responsibilities: 'Design and develop web applications\nWrite clean, maintainable PHP code\nCollaborate with frontend team\nCode reviews and mentoring\nDatabase design and optimization',
        benefits: 'Competitive salary\nHealth insurance\nFlexible hours\nAnnual bonus\nTraining allowance',
        status: 'Active', priority: 'High'
    },
    {
        id: 2, title: 'UX Designer', dept_id: 5, location: 'Dhaka, Bangladesh',
        skills: 'Figma, Adobe XD, User Research, Wireframing, Prototyping, Design Systems',
        type: 'Full Time', education: 'Bachelor', vacancies: 2, filled: 0,
        experience: 2.0, deadline: '2026-09-15', salary_min: 35000, salary_max: 55000,
        currency: 'BDT',
        description: 'Join our Design team to create intuitive user experiences for our enterprise products.',
        responsibilities: 'Conduct user research\nCreate wireframes and prototypes\nDesign UI components\nUsability testing\nCollaborate with developers',
        benefits: 'Health insurance\nCreative work environment\nDesign tools stipend\nFlexible hours',
        status: 'Active', priority: 'Medium'
    },
    {
        id: 3, title: 'HR Executive', dept_id: 4, location: 'Dhaka, Bangladesh',
        skills: 'HR Management, Recruitment, Payroll, Labor Law, Communication',
        type: 'Full Time', education: 'Master', vacancies: 1, filled: 0,
        experience: 1.0, deadline: '2026-09-30', salary_min: 25000, salary_max: 35000,
        currency: 'BDT',
        description: 'Support HR operations including recruitment, employee relations, and compliance.',
        responsibilities: 'Manage recruitment process\nHandle employee onboarding\nMaintain HR records\nAssist with payroll\nEnsure labor law compliance',
        benefits: 'Health insurance\nProfessional development\nYearly bonus\nLeave encashment',
        status: 'Active', priority: 'Medium'
    }
];

// ============================================================
// CANDIDATES
// ============================================================
DATA.candidates = [
    { id: 1, job_id: 1, code: 'CAND-2026-0001', first_name: 'John', last_name: 'Doe', email: 'john.doe@email.com', phone: '+8801712345678', nid: '1234567890', blood_group: 'A+', dob: '1995-03-15', gender: 'Male', present_address: '123 Gulshan Avenue, Dhaka', permanent_address: '456 Village Road, Cumilla', current_salary: 35000, expected_salary: 55000, status: 'Joined', applied_at: '2026-01-10 09:30' },
    { id: 2, job_id: 1, code: 'CAND-2026-0002', first_name: 'Sarah', last_name: 'Smith', email: 'sarah.smith@email.com', phone: '+8801712345679', nid: '9876543210', blood_group: 'B+', dob: '1997-07-22', gender: 'Female', present_address: '456 Banani, Dhaka', permanent_address: '789 Town Hall Road, Barisal', current_salary: 30000, expected_salary: 48000, status: 'Offered', applied_at: '2026-02-05 10:15' },
    { id: 3, job_id: 2, code: 'CAND-2026-0003', first_name: 'Mike', last_name: 'Johnson', email: 'mike.j@email.com', phone: '+8801712345680', status: 'Shortlisted', applied_at: '2026-03-01 14:00' },
    { id: 4, job_id: 3, code: 'CAND-2026-0004', first_name: 'Emily', last_name: 'Brown', email: 'emily.b@email.com', phone: '+8801712345681', status: 'Applied', applied_at: '2026-03-10 11:45' },
    { id: 5, job_id: 1, code: 'CAND-2026-0005', first_name: 'David', last_name: 'Wilson', email: 'david.w@email.com', phone: '+8801712345682', status: 'Rejected', applied_at: '2026-01-20 08:00' }
];

// ============================================================
// SCREENING
// ============================================================
DATA.screenings = [
    { id: 1, candidate_id: 1, screened_by: 'Admin User', date: '2026-01-12', rating: 4.5, experience_match: 'High', skill_match: 'High', education_match: 'High', communication_score: 4.5, remarks: 'Strong candidate with excellent PHP experience and Laravel expertise. Perfect fit for the role.', result: 'Shortlist' },
    { id: 2, candidate_id: 3, screened_by: 'Admin User', date: '2026-03-03', rating: 4.0, experience_match: 'Medium', skill_match: 'High', education_match: 'Medium', communication_score: 4.0, remarks: 'Good portfolio. Has relevant UX experience but needs guidance on enterprise design.', result: 'Shortlist' },
    { id: 3, candidate_id: 5, screened_by: 'Admin User', date: '2026-01-22', rating: 2.0, experience_match: 'Low', skill_match: 'Medium', education_match: 'Medium', communication_score: 2.5, remarks: 'Experience does not match our requirements. Limited PHP framework knowledge.', result: 'Reject' }
];

// ============================================================
// INTERVIEWS
// ============================================================
DATA.interviews = [
    { id: 1, candidate_id: 1, date: '2026-01-18 10:00', type: 'Technical', interviewer: 'Senior Dev Team', round: 1, round_name: 'Technical Screening', rating: 4.5, feedback: 'Strong technical skills. Excellent problem-solving. Deep knowledge of Laravel and database optimization.', decision: 'Pass', status: 'Completed' },
    { id: 2, candidate_id: 1, date: '2026-01-22 14:00', type: 'Panel', interviewer: 'CTO, HR Manager, Team Lead', round: 2, round_name: 'Final Panel Interview', rating: 4.8, feedback: 'Excellent communication and team fit. Showed leadership potential. Recommended for hire.', decision: 'Pass', status: 'Completed' }
];

// ============================================================
// OFFERS
// ============================================================
DATA.offers = [
    { id: 1, candidate_id: 1, salary: 55000, joining_date: '2026-02-01', designation_id: 2, dept_id: 1, reporting_manager: 1, probation_months: 6, status: 'Accepted', sent_at: '2026-01-25 09:00', accepted_at: '2026-01-28 15:30' },
    { id: 2, candidate_id: 2, salary: 48000, joining_date: '2026-04-01', designation_id: 1, dept_id: 1, reporting_manager: 1, probation_months: 6, status: 'Pending', sent_at: '2026-03-15 10:00' }
];

// ============================================================
// OFFER APPROVALS
// ============================================================
DATA.offerApprovals = [
    { id: 1, offer_id: 1, level: 1, approver: 'Admin User', status: 'Approved', comment: 'Budget approved. Great candidate.' },
    { id: 2, offer_id: 2, level: 1, approver: 'Admin User', status: 'Pending', comment: null }
];

// ============================================================
// PRE-ONBOARDING TASKS
// ============================================================
DATA.preOnboardingTasks = [
    { id: 1, candidate_id: 1, task: 'Submit Educational Documents', type: 'Document', desc: 'Upload scanned copies of degrees and transcripts', due: '2026-01-30', assigned: 'Admin User', status: 'Completed', completed_at: '2026-01-29 16:00' },
    { id: 2, candidate_id: 1, task: 'NID Verification', type: 'Verification', desc: 'Verify National ID card details', due: '2026-01-30', assigned: 'Admin User', status: 'Completed', completed_at: '2026-01-30 10:30' },
    { id: 3, candidate_id: 1, task: 'IT Equipment Setup', type: 'Equipment', desc: 'Configure laptop, email, VPN access', due: '2026-01-31', assigned: 'Admin User', status: 'Completed', completed_at: '2026-01-31 14:00' },
    { id: 4, candidate_id: 1, task: 'Company Orientation', type: 'Orientation', desc: 'Schedule and complete new hire orientation', due: '2026-02-05', assigned: 'Admin User', status: 'Completed', completed_at: '2026-02-03 11:00' },
    { id: 5, candidate_id: 2, task: 'Submit Educational Documents', type: 'Document', desc: 'Upload scanned copies of degrees and transcripts', due: '2026-03-25', assigned: 'Admin User', status: 'Pending' },
    { id: 6, candidate_id: 2, task: 'Background Check', type: 'Verification', desc: 'Complete background verification process', due: '2026-03-28', assigned: 'Admin User', status: 'In Progress' }
];

// ============================================================
// EMPLOYEES
// ============================================================
DATA.employees = [
    { id: 1, code: 'EMP-2026-000001', candidate_id: 1, first_name: 'John', last_name: 'Doe', email: 'john.doe@company.com', phone: '+8801712345678', nid: '1234567890', blood_group: 'A+', dob: '1995-03-15', gender: 'Male', present_address: '123 Gulshan Avenue, Dhaka', permanent_address: '456 Village Road, Cumilla', dept_id: 1, designation_id: 2, reporting_manager_id: null, joining_date: '2026-02-01', salary: 55000, status: 'Confirmed', type: 'Permanent' }
];

// ============================================================
// PROBATIONS
// ============================================================
DATA.probations = [
    { id: 1, employee_id: 1, joining_date: '2026-02-01', months: 6, end_date: '2026-08-01', review_date: '2026-07-25', status: 'Completed', score: 4.52, recommendation: 'Confirm', remarks: 'John has consistently exceeded expectations. Strong technical skills, great team player, and shows leadership qualities. Recommend confirmation.' }
];

// ============================================================
// APPRAISAL CATEGORIES
// ============================================================
DATA.appraisalCategories = [
    { id: 1, name: 'Attendance', weight: 15 },
    { id: 2, name: 'Discipline', weight: 10 },
    { id: 3, name: 'Job Knowledge', weight: 15 },
    { id: 4, name: 'Productivity', weight: 15 },
    { id: 5, name: 'Quality of Work', weight: 15 },
    { id: 6, name: 'Communication', weight: 10 },
    { id: 7, name: 'Leadership', weight: 5 },
    { id: 8, name: 'Initiative', weight: 5 },
    { id: 9, name: 'Behavior', weight: 5 },
    { id: 10, name: 'Teamwork', weight: 5 }
];

// ============================================================
// CONFIRMATIONS
// ============================================================
DATA.confirmations = [
    { id: 1, employee_id: 1, probation_id: 1, conf_date: '2026-08-01', eff_date: '2026-08-01', salary_revision: 60000, designation: 'Senior Developer', department: 'Engineering', letter_no: 'CONF/2026/000001', status: 'Generated' }
];

// ============================================================
// LIFECYCLE LOGS
// ============================================================
DATA.lifecycleLogs = [
    { id: 1, emp_id: 1, event: 'candidate_applied', label: 'Candidate Application', desc: 'John Doe applied for Senior PHP Developer position', date: '2026-01-10 09:30', module: 'candidates' },
    { id: 2, emp_id: 1, event: 'screening_shortlisted', label: 'Screening Passed', desc: 'Candidate was shortlisted after screening', date: '2026-01-12 11:00', module: 'screening' },
    { id: 3, emp_id: 1, event: 'interview_scheduled', label: 'Technical Interview', desc: 'Technical interview was scheduled', date: '2026-01-15 10:00', module: 'interview' },
    { id: 4, emp_id: 1, event: 'interview_passed', label: 'Technical Interview Passed', desc: 'Candidate passed technical screening', date: '2026-01-18 12:00', module: 'interview' },
    { id: 5, emp_id: 1, event: 'interview_passed', label: 'Final Interview Passed', desc: 'Candidate passed final panel interview', date: '2026-01-22 16:00', module: 'interview' },
    { id: 6, emp_id: 1, event: 'offer_created', label: 'Offer Created', desc: 'Offer letter created for candidate', date: '2026-01-23 09:00', module: 'offer_approval' },
    { id: 7, emp_id: 1, event: 'offer_approved', label: 'Offer Approved', desc: 'Offer was approved by management', date: '2026-01-24 11:00', module: 'offer_approval' },
    { id: 8, emp_id: 1, event: 'offer_accepted', label: 'Offer Accepted', desc: 'Candidate accepted the offer', date: '2026-01-28 15:30', module: 'offer_letter' },
    { id: 9, emp_id: 1, event: 'pre_onboarding', label: 'Pre-Onboarding Started', desc: 'Pre-onboarding tasks were created', date: '2026-01-29 09:00', module: 'pre_onboarding' },
    { id: 10, emp_id: 1, event: 'employee_created', label: 'Employee Created', desc: 'Employee record created with code EMP-2026-000001', date: '2026-02-01 00:00', module: 'employee_master' },
    { id: 11, emp_id: 1, event: 'joined', label: 'Joining Completed', desc: 'Employee joined the organization', date: '2026-02-01 09:00', module: 'joining' },
    { id: 12, emp_id: 1, event: 'probation_started', label: 'Probation Started', desc: '6-month probation period started', date: '2026-02-01 09:00', module: 'probation' },
    { id: 13, emp_id: 1, event: 'probation_completed', label: 'Probation Completed', desc: 'Probation appraisal completed with score 4.52/5', date: '2026-07-25 14:00', module: 'probation' },
    { id: 14, emp_id: 1, event: 'confirmed', label: 'Employment Confirmed', desc: 'Employee confirmed with salary revision to 60,000.00', date: '2026-08-01 09:00', module: 'confirmation' }
];

// ============================================================
// HELPER FUNCTIONS
// ============================================================

function getDeptName(id) {
    var d = DATA.departments.find(function(d) { return d.id === id; });
    return d ? d.name : 'N/A';
}

function getDesignationName(id) {
    var d = DATA.designations.find(function(d) { return d.id === id; });
    return d ? d.name : 'N/A';
}

function getJobTitle(id) {
    var j = DATA.jobs.find(function(j) { return j.id === id; });
    return j ? j.title : 'N/A';
}

function getCandidateName(id) {
    var c = DATA.candidates.find(function(c) { return c.id === id; });
    return c ? c.first_name + ' ' + c.last_name : 'N/A';
}

function getEmployeeName(id) {
    var e = DATA.employees.find(function(e) { return e.id === id; });
    return e ? e.first_name + ' ' + e.last_name : 'N/A';
}

function getBadgeClass(status) {
    var map = {
        'Pending': 'badge-pending',
        'In Progress': 'badge-in-progress',
        'Completed': 'badge-completed',
        'Confirm': 'badge-confirm',
        'Confirmed': 'badge-completed',
        'Extend': 'badge-extend',
        'Terminate': 'badge-terminate',
        'Transfer': 'badge-transfer',
        'Draft': 'badge-pending',
        'Generated': 'badge-completed',
        'Sent': 'badge-in-progress',
        'Acknowledged': 'badge-completed',
        'Approved': 'badge-completed',
        'Accepted': 'badge-completed',
        'Rejected': 'badge-terminate',
        'Selected': 'badge-confirm',
        'Shortlisted': 'badge-in-progress',
        'Interview': 'badge-pending',
        'Offered': 'badge-confirm',
        'Joined': 'badge-completed',
        'Applied': 'badge-pending',
        'Active': 'badge-completed',
        'On Hold': 'badge-pending',
        'Closed': 'badge-terminate',
        'Pass': 'badge-completed',
        'Fail': 'badge-terminate',
        'Hold': 'badge-pending',
        'High': 'badge-terminate',
        'Medium': 'badge-pending',
        'Low': 'badge-in-progress',
        'Overdue': 'badge-terminate',
        'Scheduled': 'badge-pending',
        'Cancelled': 'badge-terminate',
        'Rescheduled': 'badge-in-progress',
        'Waived': 'badge-pending',
        'Probation': 'badge-in-progress'
    };
    return map[status] || 'badge-pending';
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    var d = new Date(dateStr);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
}

function formatDateTime(dateStr) {
    if (!dateStr) return '-';
    var d = new Date(dateStr);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear() + ' ' + d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
}

function formatCurrency(amount, symbol) {
    if (!amount) return '-';
    symbol = symbol || '$';
    return symbol + parseFloat(amount).toFixed(2);
}

function getDaysRemaining(dateStr) {
    if (!dateStr) return 0;
    var now = new Date();
    var target = new Date(dateStr);
    var diff = Math.ceil((target - now) / (1000 * 60 * 60 * 24));
    return diff;
}

function getStatusColor(days) {
    if (days <= 0) return '#d93025';
    if (days <= 15) return '#d93025';
    if (days <= 30) return '#f9ab00';
    return '#1e8e3e';
}

// ============================================================
// STATUS COUNTERS (for sidebar badges)
// ============================================================
DATA.counts = {
    recruitment: DATA.jobs.filter(function(j) { return j.status === 'Active'; }).length,
    candidates: DATA.candidates.filter(function(c) { return c.status === 'Applied' || c.status === 'Shortlisted'; }).length,
    screening: DATA.candidates.filter(function(c) { return c.status === 'Applied'; }).length,
    interview: DATA.candidates.filter(function(c) { return c.status === 'Interview' || c.status === 'Shortlisted'; }).length,
    assessment: DATA.candidates.filter(function(c) { return c.status === 'Interview'; }).length,
    offer_approval: DATA.offers.filter(function(o) { return o.status === 'Pending'; }).length,
    offer_letter: DATA.offers.filter(function(o) { return o.status === 'Pending'; }).length,
    pre_onboarding: DATA.candidates.filter(function(c) { return c.status === 'Offered'; }).length,
    joining_upcoming: DATA.offers.filter(function(o) { return o.status === 'Accepted'; }).length,
    probation_pending: DATA.probations.filter(function(p) { return p.status === 'Pending'; }).length,
    probation_approve: DATA.probations.filter(function(p) { return p.recommendation === 'Confirm'; }).length
};
