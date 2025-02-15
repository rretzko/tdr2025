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
        Schema::create('lib_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Schools\Teacher::class)->constrained();
            $table->string('title')->unique();
            $table->string('alpha')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_titles');
    }
};
