<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('candidate_id');
            $table->string('file_type');
            $table->unsignedBigInteger('uploaded_by')->comment('user_id');
            $table->dateTime('approved')->nullable();
            $table->unsignedBigInteger('approved_by')->comment('user_id')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->unique(['candidate_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordings');
    }
};
