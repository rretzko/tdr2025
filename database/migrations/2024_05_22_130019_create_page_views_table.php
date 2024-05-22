<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->foreignId('user_id')->constrained();
            $table->integer('view_count');
            $table->timestamps();
            $table->unique(['header', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
