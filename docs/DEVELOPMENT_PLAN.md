# MyTasks — Development Plan & Phases

> Personal Daily Task Manager  
> Stack: **Laravel 12 · Blade · Vite · Bootstrap 5 · MySQL · PHPUnit**  
> Style: Server-rendered MVC (not an API)

---

## How we work

1. Complete **one phase** before starting the next.
2. Inside every feature phase, follow this order:
   - Database Design → Migration → Model → Relationships → Validation → Controller → Routes → Blade Views → Business Logic → **Testing**
3. Explain a short implementation plan **before** writing code for that phase.
4. A phase is **Done** only when:
   - Feature works manually in the browser
   - Phase tests pass: `php artisan test --filter=PhaseNX`
   - No unrelated files changed

### Progress tracker

| Phase | Name | Status |
|-------|------|--------|
| 0 | Foundation & Tooling | ✅ Done |
| 1 | Authentication | ✅ Done |
| 2 | Layout, UI Kit & Dark Mode | ✅ Done |
| 3 | User Profile | ✅ Done |
| 4 | Categories | ✅ Done |
| 5 | Tasks (CRUD Core) | ✅ Done |
| 6 | Task Actions & Soft Deletes | ✅ Done |
| 7 | Search, Filters & Sort | ✅ Done |
| 8 | Dashboard & Statistics | ⬜ Pending |
| 9 | Calendar Views | ⬜ Pending |
| 10 | Reminders & In-App Notifications | ⬜ Pending |
| 11 | Polish, Seeders & Final QA | ⬜ Pending |

**Legend:** ⬜ Pending · 🔄 In Progress · ✅ Done

---

## Domain overview (target schema)

```
users
  id, name, email, password, avatar, theme (light|dark),
  email_verified_at, remember_token, timestamps

categories
  id, user_id (FK), name, color, icon, softDeletes, timestamps

tasks
  id, user_id (FK), category_id (FK nullable),
  title, description, notes,
  priority (low|medium|high|urgent),
  status (pending|in_progress|completed|cancelled),
  due_date, due_time,
  reminder_at (nullable),
  completed_at (nullable),
  softDeletes, timestamps

notifications (Laravel database notifications or custom)
  id, user_id, type, data (JSON), read_at, timestamps
```

**Rules**

- Every resource is scoped to `auth()->id()`.
- Prefer Enums for `priority` and `status`.
- Soft deletes on `categories` and `tasks`.
- Policies for authorization (user owns the record).

---

## Phase 0 — Foundation & Tooling

### Goal
Prepare the project for Blade + Bootstrap development and a solid testing baseline.

### Plan
1. Keep Laravel 12 skeleton; switch frontend from Tailwind to **Bootstrap 5 + Bootstrap Icons**.
2. Configure Vite (`bootstrap`, `bootstrap-icons`, SweetAlert2, toast library).
3. Confirm MySQL (`.env` already points to `mytasks_db`).
4. Add base folders: `Enums`, `Policies`, `View/Components`, `Services` (empty structure).
5. Configure PHPUnit / Pest-ready Feature tests with `RefreshDatabase`.
6. Optional: install Laravel Breeze (Blade) **or** build auth manually in Phase 1 — decide in Phase 1 kickoff.

### Deliverables
- [ ] Bootstrap 5 + Icons via Vite
- [ ] `resources/css/app.css` / `resources/js/app.js` wired
- [ ] Empty app layout stub (`layouts/app.blade.php`)
- [ ] DB connection verified (`php artisan migrate`)
- [ ] Test suite runs green on skeleton

### Tests (`Phase0FoundationTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Application boots | `GET /` returns 200 |
| 2 | Vite assets config exists | `vite.config.js` loads Bootstrap entry |
| 3 | Migrations run | `migrate:fresh` succeeds in test env |

### Exit criteria
```bash
php artisan migrate
npm run build
php artisan test --filter=Phase0
```

---

## Phase 1 — Authentication

### Goal
Full auth: register, login, logout, password reset. Each user only sees own data later.

### Plan
1. Extend `users` if needed (minimal for auth).
2. Controllers: Register, Login, Forgot/Reset Password (or Breeze Blade).
3. Form Requests for register/login/reset.
4. Guest middleware on auth pages; `auth` middleware on app.
5. Blade: auth layout + forms.

