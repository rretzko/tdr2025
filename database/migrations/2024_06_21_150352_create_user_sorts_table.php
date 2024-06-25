<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_sorts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->string('header');
            $table->string('column');
            $table->boolean('asc');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sorts');
    }
};
