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
            $table->enum('judge_role',
                ['head judge', 'judge 2', 'judge 3', 'judge 4', 'judge monitor', 'room monitor']);
            $table->timestamps();
            $table->unique(['version_id', 'room_id', 'user_id', 'judge_role'], 'uAll');
            $table->unique(['room_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('judges');
    }
};
