<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('user_id');
            $table->string('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_participants');
    }
};
