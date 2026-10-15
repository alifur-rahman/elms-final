# Employee Lifecycle Management System (ELMS)

> **ERP HRIS v2.0** — A comprehensive full-stack HR platform managing the complete employee journey from recruitment to confirmation.

## 🌐 Live Demo

**[https://hris.garmentsbridge.com/v4/](https://hris.garmentsbridge.com/v4/)**

---

## 📋 Overview

ELMS (Employee Lifecycle Management System) is an end-to-end HRIS built with **PHP 8 + MySQL + vanilla JavaScript** that covers the entire employee lifecycle:

```
Requisition → Candidates → Screening → Interview → Salary Proposal → Offer Approval → 
Offer Letter → Candidate Response → Employee Creation → Probation Appraisal → Confirmation
```

### Key Highlights
- **9 Core Modules** covering every HR phase
- **23 Database Tables** with full referential integrity
- **45+ API Endpoints** for all CRUD operations
- **Complete Audit Trail** — every status change logged
- **Dynamic Template Engine** — generate letters with merge tags
- **Multi-level Approval Workflows** — proposals, offers, probation
- **Automated Salary Calculations** with tax integration

---

## 🏗️ Architecture

### Backend
- **PHP 8.x** with PDO/MySQL
- RESTful JSON API endpoints (`/ajax/*.php`)
- Transaction support for multi-step operations
- Lifecycle logging via `addCandidateLog()` helper

### Frontend
- Vanilla JavaScript (no framework dependencies)
- `apiGet()` / `apiPost()` utilities from `includes/ajax/common.js`
- Quill.js rich-text editor for letter composition
- Custom modal, accordion, and step-wizard components

### Key Patterns
| Pattern | Description |
|---------|-------------|
| **Shared Endpoints** | `list_offers.php` serves multiple modules with query param filters |
| **Template Engine** | `templates/ajax/render.php` resolves `{{TAG}}` placeholders via module-specific render files |
| **Step Wizard** | `employee/create.php` uses 6-step non-interactive stepper with auto-fill from candidate data |
| **Salary Auto-Calc** | Basic=57.1425%, House Rent=50% of Basic, Medical=10%, Conveyance=15%; tax via AJAX |

---

## 📦 Modules

| # | Module | Description | Key Files |
|---|--------|-------------|-----------|
| 1 | **Recruitment** | Job requisitions, candidate pipeline, status tracking | `recruitment/`, `candidates/`, `includes/ajax/list_*.php` |
| 2 | **Screening & Interview** | Multi-criteria evaluation, interview rounds with 4-dimension scoring | `screening/`, `interview/`, `screening/ajax/*.php` |
| 3 | **Salary Proposal** | Gross → breakdown auto-calc, tax via AJAX, versioned proposals | `offer_management/salary_breakdown.php`, `offer_management/proposal.php` |
| 4 | **Proposal Approval** | Multi-level approval routing, auto-create offer on finalize | `offer_management/proposal_approval.php` |
| 5 | **Offer Management** | Offer creation, multi-level approval, status tracking | `offer_management/letter.php`, `offer_management/proposal_approval.php` |
| 6 | **Offer Letter** | Template selection, Quill editor, tag resolution, preview | `offer_management/generate_letter.php`, `templates/render/offer.php` |
| 7 | **Candidate Response** | Email delivery, accept/reject recording, status sync | `offer_management/response.php`, `offer_management/ajax/letter_send.php` |
| 8 | **Employee Creation** | 6-step wizard, 70+ personal fields, 50+ salary fields | `employee/create.php`, `employee/list.php` |
| 9 | **Probation Appraisal** | Weighted categories, 6-level workflow (Self → Supervisor → Dept Head → HR → GM → MD) | `probation/`, `probation/ajax/*.php` |
| 10 | **Confirmation** | Eligible employees, 15 merge tags, approve/send workflow | `confirmation/`, `templates/render/confirmation.php` |

---

## 🗄️ Database Schema

**Database:** `elms` | **Engine:** InnoDB | **Charset:** utf8mb4

### Core Tables
- `departments`, `designations` — Org structure
- `requisitions` — Job postings
- `candidates`, `candidate_statuses`, `candidate_logs` — Applicant pipeline + audit
- `screenings`, `interview_log` — Evaluation data
- `salary_proposals` — Versioned compensation with JSON breakdown
- `offers`, `offer_approvals`, `offer_letters`, `offer_email_log` — Offer lifecycle
- `personnel_basic_info`, `salary_info` — Employee master data
- `probation_appraisals`, `probation_workflow`, `probation_scores`, `probation_cats` — Appraisal system
- `confirmation_letters` — Confirmation documents
- `letter_templates`, `template_modules`, `merge_tags` — Template engine
- `activity_log` — System-wide audit trail

### ERD
View the interactive ERD: [dbdocs.io](https://dbdocs.io/embed/68422dd3b5bd5c094ae78a66f4f5a1eb/19a9f26988464ed7a41da4f0d20ac6d5)

---

## 🚀 Quick Start

### Requirements
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache/Nginx with mod_rewrite
- Composer (optional, for dev dependencies)

### Installation

```bash
# 1. Clone/navigate to project
cd ELMS-final

# 2. Create database
mysql -u root -p -e "CREATE DATABASE elms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Import schema
mysql -u root -p elms < documents/elms v1 with seeds.sql

# 4. Configure database (if needed)
# Edit includes/config.php with your DB credentials

# 5. Set up virtual host pointing to project root
# Ensure document root is ELMS-final/
```

### Default Access
After setup, navigate to `http://localhost/` (or your configured domain)

---

## ⚙️ Configuration

Edit `includes/config.php`:

```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'elms');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');

define('BASE_URL', '/');  // Adjust if in subdirectory
```

---

## 📁 Project Structure

```
ELMS-final/
├── index.php                 # Dashboard overview
├── dashboard.php             # Main dashboard with stats & pipeline
├── includes/
│   ├── config.php           # DB & app configuration
│   ├── functions.php        # Global helpers (addCandidateLog, api helpers, etc.)
│   ├── layout.php           # Master layout (sidebar, topbar)
│   └── ajax/                # Shared AJAX endpoints
├── recruitment/             # Requisition management
├── candidates/              # Candidate CRUD
├── screening/               # Screening evaluation
├── interview/               # Interview scheduling & scoring
├── offer_management/        # Proposals, approvals, letters, responses
├── employee/                # Employee creation wizard & list
├── probation/               # Appraisal workflow
├── confirmation/            # Confirmation letters
├── templates/               # Template engine (render, CRUD, tags)
├── reports/                 # Analytics & reports
├── activity/                # System activity log
├── css/                     # Stylesheets
├── docs/                    # Documentation (HTML + Planning.md)
├── documents/               # System logic, ERD, SQL dumps
└── README.md                # This file
```

---

## 🔄 Status State Machines

### Candidate Flow
```
Applied → Screened → Shortlisted → Interviewed → Selected → Offered → Hired
    │         │            │              │
    └─────────┴────────────┴──────────────┴→ Rejected (any stage)
```

### Salary Proposal
```
Draft → Proposed → Pending_Approval → Finalized
                            ↘ Rejected → (revise) → Draft
```

### Offer
```
Pending → Sent → Accepted / Rejected
```

### Probation Appraisal
```
Draft → In_Review → Supervisor_Approved → Dept_Head_Approved → HR_Reviewed → Finalized
```

### Confirmation Letter
```
Draft → Generated → Approved → Sent
```

---

## 📚 Documentation

| File | Description |
|------|-------------|
| `docs/documentation.html` | Complete interactive docs (45-step flow, ERD, API ref) |
| `docs/Planning.md` | Feature planning & roadmap |
| `documents/system-logic.md` | Detailed phase-by-phase logic diagrams |
| `documents/database-erd.md` | Database schema documentation |
| `documents/data-flow.html` | Visual data flow diagram |

---

## 🧪 Testing

```bash
# Run demo seed data
php seed_demo.php

# Test API endpoints
php _test_api.php
```

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

---

## 📄 License

This project is proprietary software for Garments Bridge HRIS.

---

## 🙋 Support

For questions or issues, contact the development team or visit the live demo:
**[https://hris.garmentsbridge.com/v4/](https://hris.garmentsbridge.com/v4/)**