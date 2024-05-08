<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('view_cards', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->string('color');
            $table->text('description');
            $table->string('heroicon');
            $table->string('label');
            $table->string('href');
            $table->tinyInteger('order_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_cards');
    }
};
