<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->unsignedSmallInteger('class_of');
            $table->unsignedTinyInteger('height');
            $table->foreignId('birthday');
            $table->enum('shirt_size', ['2xs', 'xs', 's', 'm', 'l', 'xl', '2xl', '3xl']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
