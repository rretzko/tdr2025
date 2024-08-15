<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('score_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('score_category_id');
            $table->string('factor');
            $table->string('abbr');
            $table->integer('best')->default(1);
            $table->integer('worst')->default(1);
            $table->integer('multiplier')->default(1);
            $table->integer('tolerance')->default(0);
            $table->integer('order_by')->default(1);
            $table->timestamps();
            $table->unique(['version_id', 'score_category_id', 'factor'], 'uAll');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_scorings');
    }
};
