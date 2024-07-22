<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('version_pitch_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->string('file_type');
            $table->foreignId('voice_part_id');
            $table->string('url');
            $table->string('description');
            $table->tinyInteger('order_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_pitch_files');
    }
};
