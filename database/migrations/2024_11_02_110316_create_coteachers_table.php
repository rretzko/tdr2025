<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coteachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->foreignIdFor(\App\Models\Schools\School::class);
            $table->unsignedBigInteger('coteacher_id');
            $table->timestamps();
            $table->unique(['teacher_id', 'school_id', 'coteacher_id'], 'uAll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coteachers');
    }
};
