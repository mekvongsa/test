# ຂໍ້ກຳນົດໂຄງການ EduTrack LMS (Project Specification)

> ເອກະສານນີ້ສ້າງຂຶ້ນເມື່ອຄັ້ງທຳອິດບໍ່ພົບ PROJECT_SPEC.md ໃນ Repository.
> ມັນແປງຂໍ້ກຳນົດຈາກຄຳສັ່ງພັດທະນາ (Development Command) ມາເປັນ Spec ຢ່າງເປັນທາງການ
> ເພື່ອໃຊ້ເປັນຫຼັກກວດສອບໃນທຸກ Phase. ສະຖານະ: Draft v1.0 — ຕ້ອງໄດ້ຮັບການຢືນຢັນຈາກເຈົ້າຂອງໂຄງການ.

## 1. ວົງໂຄງສະໜາມ (Scope)

EduTrack LMS ແມ່ນ Web Application ຈັດການຮຽນ-ສອນສຳລັບສະຖາບັນການສຶກສາ 1 ແຫ່ງ ຄອບຄຸມ:

1. Dashboard ສະຖິຕິຈາກ Database ຈິງ
2. ຈັດການສະຖາບັນ: ພາກວິຊາ, ສົກຮຽນ/ພາກຮຽນ, ຫຼັກສູດ/ສາຂາວິຊາ, ຫ້ອງຮຽນ, ຕາຕະລາງຮຽນ, ວິຊາ ແລະ ການມອບໝາຍອາຈານ
3. ຈັດການນັກສຶກສາ (CRUD, import/export ຕາມສິດ, ຮູບ, ເອກະສານ, ປະຫວັດ)
4. ຈັດການອາຈານ ແລະ ພະນັກງານ
5. ການຮຽນການສອນ: ແຜນສອນ, ບົດຮຽນ, ສື່, ສະຖານະ Draft/Published/Archived
6. ເຊັກຊື່: ລາຍຊື່ + QR Token ປອດໄພ, ເປີດ/ປິດເວລາ, ຫ້າມເຊັກຊ້ຳ (Unique Constraint + Transaction), ລາ/ມາຊ້າ/ຂາດ, ອະນຸມັດລາ, ລາຍງານ
7. ຄະແນນ: ວຽກບ້ານ/ກາງພາກ/ທ້າຍພາກ/ລວມ, ນ້ຳໜັກ, ເກນຜ່ານ, ປະຫວັດແກ້ໄຂ, GPA
8. ເອກະສານ ແລະ ສື່ການຮຽນ: Upload/Download/Preview/Replace/Delete ຕາມສິດ, Private Storage
9. ລາຍງານ PDF/Excel/CSV, ໃບຄະແນນ, ໃບປະກາດ (Template ປັບໂລໂກ້/ຊື່/ລາຍເຊັນ/ເລກອ້າງອີງ)
10. ຜູ້ໃຊ້, Roles, Permissions (RBAC + Policies), Login/Logout/Password Reset/Profile
11. Notifications ແລະ Realtime (Broadcasting + Echo; Fallback Polling)
12. Audit Logs (ຄົ້ນຫາ/ກອງ, ຫ້າມບັນທຶກ Secrets)
13. Backup/Restore (MySQL + Files + Config, Retention, Encryption, ຄູ່ມື Restore)
14. SEO ສຳລັບໜ້າ Public ເທົ່ານັ້ນ (ໜ້າ Login/Dashboard ຫ້າມ Index)

## 2. ເທັກໂນໂລຢີ

- Backend: Laravel 12 (PHP 8.2), Eloquent, Form Requests, Policies, Middleware, Events, Jobs/Queues, Sanctum API
- Frontend: Vue 3 `<script setup>` + Vite + TypeScript + Pinia + Axios + Tailwind CSS 4
- UI Libraries: SweetAlert2, Tippy.js (+@popperjs/core / Floating UI), @vueuse/core (gesture/swipe/drag), vue3-number-flow→(ທດສະ: custom composable `useAnimatedNumber` ເພາະ library "number flow" ເປັນ React-only), Custom Stepper/Switch/Tabs/TagsInput/Modal components
- Database: MySQL (MariaDB 10.11) utf8mb4_unicode_ci; ທົດສອບດ້ວຍ SQLite :memory:
- Realtime: Laravel Reverb/Echo ເມື່ອ Environment ຮອງຮັບ; Fallback = polling ທີ່ define ໃນ config
- Font: Noto Sans Lao (Google Fonts) ທົ່ວລະບົບ

## 3. ບົດບາດ (Roles) ຕົວຢ່າງ

Super Admin, ຜູ້ບໍລິຫານ, ຫົວໜ້າພາກວິຊາ, ອາຈານ, ພະນັກງານ, ນັກສຶກສາ.
ສິດ: view/create/update/delete/approve/export ຕໍ່ module. Backend ຕ້ອງກວດສິດທຸກ Endpoint (ຫ້າມອາໄສການຊ່ອນປຸ່ມຝັ່ງ UI).

## 4. ຄວາມປອດໄພ

CSRF, XSS, SQLi prevention, secure sessions, hashed passwords (bcrypt), rate limiting (login/reset/API), authorization ทุก endpoint, transactions, eager loading, indexes, server-side pagination, cache ສະເພາະທີ່ເໝາະສົມ, no debug in production, secrets ໃນ .env (ຫ້າມ commit), error handling ບໍ່ເປີດເຜີຍ stack trace.

QR Attendance: ຮັບຮູ້ຈຳກັດ — QR ຢ່າງດຽວຮັບປະກັນ 100% ບໍ່ໄດ້; ໃຊ້ Token ຜູກ session + ອາຍຸສັ້ນ + ກວດບັນຊີ + audit log.

## 5. UI/UX

Modern Education SaaS: ພື້ນຂາວ/ເທົາ, ສີຟ້າ+ຂຽວ accent, Card/rounded/shadow, Sidebar (desktop) + Bottom Nav (mobile) + More Menu (donut, grid/circular), Breadcrumbs, Skeleton/Empty/Error states, accessible dropdown/select (searchable, keyboard, z-index ຖືກ), Modal forms ມີ validation ສອງຝັ່ງ + loading + ກັນ submit ຊ້ຳ + ກວດ unsaved data ກ່ອນປິດ.

## 6. Data Rules

- ຂໍ້ມູນສາທິດພາສາລາວ ≥ 5 records ຕໍ່ຕາຕະລາງຫຼັກ; parent ກ່ອນ child; Seeders run ຊ້ຳໄດ້ (idempotent/upsert)
- Unique student_code, FK + indexes + timestamps, SoftDeletes ບ່ອນທີ່ເໝາະສົມ
- ຫ້າມ seed ທັບ production ໂດຍບໍ່ຢືນຢັນ; activity_logs seed ສະເພາະ dev/testing

## 7. Definition of Done

ຕາມພາກ 16 ຂອງຄຳສັ່ງພັດທະນາ: CRUD+Pagination+Search+Validation ເຊື່ອມ DB ຈິງ, Auth/RBAC ຜ່ານ test, ເຊັກຊື່/ຄະແນນ/reports ໃຊ້ໄດ້, realtime ຕາມ config, audit/backup ກວດສອບໄດ້, UI responsive, `npm run build` + tests ຜ່ານ, ࡓະບຽບ documentation ຄົບ.
