<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('judges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('room_id');
            $table->foreignId('user_id');
            $table->enum('status_type', [
                'assigned', 'exempt', 'completed', 'delegated', 'left early', 'no show', 'substitute'
            ])->default('assigned');
            $table->enum('judge_type', [
                'head judge', 'judge 2', 'judge 3', 'judge 4', 'judge monitor', 'monitor', 'exempt'
            ])->default('judge 2');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('judges');
    }
};
