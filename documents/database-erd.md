# ELMS Database — Full Entity Relationship Diagram

**Database:** `elms` | **Engine:** InnoDB | **Charset:** utf8mb4

---

## All Tables (23)

| # | Table | Rows | Module |
|---|-------|------|--------|
| 1 | `departments` | 8 | Recruitment |
| 2 | `designations` | 10 | Recruitment |
| 3 | `candidate_statuses` | 8 | Recruitment |
| 4 | `requisitions` | 16 | Recruitment |
| 5 | `candidates` | 64 | Recruitment |
| 6 | `candidate_logs` | 64 | Recruitment |
| 7 | `screenings` | 9 | Screening |
| 8 | `interview_log` | 13 | Screening |
| 9 | `salary_proposals` | 5 | Offer Mgmt |
| 10 | `offers` | 1 | Offer Mgmt |
| 11 | `offer_letters` | 0 | Offer Mgmt |
| 12 | `offer_approvals` | 0 | Offer Mgmt |
| 13 | `offer_email_log` | 1 | Offer Mgmt |
| 14 | `letter_templates` | 3 | Templates |
| 15 | `merge_tags` | 34 | Templates |
| 16 | `template_modules` | 4 | Templates |
| 17 | `probation_appraisals` | 2 | Probation |
| 18 | `probation_categories` | 10 | Probation |
| 19 | `probation_scores` | 59 | Probation |
| 20 | `probation_workflow` | 12 | Probation |
| 21 | `personnel_basic_info` | — | Employee |
| 22 | `salary_info` | — | Employee |
| 23 | `confirmation_letters` | — | Confirmation |

---

## Entity Relationship Diagram

