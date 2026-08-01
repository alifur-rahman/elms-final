# Offer Letter Email with Response Link — Implementation Plan

## Overview
Add a system to send offer letters via email with a unique, time-bound response link that candidates can use to Accept or Decline the offer. After submission, the link becomes inactive and shows a contact-us info card.

> ✅ **DONE — Template Tag `{{RESPONSE_LINK}}` implemented + public `respond.php` built.**
> The unique link is now a merge tag usable directly in offer letter templates, and the candidate-facing response page is functional. Remaining work is the send-email + deadline modal flow (items 5–6 below).

---

## Database Changes

### New Table: `offer_response_tokens` ✅ DONE (created)

```sql
CREATE TABLE IF NOT EXISTS offer_response_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    offer_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    deadline_date DATE NOT NULL,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_used TINYINT(1) DEFAULT 0,
    used_at DATETIME NULL,
    response_status VARCHAR(20) NULL,
    response_notes TEXT NULL,
    candidate_ip VARCHAR(45) NULL,
    candidate_user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ort_offer FOREIGN KEY (offer_id) REFERENCES offers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Modify `offers` Table ✅ DONE (both columns added)

```sql
ALTER TABLE offers ADD COLUMN response_token VARCHAR(64) NULL UNIQUE AFTER response_notes;
ALTER TABLE offers ADD COLUMN response_deadline DATE NULL AFTER response_token;
```

---

## Files Created / Modified — STATUS

### Created ✅
| File | Purpose | Status |
|------|---------|--------|
| `offer_management/respond.php` | Public page — candidate views offer & submits Accept/Decline via unique token link | ✅ Built |
| `offer_management/ajax/send_letter_email.php` | Generates token, builds response link, sends email, stores deadline | ⏳ Pending |

### Modified ✅
| File | Change | Status |
|------|--------|--------|
| `templates/render/offer.php` | Added `RESPONSE_LINK` to `getOfferTagMap()` | ✅ Done |
| `offer_management/ajax/render_template.php` | Added `RESPONSE_LINK` to inline tag map (live preview) | ✅ Done |
| `templates/ajax/migrate_tags.php` | Added `RESPONSE_LINK` to seed (fresh installs) | ✅ Done |
| `includes/functions.php` | Added `appBaseUrl()`, `getOfferResponseToken()`, `getOfferResponseLink()` | ✅ Done |
| `merge_tags` table | Registered `RESPONSE_LINK` tag (type=Offer, sort_order=13) | ✅ Done |
| `offer_management/letter.php` | Update Send modal — add deadline date picker, change to 2-step flow | ⏳ Pending |
| `js/page_js/letter.js` | Update `openSendEmail()` — add deadline field; update `submitSendEmail()` — call new endpoint | ⏳ Pending |
| `offer_management/ajax/letter_send.php` | Add token generation, embed link in email body, insert into offer_response_tokens | ⏳ Pending |

> **Note:** `validate_token.php` was NOT created as a separate endpoint — token validation logic is inlined in `respond.php` (it checks existence, deadline expiry, and `is_used` flag directly).

---

## Detailed Flow

### Step 1 — Send Button + Deadline Modal

```
letter.php  →  row has status = "Generated"  →  [Send] button visible
       ↓ click
  Modal opens with:
    ┌────────────────────────────────────────────┐
    │  Send Offer Letter                         │
    │                                            │
    │  Candidate:  Priya Malaker        (ro)     │
    │  Email:      priya@email.com      (ro)     │
    │  Subject:    Offer of Employment   (editable) │
    │                                            │
    │  Body:  [Quill editor with letter] (editable)│
    │                                            │
    │  Response Deadline:  [📅 date picker]  *   │
    │  (min: tomorrow, required)                 │
    │                                            │
    │  Link preview: (shown after generation)    │
    │                                            │
    │        [Cancel]    [Generate Link & Send]  │
    └────────────────────────────────────────────┘
```

### Step 2 — Backend: `send_letter_email.php`

```
POST { offer_id, subject, body, deadline_date }

1. Generate unique token:  bin2hex(random_bytes(32))
2. Build response link:  BASE_URL/offer_management/respond.php?token={TOKEN}
3. Append to email body:
     ---
     Please respond to this offer by clicking the link below:
     [Respond to Offer]({LINK})
     This link is valid until {deadline_date}.
     If you have any questions, please contact the HR department.
     ---
