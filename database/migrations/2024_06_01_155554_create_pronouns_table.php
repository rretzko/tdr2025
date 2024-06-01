<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pronouns', function (Blueprint $table) {
            $table->id();
            $table->string('descr');
            $table->string('intensive');
            $table->string('personal');
            $table->string('possessive');
            $table->string('object');
            $table->unsignedTinyInteger('order_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pronouns');
    }
};