```
┌─────────────────────┐      ┌──────────────────────┐
│    departments       │      │    candidate_statuses │
├─────────────────────┤      ├──────────────────────┤
│ PK id               │      │ PK id                │
│    name             │      │    code    (UNI)     │
│    code             │      │    name              │
└────────┬────────────┘      │    sort_order        │
         │                   │    description       │
         │ 1:N               └────────┬─────────────┘
         │                            │ 1:N
    ┌────┴──────────────┐        ┌────┴──────────────────┐
    │    designations    │        │      candidates        │
    ├───────────────────┤        ├───────────────────────┤
    │ PK id             │        │ PK id                 │
    │    name           │        │    code               │
    │    code           │        │    first_name         │
    │ FK dept_id ───────┼──┐     │    last_name          │
    └───────────────────┘  │     │    email              │
                           │     │    phone              │
                           │     │    nid                │
                           │     │    blood_group        │
                           │     │    dob                │
                           │     │    gender             │
                           │     │    present_address    │
                           │     │    permanent_address  │
                           │     │    current_salary     │
                           │     │    expected_salary    │
┌─────────────────────┐   │     │ FK status_id ─────────┼──┐
│    requisitions      │   │     │ FK job_id ────────────┼──┼──→ requisitions(id)
├─────────────────────┤   │     │    applied_at         │  │
│ PK id               │   │     │    created_at         │  │
│    title            │   │     │    updated_at         │  │
│ FK dept_id ─────────┼───┘     └───────────┬───────────┘  │
│    location         │                     │               │
│    type             │                     │               │
│    vacancies        │                     │               │
│    deadline         │                     │               │
│    education        │                     │               │
│    experience       │                     │               │
│    salary_min       │                     │               │
│    salary_max       │                     │               │
│    skills           │                     │               │
│    description      │                     │               │
│    responsibilities │                     │               │
│    benefits         │                     │               │
│    priority         │                     │               │
│    status           │                     │               │
│    created_at       │                     │               │
│    updated_at       │                     │               │
└─────────────────────┘                     │               │
                                            │               │
┌─────────────────────┐                     │               │
│   candidate_logs     │                     │               │
├─────────────────────┤                     │               │
│ PK id               │                     │               │
│ FK candidate_id ─────┼─────────────────────┘               │
│    action           │                                     │
│    from_status      │                                     │
│    to_status        │                                     │
│    notes            │                                     │
│    created_by       │                                     │
│    created_at       │                                     │
└─────────────────────┘                                     │
                                                            │
┌─────────────────────┐                                     │
│    screenings        │                                     │
├─────────────────────┤                                     │
│ PK id               │                                     │
│ FK candidate_id ─────┼─────────────────────────────────────┘
│    screened_by      │
│    date             │
│    rating           │
│    communication_score
│    experience_match │
│    skill_match      │
│    education_match  │
│    remarks          │
│    result           │
│    created_at       │
│    updated_at       │
└─────────────────────┘

┌─────────────────────────┐
│      interview_log       │
├─────────────────────────┤
│ PK id                   │
│ FK candidate_id ─────────┼──→ candidates(id)
│    round                │
│    round_name           │
│    type                 │
│    interviewer          │
│    date                 │
│    status               │
│    score_technical      │
│    score_communication  │
│    score_problem_solving│
│    score_attitude       │
│    rating               │
│    decision             │
│    reject_reason        │
│    feedback             │
│    is_final_round       │
│    created_at           │
│    updated_at           │
└─────────────────────────┘

┌───────────────────────────┐
│    salary_proposals        │
├───────────────────────────┤
│ PK id                     │
│ FK candidate_id ───────────┼──→ candidates(id)
│    proposed_salary        │
│    offered_salary         │
│    negotiation_notes      │
│    salary_breakdown (JSON)│
│    status                 │
│    version                │
│    created_by             │
│    finalized_at           │
│    created_at             │
│    updated_at             │
└───────────────────────────┘

┌────────────────────────────┐           ┌────────────────────────────┐
│          offers             │           │     offer_approvals         │
├────────────────────────────┤           ├────────────────────────────┤
│ PK id                      │      1:N  │ PK id                      │
│ FK candidate_id ────────────┼──┐       │ FK offer_id ────────────────┼──┐
│ FK designation_id ──────────┼──┼──┐    │    level                   │  │
│ FK dept_id ─────────────────┼──┼──┼──┐ │    approver                │  │
│    salary                  │  │  │  │ │    status                   │  │
│    joining_date            │  │  │  │ │    comment                  │  │
│    probation_months        │  │  │  │ │    created_at               │  │
│    notes                   │  │  │  │ │    updated_at               │  │
│    status                  │  │  │  │ └────────────────────────────┘  │
│    response_status         │  │  │  │                                  │
│    responded_at            │  │  │  │  ┌────────────────────────────┐  │
│    response_notes          │  │  │  │  │     offer_letters          │  │
│    sent_at                 │  │  │  │  ├────────────────────────────┤  │
│    accepted_at             │  │  │  │  PK id                      │  │
│    created_at              │  │  │  │  FK offer_id ────────────────┼──┼──┘
│    updated_at              │  │  │  │  letter_content (LONGTEXT)  │  │
└────────────────────────────┘  │  │  │  letter_format              │  │
                                 │  │  │  generated_at               │  │
                                 │  │  │  sent_at                    │  │
                                 │  │  │  sent_to_email              │  │
                                 │  │  │  status                     │  │
                                 │  │  │  created_at                 │  │
                                 │  │  │  updated_at                 │  │
                                 │  │  └────────────────────────────┘  │
                                 │  │                                  │
                                 │  │  ┌────────────────────────────┐  │
                                 │  │  │     offer_email_log        │  │
                                 │  │  ├────────────────────────────┤  │
                                 │  │  PK id                      │  │
                                 │  │  FK offer_id ────────────────┼──┘
                                 │  │  recipient                   │
                                 │  │  subject                     │
                                 │  │  body                        │
                                 │  │  sent_at                     │
                                 │  │  status                      │
                                 │  │  error_message               │
                                 │  └────────────────────────────┘
                                 │
         ┌───────────────────────┘
         │
         ▼
┌────────────────────────────────────────────────────────────────────────────┐
│                        TEMPLATE SYSTEM                                     │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                            │
│  ┌──────────────────────┐    ┌──────────────────────┐                     │
│  │   template_modules    │    │     merge_tags        │                     │
│  ├──────────────────────┤    ├──────────────────────┤                     │
│  │ PK id                │    │ PK id                │                     │
│  │    module_name (UNI) │    │    type              │                     │
│  │    label             │    │    tag               │                     │
│  │    render_file       │◄───│    label             │                     │
│  │    description       │    │    description       │                     │
│  │    is_active         │    │    sort_order        │                     │
│  │    created_at        │    │    created_at        │                     │
│  │    updated_at        │    │    updated_at        │                     │
│  └──────────────────────┘    └──────────────────────┘                     │
│                                                                            │
│  ┌──────────────────────┐                                                  │
│  │  letter_templates     │                                                  │
│  ├──────────────────────┤                                                  │
│  │ PK id                │                                                  │
│  │    name              │                                                  │
│  │    type (→ module)   │                                                  │
│  │    content (HTML)    │                                                  │
│  │    status            │                                                  │
│  │    created_at        │                                                  │
│  │    updated_at        │                                                  │
│  └──────────────────────┘                                                  │
└────────────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────────────┐
│                       PROBATION MODULE                                     │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                            │
│  ┌──────────────────────┐    ┌──────────────────────┐                     │
│  │ probation_appraisals  │    │ probation_categories  │                     │
│  ├──────────────────────┤    ├──────────────────────┤                     │
│  │ PK id                │    │ PK id                │                     │
│  │    employee_id       │    │    name              │                     │
│  │    joining_date      │    │    weight            │                     │
│  │    probation_months  │    │    sort_order        │                     │
│  │    end_date          │    │    status            │                     │
│  │    review_date       │    │    created_at        │                     │
│  │    status            │    └──────────┬───────────┘                     │
│  │    overall_score     │               │                                 │
│  │    recommendation    │               │ 1:N                             │
│  │    remarks           │    ┌──────────┴───────────┐                     │
│  │    supervisor_comments│    │  probation_scores    │                     │
│  │    dept_head_comments│    ├──────────────────────┤                     │
│  │    hr_review         │    │ PK id                │                     │
│  │    created_by        │    │ FK appraisal_id ─────┼──┐                  │
│  │    created_at        │    │ FK category_id ──────┼──┼──→ categories    │
│  │    updated_at        │    │    score             │  │                  │
│  └──────────┬───────────┘    │    comments          │  │                  │
│             │                │    created_at        │  │                  │
│             │ 1:N            └──────────────────────┘  │                  │
│             │                                          │                  │
│  ┌──────────┴───────────┐                              │                  │
│  │  probation_workflow   │                              │                  │
│  ├──────────────────────┤                              │                  │
│  │ PK id                │                              │                  │
│  │ FK appraisal_id ─────┼──────────────────────────────┘                  │
│  │    level             │                                                  │
│  │    status            │                                                  │
│  │    approved_by       │                                                  │
│  │    comments          │                                                  │
│  │    action_at         │                                                  │
│  │    created_at        │                                                  │
│  └──────────────────────┘                                                  │
└────────────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────────────┐
│                       EMPLOYEE MODULE                                      │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                            │
│  ┌──────────────────────────┐    ┌──────────────────────────┐             │
│  │  personnel_basic_info     │    │      salary_info          │             │
│  ├──────────────────────────┤    ├──────────────────────────┤             │
│  │ PK PBI_ID                │    │ PK id                     │             │
│  │    PBI_CODE              │    │ FK PBI_ID ────────────────┼──┐          │
│  │    PBI_NAME              │    │ FK offer_id ──────────────┼──┼──→ offers│
│  │    last_name             │    │    basic_salary           │  │          │
│  │    PBI_DEPARTMENT        │    │    house_rent             │  │          │
│  │    PBI_DESIGNATION       │    │    medical_allowance      │  │          │
│  │    PBI_DOJ               │    │    convenience            │  │          │
│  │    PBI_SEX               │    │    gross_salary           │  │          │
│  │    PBI_EMAIL             │    │    total_salary           │  │          │
│  │    PBI_MOBILE            │    │    total_payable          │  │          │
│  │    EMPLOYMENT_TYPE       │    │    pf, income_tax         │  │          │
│  │    employee_type         │    │    bank_name, ac_no       │  │          │
│  │ FK candidate_id ─────────┼──┐ │    pf_applicable          │  │          │
│  │ FK offer_id ─────────────┼──┼─┤    pf_deductible          │  │          │
│  │    ... (100+ columns)    │  │ │    tax_status, pf_status  │  │          │
│  └──────────────────────────┘  │ └──────────────────────────┘  │          │
│                                │                                 │          │
│  ┌──────────────────────────┐  │                                 │          │
│  │   confirmation_letters    │  │                                 │          │
│  ├──────────────────────────┤  │                                 │          │
│  │ PK id                    │  │                                 │          │
│  │ FK employee_id ──────────┼──┘                                 │          │
│  │    offer_id              │                                    │          │
│  │ FK appraisal_id ─────────┼──→ probation_appraisals            │          │
│  │    letter_content        │                                    │          │
│  │    employment_type       │                                    │          │
│  │    status: Draft →       │                                    │          │
│  │      Generated →         │                                    │          │
│  │      Approved → Sent     │                                    │          │
│  │    generated_at          │                                    │          │
│  │    approved_at           │                                    │          │
│  │    sent_at               │                                    │          │
│  │    sent_to_email         │                                    │          │
│  └──────────────────────────┘                                    │          │
└────────────────────────────────────────────────────────────────────────────┘
```

