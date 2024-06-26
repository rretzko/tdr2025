<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->string('header');
            $table->string('filter');
            $table->string('values');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_filters');
    }
};
