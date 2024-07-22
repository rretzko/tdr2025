<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_config_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->index();
            $table->string('date_type');
            $table->dateTime('version_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_config_dates');
    }
};
