<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audition_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->unique();
            $table->foreignId('version_id');
            $table->foreignId('voice_part_id');
            $table->foreignId('school_id');
            $table->tinyInteger('voice_part_order_by');
            $table->tinyInteger('score_count');
            $table->smallInteger('total');
            $table->boolean('accepted');
            $table->string('acceptance_abbr');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audition_results');
    }
};