4. INSERT INTO offer_response_tokens (offer_id, token, deadline_date)
5. INSERT INTO offer_email_log (offer_id, recipient, subject, body, status='Sent')
6. UPDATE offer_letters SET status='Sent', sent_at=NOW(), sent_to_email=:email
7. UPDATE offers SET sent_at=NOW(), response_deadline=:deadline
8. CALL addCandidateLog(pdo, candidateId, 'offer_sent', ...)
9. RETURN { success: true, link: "{LINK}" }
```

### Step 3 — Candidate Clicks Link: `respond.php?token=XYZ` ✅ BUILT

`respond.php` is a **standalone public page** (no sidebar/layout). It evaluates the token and renders one of these states:

| State | Trigger | Rendered UI |
|-------|---------|-------------|
| `invalid` | Token not found | ⚠️ "This response link is invalid or has expired." + contact card |
| `expired` | `deadline_date` < today | ⏳ "Response deadline was {date}. Please contact HR." + contact card |
| `already_used` | `is_used = 1` | ✅ INFO CARD: "You have already submitted your response... please contact us at:" + email/phone + recorded date |
| `open` | Valid & available | Offer summary + Accept/Decline buttons + optional notes textarea |
| `accepted` / `declined` | Form submitted successfully | 🎉 Thank-you confirmation + contact card |
| `error` | DB failure during submit | ❌ "Sorry, there was a problem saving your response..." + contact card |

The **open** state shows an offer summary card:

```
┌────────────────────────────────────────────┐
│  🎉 You've Received an Offer!               │
│  Position:     Software Engineer            │
│  Department:   Engineering                  │
│  Job Title:    (from requisition)           │
│  Salary:       ৳ 77,434.00 BDT             │
│  Joining Date: July 16, 2026               │
│  Probation:    6 months                    │
│  Response Deadline: [badge with date]       │
│                                            │
│  [Optional notes textarea]                 │
│                                            │
│    [❌ Decline Offer]  [✅ Accept Offer]   │
│  (after submitting, response can't change) │
└────────────────────────────────────────────┘
```

### Step 4 — Candidate Submits Response ✅ BUILT

```
Accept:
  → UPDATE offers SET response_status='Accepted', responded_at=NOW(), response_notes=:notes
  → UPDATE offer_response_tokens SET is_used=1, used_at=NOW(),
        response_status='Accepted', response_notes=:notes,
        candidate_ip=REMOTE_ADDR, candidate_user_agent=HTTP_USER_AGENT
  → addCandidateLog(..., 'offer_accepted')  (wrapped in a DB transaction)
  → RENDER: "Thank You! Offer Accepted — HR will contact you shortly."

Decline:
  → Same with response_status='Rejected'
  → addCandidateLog(..., 'offer_rejected_candidate')
  → RENDER: "Thank You for Letting Us Know — we wish you the best."

On exception: rollback + render error state.
```

---

## Validation Rules

| Check | Where | Behavior |
|-------|-------|----------|
| Deadline is future date | Client + Server | Min: tomorrow; reject if past |
| Token uniqueness | DB UNIQUE constraint | 64-char random hex |
| Link expired | `respond.php` | Show deadline-passed message |
| Already used | `respond.php` | Show contact-us info card |
| Invalid token | `respond.php` | Show "Invalid or expired link" |

---

## Contact Info Configuration ⏳ PENDING

> **Current state:** Contact details are **hardcoded** in `offer_management/respond.php` (`hr@company.com`, `+880-XXXX-XXXXXX`).

Target: define in `includes/config.php` so they're easy to update without touching the response page:

```php
define('HR_CONTACT_EMAIL', 'hr@company.com');
define('HR_CONTACT_PHONE', '+880-XXXX-XXXXXX');
```

---

## Email Sending

Current system only logs to DB — no actual SMTP transport exists. This plan keeps that behavior. Actual email sending (PHPMailer/SMTP) can be added as a separate phase.

---

## Template Tag Implementation ✅ DONE

The unique response link is now a **merge tag**: `{{RESPONSE_LINK}}`

### How it works
1. `templates/render/offer.php` → `getOfferTagMap()` now includes `'RESPONSE_LINK' => getOfferResponseLink($pdo, $recordId)`
2. `offer_management/ajax/render_template.php` — the live-preview renderer has the same key added (both sources of truth stay in sync)
3. When a template containing `{{RESPONSE_LINK}}` is rendered, a unique token is generated lazily and persisted to `offers.response_token` (64-char hex, unique) — subsequent renders return the **same** stable link
4. The link points to `offer_management/respond.php?token={TOKEN}` (absolute URL built from `appBaseUrl()`)
5. Registered in `merge_tags` table so it appears in the template editor's merge-tag toolbar (type=Offer, sort_order=13) — also added to `templates/ajax/migrate_tags.php` seed for fresh installs

### If the tag is omitted
Letters generated from templates **without** `{{RESPONSE_LINK}}` simply get no link — the tag is fully optional per candidate/letter. Adding or skipping it is a template-level choice.

### Helper functions added (in `includes/functions.php`)
- `appBaseUrl(): string` — absolute base URL from `DOCUMENT_ROOT` + `HTTP_HOST`
- `getOfferResponseToken(PDO, offerId): ?string` — lazy-generates & persists unique token
- `getOfferResponseLink(PDO, offerId): ?string` — builds the absolute response URL

---

## Implementation Order

1. ✅ Create `offer_response_tokens` table + alter `offers` table (added `response_token`, `response_deadline`)
2. ✅ Add helper functions to `includes/functions.php` (`appBaseUrl`, `getOfferResponseToken`, `getOfferResponseLink`)
3. ✅ Add `RESPONSE_LINK` tag to `templates/render/offer.php` + `offer_management/ajax/render_template.php` + `migrate_tags.php` seed + `merge_tags` row
4. ✅ Create `offer_management/respond.php` with full token validation + accept/decline
5. ⏳ Create `offer_management/ajax/send_letter_email.php` — generates token + link, embeds in body, stores deadline
6. ⏳ Update `offer_management/letter.php` — update Send modal with deadline picker
7. ⏳ Update `js/page_js/letter.js` — wire new modal fields + API call
8. ⏳ Update `offer_management/ajax/letter_send.php` with token + link logic
9. ⏳ Add `HR_CONTACT_EMAIL` / `HR_CONTACT_PHONE` to `includes/config.php` (currently hardcoded in respond.php)
10. ⏳ Test end-to-end flow