### Deliverables
- [ ] Register / Login / Logout
- [ ] Forgot & Reset Password (mail or log driver in local)
- [ ] Auth routes + middleware
- [ ] Flash messages on success/failure

### Tests (`Phase1AuthTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Guest can view register/login | 200 |
| 2 | User can register | user in DB + redirected + authenticated |
| 3 | Validation fails on bad register data | 422/session errors |
| 4 | User can login / logout | session auth state |
| 5 | Invalid credentials rejected | guest + error |
| 6 | Password reset link request | notification/mail faked |
| 7 | Password can be reset with valid token | password updated |
| 8 | Auth pages redirect when already logged in | redirect to dashboard |

### Exit criteria
```bash
php artisan test --filter=Phase1
```

---

## Phase 2 — Layout, UI Kit & Dark Mode

### Goal
Professional shell: sidebar, top nav, flash/toast, SweetAlert, light/dark theme.

### Plan
1. Blade layout: sidebar + topbar + content slot.
2. Shared partials: alerts, empty state, loading spinner.
3. Theme preference column `users.theme` (`light`/`dark`) + cookie/session fallback for guests if needed.
4. Toggle endpoint + JS class on `<html data-bs-theme>`.
5. Include Bootstrap Icons, SweetAlert2, toast helper.

### Deliverables
- [ ] `layouts/app.blade.php` with sidebar + top nav
- [ ] Flash → Toast
- [ ] Confirm deletes via SweetAlert
- [ ] Dark/Light mode persisted per user
- [ ] Responsive (mobile sidebar collapse)

### Tests (`Phase2LayoutTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Authenticated user sees app layout | sidebar markers in HTML |
| 2 | Guest redirected from app routes | redirect to login |
| 3 | Theme toggle updates preference | `theme` column / session |
| 4 | Dark theme class rendered when preferred | `data-bs-theme="dark"` |

### Exit criteria
```bash
php artisan test --filter=Phase2
```

---

## Phase 3 — User Profile

### Goal
Edit profile, change password, upload avatar.

### Plan
1. Migration: `avatar` (nullable string path), ensure `name`/`email`.
2. `ProfileController` + Form Requests.
3. Avatar storage on `public` disk; delete old file on replace.
4. Blade profile page with preview.

### Deliverables
- [ ] Update name/email
- [ ] Change password (current + confirmation)
- [ ] Upload / replace / remove avatar
- [ ] Unique email validation (ignore self)

### Tests (`Phase3ProfileTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | User can update profile | DB updated |
| 2 | Email uniqueness enforced | validation error |
| 3 | Password change requires current password | fail without / success with |
| 4 | Avatar upload stores file | Storage::fake + path saved |
| 5 | Unauthorized access blocked | auth middleware |

### Exit criteria
```bash
php artisan test --filter=Phase3
```

---

## Phase 4 — Categories

### Goal
Unlimited user-owned categories (name, color, icon).

### Plan
1. Migration + `Category` model + soft deletes.
2. `User` ↔ `Category` relationships.
3. Policy: owner only.
4. CRUD Form Requests + Controller.
5. Blade index/create/edit + color/icon picker (simple).
6. Seeder examples: Work, Study, Personal, Health, Shopping, Finance.

### Deliverables
- [ ] Category CRUD
- [ ] Soft delete
- [ ] User isolation (cannot see others’ categories)
- [ ] Default examples via seeder (optional per user on register)

### Tests (`Phase4CategoryTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | User can create category | owned by user |
| 2 | Validation (name required, color format) | errors |
| 3 | User can update/delete own category | soft deleted |
| 4 | User cannot access another user’s category | 403 |
| 5 | Index lists only own categories | isolation |

### Exit criteria
```bash
php artisan test --filter=Phase4
```

---

## Phase 5 — Tasks (CRUD Core)

### Goal
Core task entity with all fields, enums, ownership, list/show/create/edit.

