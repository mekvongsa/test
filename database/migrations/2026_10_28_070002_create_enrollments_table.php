<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $t->timestamp('enrolled_at')->nullable();
            $t->string('status', 20)->default('active');
            $t->timestamps();
            $t->unique(['student_id', 'class_id'], 'enroll_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
