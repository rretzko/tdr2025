<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id');
            $table->foreignId('emergency_contact_type_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone_home');
            $table->string('phone_mobile');
            $table->string('phone_work');
            $table->enum('best_phone', ['home', 'mobile', 'work']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
    }
};
