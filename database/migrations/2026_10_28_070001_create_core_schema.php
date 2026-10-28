<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------- RBAC ----------
        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();          // machine name e.g. super-admin
            $t->string('display_name_lo');         // ຊື່ບົດບາດພາສາລາວ
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('permissions', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();          // e.g. students.view
            $t->string('display_name_lo');
            $t->string('module');
            $t->index('module');
            $t->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $t) {
            $t->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $t) {
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->primary(['role_id', 'user_id']);
        });

        // ---------- Institution ----------
        Schema::create('academic_years', function (Blueprint $t) {
            $t->id();
            $t->string('label')->unique();         // e.g. "2025-2026"
            $t->date('starts_at');
            $t->date('ends_at');
            $t->boolean('is_current')->default(false);
            $t->timestamps();
        });

        Schema::create('semesters', function (Blueprint $t) {
            $t->id();
            $t->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('number');     // 1,2,3
            $t->string('name_lo');
            $t->date('starts_at');
            $t->date('ends_at');
            $t->boolean('is_current')->default(false);
            $t->unique(['academic_year_id', 'number']);
            $t->timestamps();
        });

        Schema::create('departments', function (Blueprint $t) {
            $t->id();
            $t->string('code', 30)->unique();
            $t->string('name_lo');
            $t->string('phone', 30)->nullable();
            $t->string('email')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('programs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('department_id')->constrained()->cascadeOnDelete();
            $t->string('code', 30)->unique();
            $t->string('name_lo');                 // ສາຂາວິຊາ/ຫຼັກສູດ
            $t->string('level_lo');                // ລະດັບ: ປ.ວ/ມ.ປ/ສູງຊພ...
            $t->unsignedSmallInteger('duration_years')->default(4);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index('department_id');
        });

        Schema::create('courses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('program_id')->constrained()->restrictOnDelete();
            $t->string('code', 30)->unique();
            $t->string('name_lo');
            $t->text('description')->nullable();
            $t->unsignedTinyInteger('credits')->default(2);
            $t->decimal('pass_mark', 5, 2)->default(50);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->index('program_id');
        });

        // ---------- People ----------
        Schema::create('teachers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $t->string('staff_code', 30)->unique();
            $t->string('first_name_lo');
            $t->string('last_name_lo');
            $t->string('position_lo')->nullable();  // ຕຳແໜ່ງ
            $t->string('gender', 10)->default('male');
            $t->date('birth_date')->nullable();
            $t->string('phone', 30)->nullable();
            $t->string('email')->nullable();
            $t->string('photo_path')->nullable();
            $t->enum('employment_status', ['active', 'on_leave', 'resigned', 'retired'])->default('active');
            $t->date('hired_at')->nullable();
            $t->text('teaching_experience')->nullable(); // ປະຫວັດການສອນ
            $t->timestamps();
            $t->softDeletes();
            $t->index(['last_name_lo', 'first_name_lo']);
            $t->index('employment_status');
        });

        Schema::create('classes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('program_id')->constrained()->restrictOnDelete();
            $t->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $t->string('name_lo');                  // e.g. "ປ.ວ 1A ຄະນະບໍລິຫານ"
            $t->string('section_lo')->nullable();
            $t->string('room_lo')->nullable();
            $t->unsignedSmallInteger('capacity')->default(40);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->unique(['program_id', 'academic_year_id', 'name_lo']);
        });

        Schema::create('students', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('class_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $t->string('student_code', 30)->unique();   // ລະຫັດນັກສຶກສາ
            $t->string('first_name_lo');
            $t->string('last_name_lo');
            $t->string('gender', 10)->default('male');
            $t->date('birth_date')->nullable();
            $t->string('phone', 30)->nullable();
            $t->string('email')->nullable();
            $t->string('address_lo')->nullable();
            $t->string('photo_path')->nullable();
            $t->enum('status', ['studying', 'graduated', 'transferred', 'suspended', 'dropped'])->default('studying');
            $t->text('history_notes')->nullable();      // ປະຫວັດ: ຍ້າຍຫ້ອງ/ປ່ຽນສາຂາ/ພັກຮຽນ
            $t->timestamps();
            $t->softDeletes();
            $t->index(['last_name_lo', 'first_name_lo']);
            $t->index('status');
            $t->index('class_id');
        });

        // ---------- Teaching & Learning ----------
        Schema::create('teaching_assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('class_id')->constrained()->cascadeOnDelete();
            $t->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $t->boolean('is_primary')->default(true);
            $t->timestamps();
            $t->unique(['teacher_id', 'course_id', 'class_id', 'semester_id'], 'teaching_unique');
        });

        Schema::create('schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('day_of_week'); // 1=Mon..7=Sun
            $t->time('start_time');
            $t->time('end_time');
            $t->string('room_lo')->nullable();
            $t->timestamps();
            $t->index(['day_of_week', 'start_time']);
        });

        Schema::create('lessons', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $t->date('date');
            $t->string('title_lo');
            $t->text('plan_lo')->nullable();       // ແຜນການສອນ
            $t->text('content_lo')->nullable();    // ບົດຮຽນ
            $t->text('notes')->nullable();
            $t->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $t->boolean('taught')->default(false); // ບັນທຶກການສອນຈິງ
            $t->timestamp('taught_at')->nullable();
            $t->timestamps();
            $t->index(['teaching_assignment_id', 'date']);
            $t->index('status');
        });

        // ---------- Attendance ----------
        Schema::create('attendance_sessions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('lesson_id')->unique()->constrained()->cascadeOnDelete();
            $t->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $t->timestamp('opens_at')->nullable();
            $t->timestamp('closes_at')->nullable();
            $t->string('qr_token_hash', 64)->nullable();
            $t->timestamp('qr_expires_at')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('attendance_records', function (Blueprint $t) {
            $t->id();
            $t->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->enum('status', ['present', 'late', 'absent', 'excused'])->default('present');
            $t->enum('method', ['list', 'qr', 'manual_fix'])->default('list');
            $t->string('device_info')->nullable();
            $t->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('checked_at')->nullable();
            $t->text('note')->nullable();           // ເຫດຜົນການແກ້ໄຂ
            $t->timestamps();
            $t->unique(['attendance_session_id', 'student_id'], 'attendance_unique'); // ຫ້າມເຊັກຊ້ຳ
            $t->index('student_id');
        });

        Schema::create('leave_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('attendance_session_id')->nullable()->constrained()->nullOnDelete();
            $t->date('date');
            $t->text('reason_lo');
            $t->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('decided_at')->nullable();
            $t->timestamps();
            $t->index(['student_id', 'date']);
        });

        // ---------- Grades ----------
        Schema::create('assessments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $t->string('title_lo');
            $t->enum('type', ['homework', 'quiz', 'midterm', 'final'])->default('homework');
            $t->decimal('weight', 5, 2)->default(10);   // ນ້ຳໜັກ %
            $t->decimal('max_score', 5, 2)->default(10);
            $t->date('due_date')->nullable();
            $t->timestamps();
            $t->index('teaching_assignment_id');
        });

        Schema::create('grades', function (Blueprint $t) {
            $t->id();
            $t->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->decimal('score', 5, 2)->nullable();
            $t->text('comment')->nullable();
            $t->boolean('confirmed')->default(false);
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->unique(['assessment_id', 'student_id'], 'grade_unique');
            $t->index('student_id');
        });

        Schema::create('grade_histories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $t->decimal('old_score', 5, 2)->nullable();
            $t->decimal('new_score', 5, 2)->nullable();
            $t->foreignId('edited_by')->constrained('users')->cascadeOnDelete();
            $t->text('reason')->nullable();
            $t->timestamps();
        });

        // ---------- Documents ----------
        Schema::create('documents', function (Blueprint $t) {
            $t->id();
            $t->string('owner_type');               // students, teachers, lessons...
            $t->unsignedBigInteger('owner_id');
            $t->enum('category', ['personal', 'academic', 'material'])->default('academic');
            $t->string('disk')->default('private');
            $t->string('path');
            $t->string('original_name');
            $t->string('mime_type', 100);
            $t->unsignedBigInteger('size');
            $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
            $t->index(['owner_type', 'owner_id']);
        });

        // ---------- Ops ----------
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action', 50);               // login/create/update/delete/approve/export...
            $t->string('module', 50);
            $t->string('record_id', 50)->nullable();
            $t->string('description')->nullable();
            $t->json('meta')->nullable();           // NO passwords/tokens/secrets
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent')->nullable();
            $t->string('status', 20)->default('success');
            $t->timestamps();
            $t->index(['module', 'action']);
            $t->index('created_at');
        });

        Schema::create('backup_logs', function (Blueprint $t) {
            $t->id();
            $t->string('type')->default('database'); // database/files/full
            $t->string('path');
            $t->unsignedBigInteger('size')->nullable();
            $t->string('checksum', 64)->nullable();
            $t->boolean('encrypted')->default(false);
            $t->string('status', 20)->default('success');
            $t->foreignId('run_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('verified_at')->nullable();
            $t->timestamp('restored_at')->nullable();
            $t->timestamps();
        });

        Schema::create('announcements', function (Blueprint $t) {
            $t->id();
            $t->string('title_lo');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('body_lo');
            $t->string('image_path')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        // users extra columns
        Schema::table('users', function (Blueprint $t) {
            $t->boolean('is_active')->default(true)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn('is_active');
        });
        foreach ([
            'announcements','backup_logs','activity_logs','documents','grade_histories','grades',
            'assessments','leave_requests','attendance_records','attendance_sessions','lessons',
            'schedules','teaching_assignments','students','classes','teachers','courses','programs',
            'departments','semesters','academic_years','role_user','permission_role','permissions','roles',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
