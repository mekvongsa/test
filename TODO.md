# TODO — EduTrack LMS

ສະຖານະອັບເດດຕາມຈິງ. ຝາຍ [x] ກໍຕໍ່ເມື່ອ run/verify ຈິງ.

## Phase 1 — Audit & Plan
- [x] ອ່ານ repo (ວ່າງ, README "# test" ເທົ່ານັ້ນ) → Laravel 12 skeleton install
- [x] PHP 8.2 + Composer + MariaDB 10.11 (utf8mb4 DB `edutrack`) + Node 20/npm 10
- [x] PROJECT_SPEC.md ບໍ່ມີ → ສ້າງ baseline spec
- [x] IMPLEMENTATION_PLAN.md

## Phase 2 — Setup & Auth & Layout
- [ ] npm deps (vue3, router, pinia, ts, tailwind4, sweetalert2, tippy, vueuse, echos, dompdf...)
- [ ] Sanctum + login/logout/reset/profile API
- [ ] Blade root view + Vite TS entry + Noto Sans Lao

## Phase 3 — Migrations / Models / RBAC / Seeders
- [ ] Migrations ຕາມ schema plan (FK, indexes, unique, utf8mb4)
- [ ] Models + relationships + policies
- [ ] RBAC seed (6 roles, permissions), idempotent Lao seeders ≥5 records/table

## Phase 4–7 — Modules (API + Pages)
- [ ] Dashboard stats API + animated counters + charts
- [ ] Departments/years/semesters/programs/courses/classes CRUD
- [ ] Students CRUD (+photo/docs/import-export) · Teachers CRUD
- [ ] Lessons/teaching/schedules · Attendance session+QR+records+leave
- [ ] Assessments/grades/history/GPA · Documents private storage
- [ ] Reports PDF/XLSX/CSV + ໃບປະກາດ template
- [ ] Notifications+broadcast(fallback poll) · Audit logs UI · Backup command+logs+restore guide

## Phase 8 — UI/UX
- [ ] Sidebar desktop / bottom nav + donut More menu mobile (safe-area)
- [ ] Accessible Select/Dropdown/Tabs/Switch/Stepper/TagsInput/Modal system
- [ ] Skeleton/Empty/Error states, Lao date/number format

## Phase 9 — Testing
- [ ] php artisan test (feature suites) — ຜົນຈິງ: ___
- [ ] vitest frontend units — ຜົນຈິງ: ___
- [ ] Security/perf review (N+1, rate limit, no debug)

## Phase 10 — Docs & Build
- [ ] README.md (ພາສາລາວ) setup/commands/backup-restore manual
- [ ] npm run build pass · public SEO pages/sitemap/robots
- [ ] Final verification report