---

## Full Table Schemas

### Module 1: Recruitment / HR

#### `departments` (8 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| name | varchar(255) | NO | | NULL | |
| code | varchar(50) | YES | | NULL | |

#### `designations` (10 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| name | varchar(100) | NO | | NULL | |
| code | varchar(20) | YES | | NULL | |
| dept_id | int(11) | NO | MUL | NULL | |

**FK:** `dept_id` -> `departments(id)`

#### `candidate_statuses` (8 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| code | varchar(30) | NO | UNI | NULL | |
| name | varchar(50) | NO | | NULL | |
| sort_order | int(11) | NO | | 0 | |
| description | text | YES | | NULL | |

#### `requisitions` (16 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| title | varchar(255) | NO | | NULL | |
| dept_id | int(11) | NO | MUL | NULL | |
| location | varchar(255) | YES | | NULL | |
| type | varchar(100) | NO | | Full Time | |
| vacancies | int(11) | NO | | 1 | |
| deadline | date | YES | | NULL | |
| education | varchar(100) | YES | | NULL | |
| experience | decimal(4,1) | YES | | 0.0 | |
| salary_min | decimal(12,2) | YES | | 0.00 | |
| salary_max | decimal(12,2) | YES | | 0.00 | |
| skills | text | YES | | NULL | |
| description | text | YES | | NULL | |
| responsibilities | text | YES | | NULL | |
| benefits | text | YES | | NULL | |
| priority | varchar(20) | NO | | Medium | |
| status | varchar(20) | NO | | Active | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `dept_id` -> `departments(id)`

