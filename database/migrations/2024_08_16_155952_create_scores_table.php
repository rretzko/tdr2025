<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->constrained();
            $table->foreignId('candidate_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('score_category_id')->constrained();
            $table->tinyInteger('score_category_order_by');
            $table->foreignId('score_factor_id')->constrained();
            $table->tinyInteger('score_factor_order_by');
            $table->unsignedBigInteger('judge_id')->constrained();
            $table->tinyInteger('judge_order_by');
            $table->foreignId('voice_part_id')->constrained();
            $table->tinyInteger('voice_part_order_by');
            $table->tinyInteger('score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
