<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('header');
            $table->string('property');
            $table->string('value');
            $table->timestamps();
            $table->unique(['user_id', 'header', 'property'], 'uAll');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_configs');
    }
};
