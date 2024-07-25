<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_scorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->string('file_type');
            $table->string('segment');
            $table->string('abbr');
            $table->integer('best')->default(1);
            $table->integer('worst')->default(1);
            $table->integer('multiplier')->default(1);
            $table->integer('tolerance')->default(0);
            $table->integer('order_by')->default(1);
            $table->timestamps();
            $table->unique(['version_id', 'file_type', 'segment'], 'uAll');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_scorings');
    }
};
