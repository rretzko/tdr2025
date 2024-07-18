<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_config_adjudications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->unique();
            $table->integer('upload_count')->default(0);
            $table->string('upload_types')->comment('comma-separated values')->nullable();
            $table->integer('judge_per_room_count')->default(0);
            $table->boolean('room_monitor')->default(0);
            $table->boolean('averaged_scores')->default(0);
            $table->boolean('scores_ascending')->default(0);
            $table->boolean('alternating_scores')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_config_adjudications');
    }
};
