<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('version_id');
            $table->foreignId('student_id');
            $table->foreignId('teacher_id');
            $table->string('program_name');
            $table->enum('candidate_type', [
                'applied', 'eligible', 'no-app', 'preregistered',
                'prohibited', 'registered', 'removed', 'withdrew'
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
