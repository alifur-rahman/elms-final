<?php
$basePath    = '.';
$pageTitle   = 'Overview';
$activePage  = 'overview';
$breadcrumb  = 'Home / Overview';
$pageCss = 'css/page_css/index.css';

ob_start();
?>
<div class="welcome-card">
    <div class="brand-badge">ERP HRIS v2.0</div>
    <h1>Employee Lifecycle Management System</h1>
    <p>End-to-end HR operations from recruitment to confirmation</p>
</div>

<div class="card mb-24">
    <div class="card-header"><h2>System Overview</h2></div>
    <div class="card-body">
        <p style="line-height:1.8;color:var(--gray-600);font-size:14px">
            The <strong>Employee Lifecycle Management System (ELMS)</strong> is a comprehensive HR platform that manages the complete journey of an employee within the organization &mdash; from the moment a position is requisitioned through recruitment, hiring, probation, and finally confirmation. Each module is designed to handle a specific phase of the lifecycle, with data flowing seamlessly from one stage to the next.
        </p>
    </div>
</div>


<div class="card mb-24">
    <div class="card-header"><h2>System Capabilities</h2></div>
    <div class="card-body">
        <div class="caps-grid">
            <div class="cap-card">
                <div class="cap-icon" style="background:#e8f0fe">&#x1F4DD;</div>
                <div class="cap-content">
                    <h3>Requisition Management</h3>
                    <p>Create detailed job requisitions with role specifications, qualifications, salary ranges, and track approval status through the recruitment pipeline.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#e6f4ea">&#x1F50E;</div>
                <div class="cap-content">
                    <h3>Multi-Criteria Screening</h3>
                    <p>Evaluate candidates across communication, experience, skills, and education dimensions with weighted scoring to identify the best fits efficiently.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#fef7e0">&#x1F91D;</div>
                <div class="cap-content">
                    <h3>Interview Pipeline</h3>
                    <p>Schedule interviews, capture structured feedback and ratings, and systematically advance candidates through the selection process with full visibility.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#f3e8fd">&#x1F4B0;</div>
                <div class="cap-content">
                    <h3>Salary Proposal Workflow</h3>
                    <p>Define compensation packages with multi-level approval routing, ensuring all offers are properly authorized before extending to candidates.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#fce8e6">&#x1F4C4;</div>
                <div class="cap-content">
                    <h3>Template Engine</h3>
                    <p>Generate offer letters and confirmation documents from customizable templates with dynamic merge tags for candidate and employee data.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#e8f0fe">&#x1F504;</div>
                <div class="cap-content">
                    <h3>Status Automation</h3>
                    <p>Automatically update candidate and employee statuses across the lifecycle when actions are completed &mdash; no manual status changes required.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#e6f4ea">&#x1F4CA;</div>
                <div class="cap-content">
                    <h3>Probation Scoring</h3>
                    <p>Evaluate employees across multiple performance categories with supervisor and department head approvals, generating comprehensive appraisal records.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#fef7e0">&#x1F4CB;</div>
                <div class="cap-content">
                    <h3>Audit Trail</h3>
                    <p>Every status change and action is logged with timestamps and user information, providing a complete audit trail for compliance and reporting.</p>
                </div>
            </div>
            <div class="cap-card">
                <div class="cap-icon" style="background:#f3e8fd">&#x1F4C8;</div>
                <div class="cap-content">
                    <h3>Reports &amp; Analytics</h3>
                    <p>Generate probation summaries, confirmation status reports, and workforce analytics to support data-driven HR decision-making.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-24">
    <div class="card-header"><h2>Process Architecture</h2></div>
    <div class="card-body">
        <div class="arch-lanes">
            <div class="arch-lane">
                <div class="arch-lane-header" style="background:#e8f0fe;color:#1a73e8">
                    <span class="arch-lane-icon">&#x1F3AF;</span>
                    <span class="arch-lane-title">Recruitment Pipeline</span>
                </div>
                <div class="arch-lane-body">
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#1a73e8"></div>
                        <div class="arch-node-content">
                            <strong>Requisition</strong>
                            <span>Create job requisitions with role, qualifications &amp; salary range</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#1a73e8"></div>
                        <div class="arch-node-content">
                            <strong>Candidates</strong>
                            <span>Collect and manage applicant profiles and documents</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#1a73e8"></div>
                        <div class="arch-node-content">
                            <strong>Screening</strong>
                            <span>Evaluate candidates against job criteria &amp; shortlist</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#1a73e8"></div>
                        <div class="arch-node-content">
                            <strong>Interview</strong>
                            <span>Schedule interviews, capture feedback &amp; select best fit</span>
                        </div>
                    </div>
                    <div class="arch-node-outcome">
                        <span>Selected Candidate</span>
                    </div>
                </div>
            </div>
            <div class="arch-lane">
                <div class="arch-lane-header" style="background:#f3e8fd;color:#9334ea">
                    <span class="arch-lane-icon">&#x2705;</span>
                    <span class="arch-lane-title">Offer Management</span>
                </div>
                <div class="arch-lane-body">
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#9334ea"></div>
                        <div class="arch-node-content">
                            <strong>Salary Proposal</strong>
                            <span>Define compensation, designation &amp; joining terms</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#9334ea"></div>
                        <div class="arch-node-content">
                            <strong>Proposal Approval</strong>
                            <span>Route through approval workflow for authorization</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#9334ea"></div>
                        <div class="arch-node-content">
                            <strong>Offer Letter</strong>
                            <span>Generate letter from template &amp; send to candidate</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#9334ea"></div>
                        <div class="arch-node-content">
                            <strong>Response</strong>
                            <span>Record acceptance / rejection &amp; update status</span>
                        </div>
                    </div>
                    <div class="arch-node-outcome">
                        <span>Offer Accepted</span>
                    </div>
                </div>
            </div>
            <div class="arch-lane">
                <div class="arch-lane-header" style="background:#e6f4ea;color:#1e7e34">
                    <span class="arch-lane-icon">&#x1F464;</span>
                    <span class="arch-lane-title">Employment Lifecycle</span>
                </div>
                <div class="arch-lane-body">
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#1e7e34"></div>
                        <div class="arch-node-content">
                            <strong>Employee Creation</strong>
                            <span>Onboard candidate into employee records</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#1e7e34"></div>
                        <div class="arch-node-content">
                            <strong>Probation Appraisal</strong>
                            <span>Evaluate performance, score &amp; route for approval</span>
                        </div>
                    </div>
                    <div class="arch-connector-v"></div>
                    <div class="arch-node">
                        <div class="arch-node-dot" style="background:#1e7e34"></div>
                        <div class="arch-node-content">
                            <strong>Confirmation</strong>
                            <span>Generate confirmation letter &amp; finalize employment</span>
                        </div>
                    </div>
                    <div class="arch-node-outcome">
                        <span>Confirmed Employee</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Quick Module Access</h2></div>
    <div class="card-body">
        <div class="quick-links-grid">
            <a href="dashboard.php" class="quick-link-card"><div class="ql-icon">&#x1F4CA;</div><div class="ql-label">Dashboard</div></a>
            <a href="recruitment/list.php" class="quick-link-card"><div class="ql-icon">&#x1F3AF;</div><div class="ql-label">Requisitions</div></a>
            <a href="candidates/list.php" class="quick-link-card"><div class="ql-icon">&#x1F465;</div><div class="ql-label">Candidates</div></a>
            <a href="screening/list.php" class="quick-link-card"><div class="ql-icon">&#x1F50D;</div><div class="ql-label">Screening</div></a>
            <a href="interview/list.php" class="quick-link-card"><div class="ql-icon">&#x1F91D;</div><div class="ql-label">Interview</div></a>
            <a href="offer_management/proposal.php" class="quick-link-card"><div class="ql-icon">&#x2705;</div><div class="ql-label">Salary Proposal</div></a>
            <a href="offer_management/proposal_approval.php" class="quick-link-card"><div class="ql-icon">&#x1F4AF;</div><div class="ql-label">Proposal Approval</div></a>
            <a href="offer_management/letter.php" class="quick-link-card"><div class="ql-icon">&#x1F4C4;</div><div class="ql-label">Offer Letter</div></a>
            <a href="offer_management/response.php" class="quick-link-card"><div class="ql-icon">&#x1F4E8;</div><div class="ql-label">Response</div></a>
            <a href="employee/list.php" class="quick-link-card"><div class="ql-icon">&#x1F464;</div><div class="ql-label">Employees</div></a>
            <a href="probation/list.php" class="quick-link-card"><div class="ql-icon">&#x1F4DD;</div><div class="ql-label">Probation</div></a>
            <a href="probation/approve.php" class="quick-link-card"><div class="ql-icon">&#x1F9FE;</div><div class="ql-label">Probation Approve</div></a>
            <a href="confirmation/list.php" class="quick-link-card"><div class="ql-icon">&#x1F389;</div><div class="ql-label">Confirmation</div></a>
            <a href="confirmation/confirmation_letters.php" class="quick-link-card"><div class="ql-icon">&#x1F4C3;</div><div class="ql-label">Confirmation Letters</div></a>
            <a href="templates/list.php" class="quick-link-card"><div class="ql-icon">&#x1F4CB;</div><div class="ql-label">Templates</div></a>
            <a href="templates/tags.php" class="quick-link-card"><div class="ql-icon">&#x1F3F7;</div><div class="ql-label">Template Tags</div></a>
            <a href="reports/probation_summary.html" class="quick-link-card"><div class="ql-icon">&#x1F4C8;</div><div class="ql-label">Reports</div></a>
            <a href="docs/documentation.html" class="quick-link-card"><div class="ql-icon">&#x1F4D6;</div><div class="ql-label">Documentation</div></a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
