<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_score_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id');
            $table->foreignId('score_category_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_score_categories');
    }
};
