<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_config_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->unique();
            $table->boolean('membership_card')->default(0);
            $table->date('valid_thru')->default('1960-01-01');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_config_memberships');
    }
};
