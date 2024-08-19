<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_voiceparts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained();
            $table->foreignId('voice_part_id')->constrained();
            $table->timestamps();
            $table->unique(['room_id', 'voice_part_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_voiceparts');
    }
};
