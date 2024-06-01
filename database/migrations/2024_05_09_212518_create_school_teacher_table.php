<?php

use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class)->constrained('schools');
            $table->foreignIdFor(Teacher::class)->constrained('teachers');
            $table->string('email')->comment('users work email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['school_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_teacher');
    }
};