### Plan
1. Enums: `TaskStatus`, `TaskPriority`.
2. Migration `tasks` + FKs to `users` and `categories`.
3. `Task` model: casts, soft deletes, relationships, scopes.
4. Policy + Form Request.
5. Resource controller (thin) + optional `TaskService` for create/update.
6. Blade: index table, form, show, empty state, pagination.

### Fields
Title, Description, Category, Priority, Status, Due Date, Due Time, Reminder, Notes

### Deliverables
- [ ] Full CRUD
- [ ] Route model binding + policy
- [ ] Pagination
- [ ] Flash messages
- [ ] Category belongs to same user (validation)

### Tests (`Phase5TaskCrudTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | User can create task with valid data | DB + redirect |
| 2 | Invalid data rejected | validation |
| 3 | User can update/delete own task | soft delete |
| 4 | Cannot use another user’s category_id | validation/403 |
| 5 | Cannot view another user’s task | 403 |
| 6 | Index paginates and scopes to owner | isolation |
| 7 | Enums cast correctly | status/priority |

### Exit criteria
```bash
php artisan test --filter=Phase5
```

---

## Phase 6 — Task Actions & Soft Deletes

### Goal
Complete, restore, duplicate; trash listing.

### Plan
1. Actions: `complete`, `restore`, `duplicate` (POST routes).
2. `completed_at` set/cleared with status changes.
3. Trash view: only soft-deleted tasks.
4. Force delete optional (or restore only for v1).
5. SweetAlert confirms on destructive actions.

### Deliverables
- [ ] Mark complete / reopen
- [ ] Soft delete + restore
- [ ] Duplicate (copy fields, new pending task)
- [ ] Trash UI

### Tests (`Phase6TaskActionsTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Complete task sets status + completed_at | DB state |
| 2 | Soft-deleted task hidden from index | not listed |
| 3 | Restore brings task back | not trashed |
| 4 | Duplicate creates new owned task | copy without same id |
| 5 | Actions forbidden for other users | 403 |

### Exit criteria
```bash
php artisan test --filter=Phase6
```

---

## Phase 7 — Search, Filters & Sort

### Goal
Global search + filters (status, priority, category, date presets) + sorting.

### Plan
1. Query scopes / dedicated `TaskFilter` or `TaskQuery` class.
2. Filters: status, priority, category, today, tomorrow, this week, this month.
3. Search: title, description, category name.
4. Sort: due date, priority, created_at, title.
5. Blade filter bar + preserve query string on pagination.

### Deliverables
- [ ] Search box (tasks page + optional global topbar)
- [ ] Filter form
- [ ] Sort dropdown
- [ ] Clean URL query params

### Tests (`Phase7SearchFilterTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Search by title matches | result set |
| 2 | Search by description / category name | matches |
| 3 | Filter by status / priority / category | scoped |
| 4 | Date presets (today / week / month) | correct windows |
| 5 | Sort by due_date | order |
| 6 | Filters never leak other users’ tasks | isolation |

### Exit criteria
```bash
php artisan test --filter=Phase7
```

---

## Phase 8 — Dashboard & Statistics

### Goal
Dashboard with today’s / upcoming / completed / overdue tasks, % completion, productivity summary, recent activity.

### Plan
1. `DashboardController` + `DashboardStatsService` (or Query object).
2. Cards: total, completed, pending, overdue, today’s, weekly/monthly progress.
3. Lists: today, upcoming, overdue, recent completed/activity.
4. Blade dashboard with stats cards + empty states.

### Deliverables
- [ ] Welcome message
- [ ] Stats cards
- [ ] Task sections
- [ ] Completion percentage
- [ ] Weekly & monthly progress

### Tests (`Phase8DashboardTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Dashboard requires auth | redirect guest |
| 2 | Counts match seeded tasks | totals correct |
| 3 | Overdue excludes completed | logic |
| 4 | Today’s tasks only due today | date scope |
| 5 | Completion % calculated correctly | formula |
| 6 | Only current user’s data | isolation |

### Exit criteria
```bash
php artisan test --filter=Phase8
```

---

## Phase 9 — Calendar Views

### Goal
Daily / weekly / monthly calendar of tasks.

### Plan
1. `CalendarController` with `view=day|week|month` + date param.
2. Query tasks in date range for user.
3. Blade calendar UI (Bootstrap grid; optional lightweight JS).
4. Click day → filtered tasks / create with prefilled due date.

