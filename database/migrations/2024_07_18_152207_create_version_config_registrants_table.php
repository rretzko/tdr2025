<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_config_registrants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->unique();
            $table->boolean('eapplication')->default(0);
            $table->integer('audition_count')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_config_registrants');
    }
};
