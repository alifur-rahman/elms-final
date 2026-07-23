# ELMS System Logic Diagram — Each Phase

---

## Phase 1: Requisition & Candidate Pipeline

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 1: REQUISITION → CANDIDATE PIPELINE                       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  HR Creates Requisition                                          │
│  ┌─────────────┐    ┌────────────────┐    ┌──────────────────┐  │
│  │ departments  │───→│  requisitions   │───→│   candidates     │  │
│  │ designations │    │  (job posting)  │    │  (applicants)    │  │
│  └─────────────┘    └────────────────┘    └──────┬───────────┘  │
│                                                   │              │
│  Candidate Status Tracking                        │              │
│  ┌──────────────────┐    ┌──────────────────┐     │              │
│  │ candidate_statuses│◄───│ candidate_logs   │◄────┘              │
│  │ (New,Screened,   │    │ (audit trail)    │                    │
│  │  Interview,Offer)│    └──────────────────┘                    │
│  └──────────────────┘                                           │
│                                                                  │
│  LOGIC:                                                          │
│  1. Admin creates requisition → selects dept, designation        │
│  2. Candidates apply → auto-assigned status "Applied"            │
│  3. Status changes logged to candidate_logs (from→to, notes)     │
│  4. candidates.status_id FK → candidate_statuses                 │
│  5. candidates.job_id FK → requisitions                          │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - requisitions/ajax/*.php (CRUD)                                │
│  - candidates/ajax/*.php (CRUD)                                  │
│  - includes/ajax/list_requisitions.php                           │
│  - includes/ajax/list_candidates.php                             │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 2: Screening & Interview

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 2: SCREENING → INTERVIEW                                  │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌───────────┐    ┌──────────────────────────────────────┐       │
│  │ screenings │    │          interview_log                │       │
│  ├───────────┤    ├──────────────────────────────────────┤       │
│  │ candidate_id│──→│ candidate_id, round, round_name     │       │
│  │ screened_by│   │ type, interviewer, date              │       │
│  │ date       │   │ score_technical                      │       │
│  │ rating     │   │ score_communication                  │       │
│  │ comm_score │   │ score_problem_solving                │       │
│  │ exp_match  │   │ score_attitude                       │       │
│  │ skill_match│   │ rating, decision, feedback           │       │
│  │ edu_match  │   │ is_final_round → YES/NO              │       │
│  │ result     │   └──────────────────────────────────────┘       │
│  └───────────┘                                                   │
│                                                                  │
│  LOGIC:                                                          │
│  1. HR screens candidate → result: Shortlisted / Rejected        │
│  2. screen.match fields = Match/Partial/No Match                 │
│  3. Interview rounds added (1,2,3...), each with scores          │
│  4. 4 score dimensions: Technical, Communication, Problem-Solving│
│     Attitude                                                      │
│  5. is_final_round=1 marks last round → triggers decision        │
│  6. decision: Shortlisted / Rejected / Selected                  │
│  7. Candidate status auto-updates via candidate_logs             │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - screening/ajax/*.php                                          │
│  - interview/ajax/*.php                                          │
│  - includes/ajax/list_screenings.php                             │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 3: Salary Proposal

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 3: SALARY PROPOSAL                                        │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────┐       ┌────────────────────────────┐     │
│  │ salary_proposals    │       │  ajax/proposal_create.php  │     │
│  ├────────────────────┤       └──────────┬─────────────────┘     │
│  │ candidate_id       │◄─────────────────┘                       │
│  │ proposed_salary    │                                          │
│  │ offered_salary     │    Save flow:                            │
│  │ negotiation_notes  │    1. Fetch candidate from DB            │
│  │ salary_breakdown   │◄── 2. Calculate salary components        │
│  │   {                │    3. Calculate tax via get_taxinfo_ajx   │
│  │     gross_salary,  │    4. Store as JSON in salary_breakdown   │
│  │     basic,         │    5. INSERT INTO salary_proposals        │
│  │     house_rent,    │                                          │
│  │     medical_       │    Auto-calculation:                     │
│  │     allowance,     │    Basic = Gross × 0.571425              │
│  │     conveyance,    │    House Rent = Basic × 0.5              │
│  │     total_salary,  │    Medical = Basic × 0.1                 │
│  │     personal_pf,   │    Conveyance = Basic × 0.15             │
│  │     pf_deductible, │    Total Payable = Total Salary          │
│  │     monthly_tax,   │           - Personal PF                  │
│  │     total_payable  │           - PF Deductible                │
│  │   }                │           - Monthly Tax                  │
│  │ status:            │                                          │
│  │  Draft→Proposed    │    Status flow:                          │
│  │  →Pending_Approval │    Draft → Proposed → Pending_Approval   │
│  │  →Approved/Rejected│    Pending_Approval → Approved/Rejected  │
│  │ version (auto-inc) │    Approved → Finalized                   │
│  └────────────────────┘                                          │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - offer_management/salary_breakdown.php  (UI)                   │
│  - offer_management/proposal.php          (list)                 │
│  - offer_management/ajax/proposal_create.php (save)              │
│  - offer_management/ajax/get_taxinfo_ajx.php (tax calc)         │
│  - offer_management/ajax/list_proposals.php (list data)         │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 4: Proposal Approval

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 4: PROPOSAL APPROVAL                                      │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────┐                                │
│  │   proposal_approval.php       │                                │
│  │   (list_approvals.php)       │                                │
│  ├──────────────────────────────┤                                │
│  │  View all Proposals           │                                │
│  │  ┌─────────────────────────┐ │                                │
│  │  │ salary_proposals        │ │                                │
│  │  │ JOIN candidates         │ │                                │
│  │  │ JOIN requisitions       │ │                                │
│  │  │ WHERE status =          │ │                                │
│  │  │  Pending_Approval       │ │                                │
│  │  └─────────────────────────┘ │                                │
│  └──────────────┬───────────────┘                                │
│                 │                                                │
│     ┌───────────┼───────────────┐                                │
│     ▼           ▼               ▼                                │
│  ┌────────┐ ┌────────┐    ┌──────────┐                          │
│  │  View  │ │Approve │    │  Reject  │                          │
│  │ Details│ │        │    │          │                          │
│  └────────┘ └───┬────┘    └────┬─────┘                          │
│                 │              │                                  │
│                 ▼              ▼                                  │
│  ┌──────────────────────────────────────────┐                    │
│  │  proposal_approve.php                    │                    │
│  │  - UPDATE salary_proposals SET status     │                    │
│  │  - status: Approved / Finalized           │                    │
│  │    OR Rejected                            │                    │
│  └──────────────────────────────────────────┘                    │
│                                                                  │
│  LOGIC:                                                          │
│  1. HR proposes salary → status=Pending_Approval                 │
│  2. Manager views full breakdown in modal                        │
│  3. Approve → status=Finalized → triggers offer creation         │
│  4. Reject → status=Rejected → HR can revise                     │
│  5. Version increments on each revision                          │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - offer_management/proposal_approval.php (UI)                   │
│  - offer_management/ajax/list_approvals.php (data)              │
│  - offer_management/ajax/proposal_approve.php (action)           │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 5: Offer Creation & Letter Generation

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 5: OFFER CREATION → LETTER GENERATION                     │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────┐                                          │
│  │     offers          │                                          │
│  ├────────────────────┤                                          │
│  │ candidate_id       │  Created when proposal=Finalized         │
│  │ salary             │  Links to candidate + designation + dept  │
│  │ joining_date       │                                          │
│  │ designation_id     │                                          │
│  │ dept_id            │                                          │
│  │ probation_months   │                                          │
│  │ status: Pending    │  → Sent → Accepted/Rejected              │
│  └────────┬───────────┘                                          │
│           │                                                      │
│           ▼                                                      │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │          LETTER GENERATION FLOW                             │ │
│  │                                                             │ │
│  │  letter.php                                                 │ │
│  │    │                                                        │ │
│  │    ├─→ "Generate" button → generate_letter.php?offer_id=X  │ │
│  │    │                                                        │ │
│  │    │  1. Fetch offer + candidate details                    │ │
│  │    │  2. Populate dropdown with letter_templates (type=Offer│ │
│  │    │  3. User selects template                               │ │
│  │    │  4. AJAX POST → templates/ajax/render.php              │ │
│  │    │     { module: "Offer", record_id: offerId, content }   │ │
│  │    │                                                        │ │
│  │    │  render.php:                                            │ │
│  │    │    a. Load template_modules WHERE module_name="Offer"  │ │
│  │    │    b. require_once render_file (offer.php)             │ │
│  │    │    c. Call getOfferTagMap(pdo, offerId)                │ │
│  │    │       - Queries offers + candidates + departments +    │ │
│  │    │         designations + requisitions                     │ │
│  │    │       - Fetches salary_breakdown from salary_proposals │ │
│  │    │       - Returns 12 tags: CANDIDATE_NAME, DESIGNATION,  │ │
│  │    │         JOB_TITLE, DEPARTMENT, SALARY, JOINING_DATE,   │ │
│  │    │         PROBATION_MONTHS, COMPANY_NAME, HR_NAME, DATE, │ │
│  │    │         RESPONSE_DATE, SALARY_BREAKDOWN                 │ │
│  │    │    d. preg_replace /{{TAG}}/ → value                   │ │
│  │    │    e. Returns rendered HTML                             │ │
│  │    │                                                        │ │
│  │    │  5. Rendered content loaded into Quill editor          │ │
│  │    │  6. User edits if needed                               │ │
│  │    │  7. "Generate Letter" → saveLetter()                   │ │
│  │    │     → POST ajax/letter_generate.php                    │ │
│  │    │     → INSERT INTO offer_letters (offer_id, content)    │ │
│  │    │     → Redirect to letter.php                           │ │
│  │    │                                                        │ │
│  │    ├─→ "Preview" button → letter_view.php?offer_id=X       │ │
│  │    │     → Renders letter_content in styled view            │ │
│  │    │                                                        │ │
│  │    └─→ "Send Email" button → email modal                    │ │
│  │          → POST ajax/letter_send.php                        │ │
│  │          → Updates offer.status=Sent                        │ │
│  │          → Logs to offer_email_log                          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - offer_management/letter.php             (list)                │
│  - offer_management/generate_letter.php    (generate UI)         │
│  - offer_management/ajax/letter_generate.php (save)              │
│  - offer_management/ajax/letter_view.php   (preview)             │
│  - offer_management/ajax/letter_send.php   (email)               │
│  - templates/ajax/render.php               (template engine)     │
│  - templates/render/offer.php              (tag resolver)        │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 6: Offer Response

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 6: OFFER RESPONSE                                         │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────┐    ┌────────────────────────────────┐    │
│  │     offers          │    │      offer_email_log            │    │
│  ├────────────────────┤    ├────────────────────────────────┤    │
│  │ response_status:   │    │ offer_id                       │    │
│  │  Pending →         │    │ recipient                      │    │
│  │  Accepted / Rejected│   │ subject                        │    │
│  │ responded_at       │    │ body                           │    │
│  │ response_notes     │    │ sent_at                        │    │
│  │ accepted_at        │    │ status: Sent / Failed          │    │
│  └────────────────────┘    │ error_message                  │    │
│                            └────────────────────────────────┘    │
│                                                                  │
│  LOGIC:                                                          │
│  1. Letter sent via email → logged in offer_email_log            │
│  2. Candidate responds → response_status updated                 │
│  3. If Accepted → accepted_at timestamp saved                    │
│  4. If Rejected → response_notes captured                        │
│  5. Offer status auto-updates based on response                  │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - offer_management/ajax/letter_send.php                         │
│  - offer_management/letter.php (status display)                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 7: Template Module System

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 7: TEMPLATE MODULE (Dynamic Tag Resolution)               │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────┐    ┌──────────────────────┐           │
│  │   template_modules    │    │     merge_tags        │           │
│  ├──────────────────────┤    ├──────────────────────┤           │
│  │ Offer → offer.php    │◄───│ CANDIDATE_NAME        │           │
│  │ Confirmation         │    │ DESIGNATION           │           │
│  │ Probation            │    │ JOB_TITLE             │           │
│  │ General              │    │ DEPARTMENT            │           │
│  └──────────────────────┘    │ SALARY                │           │
│                              │ JOINING_DATE           │           │
│  ┌──────────────────────┐    │ PROBATION_MONTHS      │           │
│  │  letter_templates     │    │ COMPANY_NAME          │           │
│  ├──────────────────────┤    │ HR_NAME               │           │
│  │ type → module_name    │    │ DATE                  │           │
│  │ content: HTML +       │    │ RESPONSE_DATE         │           │
│  │   {{TAG}} placeholders│    │ SALARY_BREAKDOWN      │           │
│  └──────────────────────┘    └──────────────────────┘           │
│                                                                  │
│  Render Engine: templates/ajax/render.php                        │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ Input: { module, record_id, content }                      │  │
│  │                                                            │  │
│  │ 1. SELECT render_file FROM template_modules               │  │
│  │    WHERE module_name = :module AND is_active = 1          │  │
│  │                                                            │  │
│  │ 2. require_once render_file                                │  │
│  │                                                            │  │
│  │ 3. Call get<Module>TagMap(pdo, record_id)                 │  │
│  │    → Each render file has a function that queries DB      │  │
│  │    → Returns [ 'TAG_NAME' => 'resolved_value', ... ]      │  │
│  │                                                            │  │
│  │ 4. preg_replace /{{([A-Z_][A-Z0-9_]*)}}/                 │  │
│  │    → Replaces all {{TAG}} with values from tagMap         │  │
│  │    → Unknown tags left as-is                              │  │
│  │                                                            │  │
│  │ 5. Return { rendered_content, tag_count }                  │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  Render Files:                                                   │
│  templates/render/offer.php      → getOfferTagMap()              │
│  templates/render/confirmation.php → getConfirmationTagMap()     │
│  templates/render/probation.php  → getProbationTagMap()          │
│  templates/render/general.php    → getGeneralTagMap()            │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - templates/list.php              (template CRUD UI)            │
│  - templates/tags.php              (merge tag management)        │
│  - templates/ajax/render.php       (render engine)               │
│  - templates/render/offer.php      (Offer tag resolver)          │
│  - templates/render/confirmation.php (Confirmation tag resolver) │
│  - templates/render/probation.php  (Probation tag resolver)      │
│  - templates/render/general.php    (General tag resolver)        │
│  - templates/ajax/templates_*.php  (template CRUD endpoints)     │
│  - templates/ajax/merge_tags_*.php (merge tag CRUD endpoints)    │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 8: Probation Appraisal

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 8: PROBATION APPRAISAL                                     │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────┐                                        │
│  │ probation_appraisals  │                                        │
│  ├──────────────────────┤                                        │
│  │ employee_id          │                                        │
│  │ joining_date         │                                        │
│  │ probation_months     │                                        │
│  │ end_date = join + months                                       │
│  │ review_date          │                                        │
│  │ overall_score        │                                        │
│  │ recommendation       │                                        │
│  └──────────┬───────────┘                                        │
│             │                                                    │
│     ┌───────┼──────────────┐                                     │
│     ▼       ▼              ▼                                     │
│  ┌────────────────┐  ┌──────────────────┐                        │
│  │ probation_scores│  │ probation_workflow│                        │
│  ├────────────────┤  ├──────────────────┤                        │
│  │ appraisal_id   │  │ appraisal_id     │                        │
│  │ category_id    │  │ level: Supervisor│                        │
│  │ score          │  │       → Dept Head│                        │
│  │ comments       │  │       → HR Review│                        │
│  └────────────────┘  │ status:          │                        │
│                      │  Pending→Approved│                        │
│  ┌──────────────────┐ └──────────────────┘                        │
│  │ probation_cats   │                                            │
│  ├──────────────────┤                                            │
│  │ name (category)  │                                            │
│  │ weight %         │  Categories weighted, scores calculated    │
│  └──────────────────┘  overall = Σ(score × weight)               │
│                                                                  │
│  LOGIC:                                                          │
│  1. Appraisal created when employee joins                        │
│  2. review_date set to probation end                             │
│  3. Supervisor scores each category (weighted %)                 │
│  4. Dept Head reviews → HR reviews → Final recommendation        │
│  5. recommendation: Extend / Confirm / Terminate                 │
│  6. Multi-level workflow with status tracking                    │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - probation/ajax/*.php                                          │
│  - includes/ajax/list_appraisals.php                             │
│  - includes/ajax/list_categories.php                             │
└──────────────────────────────────────────────────────────────────┘
```

---

## Phase 9: Confirmation Module

```
┌──────────────────────────────────────────────────────────────────┐
│  PHASE 9: CONFIRMATION (Post-Probation Letter Generation)        │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────┐                                    │
│  │  ELIGIBLE EMPLOYEES       │                                    │
│  ├──────────────────────────┤                                    │
│  │ Query: probation_appraisals│                                   │
│  │   JOIN personnel_basic_info│                                   │
│  │   WHERE status = 'Completed'                                   │
│  │   AND EMPLOYMENT_TYPE = 'Probationer'                         │
│  │                                           │                    │
│  │ Shows: name, code, dept, designation,     │                    │
│  │        joining_date, probation_months,     │                    │
│  │        appraisal result, salary            │                   │
│  └──────────┬───────────────┘                                    │
│             │                                                    │
│             ▼                                                    │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │          CONFIRMATION LETTER GENERATION                     │ │
│  │                                                             │ │
│  │  generate.php                                               │ │
│  │    │                                                        │ │
│  │    ├─→ Select employee from eligible list                   │ │
│  │    ├─→ Select employment type (dropdown)                    │ │
│  │    ├─→ Select template (type=Confirmation)                  │ │
│  │    │                                                        │ │
│  │    │  AJAX POST → templates/ajax/render.php                 │ │
│  │    │  { module: "Confirmation", record_id: appraisal_id }   │ │
│  │    │                                                        │ │
│  │    │  render.php:                                            │ │
│  │    │    a. Load template_modules WHERE module_name=          │ │
│  │    │       "Confirmation"                                    │ │
│  │    │    b. require_once confirmation.php                     │ │
│  │    │    c. Call getConfirmationTagMap(pdo, appraisal_id)     │ │
│  │    │       - Queries probation_appraisals +                  │ │
│  │    │         personnel_basic_info + salary_info              │ │
│  │    │       - Returns 15 tags: EMPLOYEE_NAME,                 │ │
│  │    │         EMPLOYEE_CODE, DESIGNATION, DEPARTMENT,         │ │
│  │    │         SALARY, JOINING_DATE, CONFIRMATION_DATE,        │ │
│  │    │         PROBATION_MONTHS, COMPANY_NAME, HR_NAME,        │ │
│  │    │         DATE, PHONE, EMAIL, GENDER, SALARY_BREAKDOWN    │ │
│  │    │    d. preg_replace /{{TAG}}/ → value                    │ │
│  │    │    e. Returns rendered HTML                             │ │
│  │    │                                                        │ │
│  │    │  5. Rendered content loaded into Quill editor          │ │
│  │    │  6. User edits if needed                               │ │
│  │    │  7. "Generate Letter" → AJAX letter_generate.php       │ │
│  │    │     → INSERT INTO confirmation_letters                  │ │
│  │    │       (employee_id, offer_id, appraisal_id,            │ │
│  │    │        letter_content, employment_type, status)        │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │          CONFIRMATION LETTERS LIST                          │ │
│  │                                                             │ │
│  │  confirmation_letters.php                                   │ │
│  │    │                                                        │ │
│  │    ├─→ "Preview" → letter_view.php → styled HTML view      │ │
│  │    ├─→ "Approve" → letter_approve.php                      │ │
│  │    │     → UPDATE status='Approved', approved_at=NOW()      │ │
│  │    └─→ "Send" → Send modal with auto-filled email          │ │
│  │          → letter_send.php                                  │ │
│  │          → UPDATE status='Sent', sent_at=NOW()              │ │
│  │          → Logs to offer_email_log (module='Confirmation')  │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  LOGIC:                                                          │
│  1. Probation completed → status='Completed' on appraisal        │
│  2. Eligible list filtered: Completed + Probationer type         │
│  3. Letter generated via template engine (15 merge tags)         │
│  4. Status: Draft → Generated → Approved → Sent                 │
│  5. Send modal auto-fills email from PBI_EMAIL                   │
│  6. Confirmation date = joining_date + probation_months           │
│                                                                  │
│  FILES INVOLVED:                                                 │
│  - confirmation/list.php            (eligible employees)        │
│  - confirmation/generate.php        (letter gen UI)             │
│  - confirmation/confirmation_letters.php (letter list)          │
│  - confirmation/ajax/migrate.php    (data migration)            │
│  - confirmation/ajax/list_eligible.php (eligible data)          │
│  - confirmation/ajax/letter_generate.php (save)                 │
│  - confirmation/ajax/list_letters.php (list data)               │
│  - confirmation/ajax/letter_view.php  (preview)                 │
│  - confirmation/ajax/letter_approve.php (approve)               │
│  - confirmation/ajax/letter_send.php  (send email)              │
│  - confirmation/ajax/render_template.php (template render)      │
│  - templates/render/confirmation.php  (tag resolver)            │
│  - templates/ajax/seed_confirmation.php (default template)      │
└──────────────────────────────────────────────────────────────────┘
```

---

## Data Flow Summary

```
┌─────────────┐     ┌──────────────┐     ┌───────────────┐
│ Requisition │────→│  Candidates   │────→│  Screening    │
│ (Job Post)  │     │ (Applicants) │     │  & Interview  │
└─────────────┘     └──────────────┘     └───────┬───────┘
                                                 │
                                                 ▼
┌─────────────┐     ┌──────────────┐     ┌───────────────┐
│  Offer      │◄────│  Proposal    │◄────│   Salary      │
│  Response   │     │  Approval    │     │   Proposal    │
└──────┬──────┘     └──────────────┘     └───────────────┘
       │
       ▼
┌─────────────┐     ┌──────────────┐
│  Letter     │────→│  Email       │
│  Generation │     │  Delivery    │
│  (Templates)│     │              │
└──────┬──────┘     └──────────────┘
       │
       ▼
┌─────────────┐     ┌──────────────┐     ┌───────────────┐
│  Employee   │────→│  Probation   │────→│ Confirmation  │
│  Creation   │     │  Appraisal   │     │  Letter Gen   │
│  (Wizard)   │     │  (Scoring)   │     │  (Template)   │
└─────────────┘     └──────────────┘     └───────────────┘
```

---

## Status State Machines

### Candidate Status Flow
```
Applied → Screened → Shortlisted → Interviewed → Selected → Offered → Hired
    │         │            │              │
    └─────────┴────────────┴──────────────┴→ Rejected (at any stage)
```

### Salary Proposal Status Flow
```
Draft → Proposed → Pending_Approval → Finalized
                                  ↘ Rejected → (can revise) → Draft
```

### Offer Status Flow
```
Pending → Sent → Accepted / Rejected
```

### Letter Status Flow (Offer)
```
Draft → Generated → Sent
```

### Probation Appraisal Status Flow
```
Draft → In_Review → Supervisor_Approved → Dept_Head_Approved → HR_Reviewed → Finalized
```

### Confirmation Letter Status Flow
```
Draft → Generated → Approved → Sent
```
