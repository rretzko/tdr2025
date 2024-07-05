<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Schools\Teacher::class)->constrained();
            $table->foreignIdFor(\App\Models\Schools\School::class)->constrained();
            $table->string('subject');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['teacher_id', 'school_id', 'subject'], 'uAll');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
    }
};
