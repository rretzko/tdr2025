<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voice_parts', function (Blueprint $table) {
            $table->id();
            $table->string('descr');
            $table->string('abbr');
            $table->integer('order_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_parts');
    }
};
