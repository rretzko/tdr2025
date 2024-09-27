<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_timeslots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('school_id');
            $table->timestamp('timeslot');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_timeslots');
    }
};