#### `candidates` (64 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| job_id | int(11) | NO | MUL | NULL | |
| code | varchar(30) | NO | | NULL | |
| first_name | varchar(100) | NO | | NULL | |
| last_name | varchar(100) | NO | | NULL | |
| email | varchar(100) | NO | | NULL | |
| phone | varchar(30) | YES | | NULL | |
| nid | varchar(50) | YES | | NULL | |
| blood_group | varchar(10) | YES | | NULL | |
| dob | date | YES | | NULL | |
| gender | varchar(10) | YES | | NULL | |
| present_address | text | YES | | NULL | |
| permanent_address | text | YES | | NULL | |
| current_salary | decimal(12,2) | YES | | 0.00 | |
| expected_salary | decimal(12,2) | YES | | 0.00 | |
| status_id | int(11) | NO | MUL | 1 | |
| applied_at | datetime | YES | | current_timestamp() | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `job_id` -> `requisitions(id)`, `status_id` -> `candidate_statuses(id)`

#### `candidate_logs` (64 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| candidate_id | int(11) | NO | MUL | NULL | |
| action | varchar(50) | NO | | NULL | |
| from_status | varchar(30) | YES | | NULL | |
| to_status | varchar(30) | YES | | NULL | |
| notes | text | YES | | NULL | |
| created_by | varchar(100) | YES | | Admin User | |
| created_at | timestamp | NO | | current_timestamp() | |

