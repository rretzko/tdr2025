<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->index();
            $table->unsignedTinyInteger('grade');
            $table->timestamps();
            $table->unique(['school_id', 'grade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_grades');
    }
};
