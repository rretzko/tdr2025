<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergency_contact_types', function (Blueprint $table) {
            $table->id();
            $table->string('relationship');
            $table->foreignId('pronoun_id');
            $table->tinyInteger('order_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_contact_types');
    }
};