### Deliverables
- [ ] Day view
- [ ] Week view
- [ ] Month view
- [ ] Navigation prev/next

### Tests (`Phase9CalendarTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Month view lists tasks in month | present |
| 2 | Week/day views scope correctly | date bounds |
| 3 | Navigation query params work | 200 + correct range |
| 4 | Other users’ tasks never appear | isolation |

### Exit criteria
```bash
php artisan test --filter=Phase9
```

---

## Phase 10 — Reminders & In-App Notifications

### Goal
Task reminders + in-app notifications (due today, overdue, completed).

### Plan
1. Use `reminder_at` on tasks.
2. Laravel Notifications (database channel) + `notifications` table.
3. Scheduler/command: dispatch due reminders & overdue digests (or on-request check on each auth request for simplicity in v1).
4. Notification bell in top nav + mark as read.
5. Events: TaskCompleted → notify (optional self-feed).

### Deliverables
- [ ] Reminder field on task form
- [ ] In-app notification list
- [ ] Mark read / mark all read
- [ ] Types: due today, overdue, completed, reminder

### Tests (`Phase10NotificationsTest`)
| # | Test | Assert |
|---|------|--------|
| 1 | Reminder saved on task | DB |
| 2 | Due-today notification created for eligible tasks | notification row |
| 3 | Overdue notification logic | created once / idempotent strategy |
| 4 | User can mark notification read | `read_at` set |
| 5 | Notifications scoped to user | isolation |

### Exit criteria
```bash
php artisan test --filter=Phase10
```

---

## Phase 11 — Polish, Seeders & Final QA

### Goal
Production-ready feel: seeders, empty states, loading indicators, security pass, full regression.

### Plan
1. Demo seeder (user + categories + tasks covering all statuses/priorities).
2. UI polish pass (empty states, loading, consistency).
3. Policies audit + mass-assignment review.
4. Run full test suite + Pint.
5. Update README with setup instructions.

### Deliverables
- [ ] `DemoSeeder`
- [ ] README setup guide
- [ ] Full suite green
- [ ] Pint / PSR-12 clean

### Tests (`Phase11FinalQaTest` + full suite)
| # | Test | Assert |
|---|------|--------|
| 1 | Demo seeder runs | no exceptions |
| 2 | Critical happy path feature test | register → create task → complete → dashboard |
| 3 | Full suite | `php artisan test` all green |

### Exit criteria
```bash
php artisan test
vendor/bin/pint --test
npm run build
```

---

## Suggested folder structure (target)

```
app/
  Enums/
    TaskStatus.php
    TaskPriority.php
  Http/
    Controllers/
      Auth/
      CategoryController.php
      TaskController.php
      DashboardController.php
      CalendarController.php
      ProfileController.php
      NotificationController.php
    Requests/
      Auth/
      Category/
      Task/
      Profile/
  Models/
    User.php
    Category.php
    Task.php
  Policies/
    CategoryPolicy.php
    TaskPolicy.php
  Services/
    DashboardStatsService.php
    TaskQueryService.php
    NotificationDispatchService.php
resources/
  views/
    layouts/
    components/
    auth/
    dashboard/
    tasks/
    categories/
    calendar/
    profile/
    notifications/
tests/
  Feature/
    Phase0FoundationTest.php
    Phase1AuthTest.php
    ...
```

---

## Coding standards (every phase)

- PSR-12, thin controllers, Form Requests, Policies
- Route model binding + explicit scopes by `user_id`
- Soft deletes where specified
- Flash + Toast; SweetAlert for confirms
- No N+1: eager load `category` where listing tasks
- Meaningful names; no dead code
- Feature tests preferred over unit for HTTP flows

---

## Commands cheat sheet

```bash
# Run one phase
php artisan test --filter=Phase5

# Full suite
php artisan test

# Fresh DB
php artisan migrate:fresh --seed

# Frontend
npm install
npm run dev
npm run build
```

---

## Next step

Start **Phase 0 — Foundation & Tooling** when you say go.  
Before coding, a short implementation plan for Phase 0 will be written in chat, then we implement + add `Phase0FoundationTest`.
