<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id');
            $table->foreignId('student_id');
            $table->boolean('active')->default(0);
            $table->unique(['school_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_student');
    }
};