**FK:** `candidate_id` -> `candidates(id)`

---

### Module 2: Screening & Interview

#### `screenings` (9 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| candidate_id | int(11) | NO | MUL | NULL | |
| screened_by | varchar(100) | YES | | Admin User | |
| date | date | YES | | NULL | |
| rating | decimal(3,1) | YES | | NULL | |
| communication_score | decimal(3,1) | YES | | NULL | |
| experience_match | varchar(20) | YES | | NULL | |
| skill_match | varchar(20) | YES | | NULL | |
| education_match | varchar(20) | YES | | NULL | |
| remarks | text | YES | | NULL | |
| result | varchar(30) | NO | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `candidate_id` -> `candidates(id)`

#### `interview_log` (13 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| candidate_id | int(11) | NO | MUL | NULL | |
| round | int(11) | NO | | NULL | |
| round_name | varchar(100) | YES | | NULL | |
| type | varchar(50) | NO | | NULL | |
| interviewer | varchar(100) | NO | | NULL | |
| date | datetime | NO | | NULL | |
| status | varchar(50) | YES | | Waiting for Feedback | |
| score_technical | decimal(3,1) | YES | | NULL | |
| score_communication | decimal(3,1) | YES | | NULL | |
| score_problem_solving | decimal(3,1) | YES | | NULL | |
| score_attitude | decimal(3,1) | YES | | NULL | |
| rating | decimal(3,1) | YES | | NULL | |
| decision | varchar(20) | YES | | NULL | |
| reject_reason | text | YES | | NULL | |
| feedback | text | YES | | NULL | |
| is_final_round | tinyint(1) | YES | | 0 | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `candidate_id` -> `candidates(id)`

---

### Module 3: Offer Management

#### `salary_proposals` (5 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| candidate_id | int(11) | NO | MUL | NULL | |
| proposed_salary | decimal(12,2) | NO | | NULL | |
| offered_salary | decimal(12,2) | YES | | NULL | |
| negotiation_notes | text | YES | | NULL | |
| salary_breakdown | text | YES | | NULL | |
| status | varchar(20) | NO | | Draft | |
| version | int(11) | NO | | 1 | |
| created_by | varchar(100) | YES | | Admin User | |
| finalized_at | datetime | YES | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `candidate_id` -> `candidates(id)`

> Note: `salary_breakdown` stores JSON with keys: `gross_salary`, `basic`, `house_rent`, `medical_allowance`, `conveyance_allowance`, `total_salary`, `personal_pf`, `company_pf`, `pf_deductible`, `monthly_income_tax`, `total_payable`, `gender`, `pf_applicable`, `bank_name`, `account_no`, etc.

