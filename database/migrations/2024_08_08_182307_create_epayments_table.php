<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('epayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('school_id');
            $table->foreignId('user_id');
            $table->string('fee_type');
            $table->unsignedBigInteger('candidate_id')->default(0);
            $table->string('transaction_id');
            $table->integer('amount');
            $table->string('comments');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('epayments');
    }
};
