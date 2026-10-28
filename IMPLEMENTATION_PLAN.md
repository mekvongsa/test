# IMPLEMENTATION_PLAN — EduTrack LMS

## 1. Architecture

**SPA + REST API (Laravel 12 + Vue 3 + TypeScript)**
- Laravel ເປັນ monolith: session web auth (cookie) + Sanctum ສຳລັບ API tokens (ຕໍ່ຍອດ Mobile ອະນາຄົດ).
- Vue 3 SPA (`resources/js`) build ດ້ວຍ Vite; routing ດ້ວຍ vue-router (history mode, fallback ຫາ `catch-all` view).
- JSON API under `/api/v1/*` (middleware `auth:sanctum` + policies); SPA ໃຊ້ session cookie ຜ່ານ `/api/v1` ດຽວກັນ → API ຊຸດດຽວໃຊ້ໄດ້ທັງ SPA ແລະ mobile.
- Pinia stores: auth, notifications, ui. Services layer: axios instance + interceptors (401 redirect, CSRF).
- Realtime: Broadcast events + channel auth; driver `pusher`(Reverb) ຖ້າ config, fallback = polling service ໃນ store (config `services.realtime.fallback_poll_seconds`).
- Queues: `database` driver ໃນ dev/testing; jobs ສຳລັບ notification/report/backup.

## 2. Database Schema (MySQL utf8mb4_unicode_ci)

| Table | Key fields / constraints |
|---|---|
| users | name, email unique, password, student_id?/teacher_id?, is_active |
| roles / permissions / permission_role / role_user | RBAC pivot, name unique |
| departments | name_lo, code unique, head teacher FK |
| academic_years | year label unique, start/end dates, is_current |
| semesters | academic_year_id FK, number, unique(year,number) |
| programs | department_id FK, name_lo, level (ສາຂາວິຊາ/ລະດັບ) |
| courses | program_id FK, code unique, name_lo, credits, pass_mark |
| classes | course/program FK, section, capacity, academic_year_id |
| students | user_id? FK, student_code unique, Lao names, gender, dob, contact, class_id FK, status enum |
| teachers | user_id? FK, staff_code unique, Lao names, position, department_id FK, employment_status |
| enrollments | student_id+class_id unique, enrolled_at, status |
| teaching_assignments | teacher+course+class unique, semester_id |
| schedules | assignment FK, day_of_week, start/end time, room |
| lessons | assignment FK, date, title, content, status draft/published/archived |
| lesson_materials | lesson FK polymorphic file ref |
| attendance_sessions | lesson/assignment FK, open/close time, qr_token_hash, token_expires_at, unique(lesson) |
| attendance_records | session+student unique, status present/late/absent/excused, method list/qr, device_info, checked_by, note |
| leave_requests | student FK, session/date, reason, status pending/approved/rejected, approver |
| assessments | class/course FK, type homework/midterm/final, weight, max_score |
| grades | assessment+student unique, score (≤max), updated_by; grade_histories table |
| grade_histories | old/new value, editor, reason, timestamp |
| documents | owner polymorphic, category personal/academic/material, disk, path, mime, size, uploaded_by |
| notifications | standard Laravel notifications table |
| activity_logs | user, action, module, record_id, description, meta(JSON no secrets), ip, ua, status |
| backup_logs | driver, path, size, checksum, status, encrypted, restored_at |
| announcements (public SEO) | title, slug unique, body, published_at |

Indexes: FK columns, name fields (LIKE search), dates, status, lookup columns. SoftDeletes: students, teachers, courses, classes, lessons, documents.

## 3. Modules ↔ Menu (Lao UI)

Dashboard · ສະຖາບັນ (ພາກວິຊາ/ສົກຮຽນ/ຫຼັກສູດ/ຫ້ອງຮຽນ/ຕາຕະລາງ) · ນັກສຶກສາ · ອາຈານ-ພະນັກງານ · ການຮຽນການສອນ · ເຊັກຊື່ (QR) · ຄະແນນ · ເອກະສານ/ສື່ · ລາຍງານ · ຜູ້ໃຊ້/ສິດ · Audit Logs · ສຳຮອງ · ຕັ້ງຄ່າ · Profile/Login · ໜ້າ Public (SEO).

## 4. Development Phases & Status

- [x] **Phase 1** Environment audit (PHP 8.2 installed, MariaDB 10.11 running, Node 20, Composer 2.10; PROJECT_SPEC.md absent → created baseline spec)
- [x] **Phase 2** Laravel 12 skeleton in /workspace, MySQL `.env`, base layout (in progress below)
- [ ] **Phase 3** Migrations/models/RBAC seeders
- [ ] **Phase 4** Core CRUD modules API + pages
- [ ] **Phase 5** Schedule/teaching/attendance + QR
- [ ] **Phase 6** Grades, documents, reports (PDF/Excel/CSV)
- [ ] **Phase 7** Notifications/realtime(fallback poll), audit logs, backup command
- [ ] **Phase 8** UI/UX refinement, mobile bottom nav + donut more menu, select/dropdown a11y
- [ ] **Phase 9** Tests backend/frontend/integration, security & perf checks
- [ ] **Phase 10** Docs (README Lao), production build, final verification

## 5. Dependencies (compatibility verified for Vue 3 + Vite 7 + TS)

vue@3, vue-router@4, pinia@3, typescript, @vueuse/core (gesture/swipe), swee­talert2, tippy.js + @popperjs/core (@floating-ui/dom ໃຊ້ທາງ popper/tippy), laravel-echo + pusher-js (optional driver), chart: lightweight custom SVG/composables (ຫຼີກ lottie-heavy libs), lucide-vue-next icons, dayjs (lao locale via custom strings), exceljs/browser download CSV; Backend: laravel/sanctum, barryvdh/laravel-dompdf, openspout/openspout (XLSX), simplesoftwareio/simple-qrcode, spatie/laravel-permission → ໃຊ້ custom RBAC ເພື່ອຄວບຄຸມ schema.

ເຫດຜົນ alternative: "Number Flow" ແລະ "use-gesture" ເປັນ React-only → ໃຊ້ `useAnimatedNumber` composable + `@vueuse/core` (useSwipe/useDrag) ແທນ, ບັນທຶນຕາມ Spec §2.

## 6. Testing Plan

- Backend Feature tests (sqlite :memory:, RefreshDatabase): auth/RBAC, CRUD+validation, unique constraints (student_code, duplicate check-in incl. concurrent two requests), grade bounds + GPA calc, file upload MIME/size, policy 403, audit log written w/o secrets, backup command creates verifiable file.
- Frontend: vitest + @vuejs/test-utils unit tests ສຳລັບ Modal/Tabs/Switch/Pagination/Select composables.
- Integration: migrate+seed against real MySQL manually verified via artisan/curl; documented results.
- ຫ້າມລາຍງານຜ່ານໂດຍບໍ່ run ຈິງ — ຜົນທຸກ suite ບັນທຶກໃນ TODO.md.