#### `offers` (1 row)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| candidate_id | int(11) | NO | MUL | NULL | |
| salary | decimal(12,2) | NO | | NULL | |
| joining_date | date | NO | | NULL | |
| designation_id | int(11) | YES | MUL | NULL | |
| dept_id | int(11) | YES | MUL | NULL | |
| probation_months | int(11) | YES | | 6 | |
| notes | text | YES | | NULL | |
| status | varchar(20) | NO | | Pending | |
| response_status | varchar(20) | YES | | Pending | |
| responded_at | datetime | YES | | NULL | |
| response_notes | text | YES | | NULL | |
| sent_at | datetime | YES | | current_timestamp() | |
| accepted_at | datetime | YES | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `candidate_id` -> `candidates(id)`, `designation_id` -> `designations(id)`, `dept_id` -> `departments(id)`

#### `offer_letters` (0 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| offer_id | int(11) | NO | MUL | NULL | |
| letter_content | longtext | YES | | NULL | |
| letter_format | varchar(10) | YES | | html | |
| generated_at | datetime | YES | | NULL | |
| sent_at | datetime | YES | | NULL | |
| sent_to_email | varchar(100) | YES | | NULL | |
| status | varchar(20) | NO | | Draft | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `offer_id` -> `offers(id)`

#### `offer_approvals` (0 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| offer_id | int(11) | NO | MUL | NULL | |
| level | int(11) | NO | | 1 | |
| approver | varchar(100) | YES | | Admin User | |
| status | varchar(20) | NO | | Pending | |
| comment | text | YES | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `offer_id` -> `offers(id)`

#### `offer_email_log` (1 row)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| offer_id | int(11) | NO | MUL | NULL | |
| recipient | varchar(100) | NO | | NULL | |
| subject | varchar(255) | NO | | NULL | |
| body | longtext | YES | | NULL | |
| sent_at | datetime | YES | | current_timestamp() | |
| status | varchar(20) | NO | | Sent | |
| error_message | text | YES | | NULL | |

**FK:** `offer_id` -> `offers(id)`

---

### Module 4: Templates

#### `template_modules` (4 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| module_name | varchar(50) | NO | UNI | NULL | |
| label | varchar(100) | NO | | NULL | |
| render_file | varchar(255) | NO | | (empty) | |
| description | text | YES | | NULL | |
| is_active | tinyint(1) | YES | | 1 | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**Modules:** Offer, Confirmation, Probation, General

#### `merge_tags` (34 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| type | varchar(50) | NO | MUL | Offer | |
| tag | varchar(100) | NO | MUL | NULL | |
| label | varchar(255) | NO | | NULL | |
| description | text | YES | | NULL | |
| sort_order | int(11) | NO | | 0 | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**Unique:** `(tag, type)`

#### `letter_templates` (3 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| name | varchar(255) | NO | | NULL | |
| type | varchar(50) | NO | | Offer | |
| content | longtext | NO | | NULL | |
| status | varchar(20) | NO | | Active | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

---

### Module 5: Probation

#### `probation_appraisals` (2 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| employee_id | int(11) | NO | | NULL | |
| joining_date | date | YES | | NULL | |
| probation_months | int(11) | NO | | 6 | |
| end_date | date | YES | | NULL | |
| review_date | date | YES | | NULL | |
| status | varchar(20) | NO | | Draft | |
| overall_score | decimal(5,2) | YES | | NULL | |
| recommendation | varchar(30) | YES | | NULL | |
| remarks | text | YES | | NULL | |
| supervisor_comments | text | YES | | NULL | |
| dept_head_comments | text | YES | | NULL | |
| hr_review | text | YES | | NULL | |
| created_by | varchar(100) | YES | | Admin User | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

#### `probation_categories` (10 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| name | varchar(100) | NO | | NULL | |
| weight | decimal(5,2) | NO | | 0.00 | |
| sort_order | int(11) | NO | | 0 | |
| status | varchar(20) | NO | | Active | |
| created_at | timestamp | NO | | current_timestamp() | |

#### `probation_scores` (59 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| appraisal_id | int(11) | NO | MUL | NULL | |
| category_id | int(11) | NO | MUL | NULL | |
| score | decimal(5,2) | NO | | 0.00 | |
| comments | text | YES | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | |

