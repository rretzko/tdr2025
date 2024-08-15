<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('score_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->string('descr');
            $table->tinyInteger('order_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('score_categories');
    }
};
