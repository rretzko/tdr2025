<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->index();
            $table->foreignId('teacher_id')->index();
            $table->string('name');
            $table->unique(['school_id', 'teacher_id', 'name'], 'uAll');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
};
