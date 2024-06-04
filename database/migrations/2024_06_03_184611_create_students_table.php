<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(\App\Models\Students\VoicePart::class)->default(1)->constrained();
            $table->unsignedSmallInteger('class_of');
            $table->unsignedTinyInteger('height')->default(30);
            $table->date('birthday')->nullable();
            $table->enum('shirt_size', ['2xs', 'sx', 'sm', 'med', 'lg', 'xl', '2xl', '3xl', '4xl'])->default('med');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