**FK:** `appraisal_id` -> `probation_appraisals(id)`, `category_id` -> `probation_categories(id)`

#### `probation_workflow` (12 rows)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| appraisal_id | int(11) | NO | MUL | NULL | |
| level | varchar(50) | NO | | NULL | |
| status | varchar(20) | NO | | Pending | |
| approved_by | varchar(100) | YES | | NULL | |
| comments | text | YES | | NULL | |
| action_at | datetime | YES | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | |

**FK:** `appraisal_id` -> `probation_appraisals(id)`

---

### Module 6: Employee (personnel_basic_info + salary_info)

#### `personnel_basic_info` (Employee Master)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| PBI_ID | bigint(20) | NO | PRI | NULL | auto_increment |
| PBI_CODE | varchar(100) | NO | | NULL | |
| MACHINE_ID | varchar(50) | NO | | NULL | |
| PBI_DUE_DOJ | date | NO | | NULL | |
| PBI_NAME | varchar(50) | YES | | NULL | |
| PBI_FATHER_NAME | varchar(50) | YES | | NULL | |
| PBI_MOTHER_NAME | varchar(50) | YES | | NULL | |
| DEPT_ID | int(10) | YES | | NULL | |
| PBI_DEPARTMENT | int(10) | YES | | NULL | |
| nid | bigint(20) | YES | | NULL | |
| employee_type | varchar(20) | NO | | Non Roster | |
| DESG_ID | int(10) | YES | | NULL | |
| PBI_DESIGNATION | varchar(50) | YES | | NULL | |
| PBI_DOJ | date | YES | | NULL | |
| PBI_DOB | date | YES | | NULL | |
| PBI_SEX | varchar(10) | YES | | NULL | |
| PBI_MARITAL_STA | varchar(12) | YES | | NULL | |
| PBI_RELIGION | varchar(12) | YES | | NULL | |
| PBI_NATIONALITY | varchar(50) | YES | | NULL | |
| PBI_PERMANENT_ADD | varchar(250) | YES | | NULL | |
| PBI_PRESENT_ADD | varchar(250) | YES | | NULL | |
| PBI_PHONE | varchar(50) | YES | | NULL | |
| PBI_MOBILE | varchar(50) | YES | | NULL | |
| PBI_EMAIL | varchar(50) | YES | | NULL | |
| PBI_BLOOD_GROUP | varchar(32) | NO | | | |
| EMPLOYMENT_TYPE | varchar(70) | NO | | | |
| candidate_id | int(11) | YES | MUL | NULL | |
| offer_id | int(11) | YES | MUL | NULL | |
| last_name | varchar(100) | NO | | | |
| job_title | varchar(100) | NO | | | |
| ... | (100+ columns) | | | | |

> Note: `personnel_basic_info` is the employee master table. Key fields: `PBI_ID` (PK), `PBI_CODE` (employee code, e.g. EMP-00001), `EMPLOYMENT_TYPE` (Casual, Apprentice, Probationer, Temporary, Replace, Permanent, Seasonal, Contractual, Regular), `candidate_id` (FK to candidates), `offer_id` (FK to offers).

**FK:** `candidate_id` -> `candidates(id)`, `offer_id` -> `offers(id)`

#### `salary_info` (Employee Salary Breakdown)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| PBI_ID | int(11) | NO | MUL | NULL | |
| offer_id | int(11) | NO | MUL | NULL | |
| salary_type | varchar(255) | NO | | | |
| basic_salary | decimal(15,2) | NO | | 0.00 | |
| house_rent | decimal(20,2) | NO | | 0.00 | |
| medical_allowance | decimal(20,2) | NO | | 0.00 | |
| convenience | decimal(20,2) | NO | | 0.00 | |
| gross_salary | int(11) | NO | | 0 | |
| total_salary | int(11) | NO | | 0 | |
| total_payable | decimal(20,0) | NO | | 0 | |
| m_income_tex | int(100) | NO | | 0 | |
| pf | decimal(20,2) | NO | | 0.00 | |
| pf_applicable | enum('Yes','No') | NO | | No | |
| pf_deductible | enum('Yes','No') | NO | | No | |
| income_tax | decimal(20,2) | NO | | 0.00 | |
| cash_bank | varchar(32) | NO | | | |
| ac_no | varchar(255) | NO | | | |
| bank_name | int(11) | NO | | 0 | |
| tax_status | varchar(100) | NO | | | |
| pf_status | varchar(100) | NO | | | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

