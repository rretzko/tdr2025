<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('version_participant_id');
            $table->string('role');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['version_id', 'version_participant_id', 'role'], 'uAll');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_roles');
    }
};
