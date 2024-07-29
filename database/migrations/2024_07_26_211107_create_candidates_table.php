<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('ref', 12);
            $table->foreignId('student_id');
            $table->foreignId('version_id');
            $table->foreignId('school_id');
            $table->foreignId('teacher_id');
            $table->foreignId('voice_part_id');
            $table->enum('status', [
                'applied', 'eligible', 'no-app', 'preregistered',
                'prohibited', 'registered', 'removed', 'withdrew'
            ]);
            $table->string('program_name');
            $table->timestamps();
            $table->unique(['student_id', 'version_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