**FK:** `PBI_ID` -> `personnel_basic_info(PBI_ID)`, `offer_id` -> `offers(id)`

---

### Module 7: Confirmation

#### `confirmation_letters` (Confirmation Letter Generation)
| Field | Type | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id | int(11) | NO | PRI | NULL | auto_increment |
| employee_id | int(11) | NO | | NULL | |
| offer_id | int(11) | YES | | NULL | |
| appraisal_id | int(11) | YES | | NULL | |
| letter_content | longtext | YES | | NULL | |
| letter_format | varchar(10) | YES | | html | |
| employment_type | varchar(50) | YES | | Probationer | |
| status | varchar(20) | NO | | Draft | |
| generated_at | datetime | YES | | NULL | |
| approved_at | datetime | YES | | NULL | |
| approved_by | varchar(100) | YES | | NULL | |
| sent_at | datetime | YES | | NULL | |
| sent_to_email | varchar(100) | YES | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | |
| updated_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |

> Note: `employee_id` stores `personnel_basic_info.PBI_ID`. `appraisal_id` stores `probation_appraisals.id`. Status flow: Draft → Generated → Approved → Sent.

**FK:** `employee_id` -> `personnel_basic_info(PBI_ID)`, `appraisal_id` -> `probation_appraisals(id)`

---

## Foreign Key Map (20 constraints)

| Parent Table | Child Table | FK Column | References |
|---|---|---|---|
| `departments` | `designations` | `dept_id` | `departments(id)` |
| `departments` | `requisitions` | `dept_id` | `departments(id)` |
| `departments` | `offers` | `dept_id` | `departments(id)` |
| `requisitions` | `candidates` | `job_id` | `requisitions(id)` |
| `candidate_statuses` | `candidates` | `status_id` | `candidate_statuses(id)` |
| `candidates` | `candidate_logs` | `candidate_id` | `candidates(id)` |
| `candidates` | `screenings` | `candidate_id` | `candidates(id)` |
| `candidates` | `interview_log` | `candidate_id` | `candidates(id)` |
| `candidates` | `salary_proposals` | `candidate_id` | `candidates(id)` |
| `candidates` | `offers` | `candidate_id` | `candidates(id)` |
| `designations` | `offers` | `designation_id` | `designations(id)` |
| `offers` | `offer_letters` | `offer_id` | `offers(id)` |
| `offers` | `offer_approvals` | `offer_id` | `offers(id)` |
| `offers` | `offer_email_log` | `offer_id` | `offers(id)` |
| `probation_appraisals` | `probation_scores` | `appraisal_id` | `probation_appraisals(id)` |
| `probation_categories` | `probation_scores` | `category_id` | `probation_categories(id)` |
| `probation_appraisals` | `probation_workflow` | `appraisal_id` | `probation_appraisals(id)` |
| `candidates` | `personnel_basic_info` | `candidate_id` | `candidates(id)` |
| `offers` | `personnel_basic_info` | `offer_id` | `offers(id)` |
| `personnel_basic_info` | `salary_info` | `PBI_ID` | `personnel_basic_info(PBI_ID)` |
| `offers` | `salary_info` | `offer_id` | `offers(id)` |
| `personnel_basic_info` | `confirmation_letters` | `employee_id` | `personnel_basic_info(PBI_ID)` |
| `probation_appraisals` | `confirmation_letters` | `appraisal_id` | `probation_appraisals(id)` |
