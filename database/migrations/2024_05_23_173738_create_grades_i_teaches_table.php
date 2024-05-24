<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grades_i_teaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->index();
            $table->foreignId('teacher_id')->index();
            $table->unsignedTinyInteger('grade');
            $table->timestamps();
            $table->unique(['school_id', 'teacher_id', 'grade'], 'uAll');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades_i_teaches');
    }
};
