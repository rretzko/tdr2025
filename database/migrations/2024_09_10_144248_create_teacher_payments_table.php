<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id');
            $table->foreignId('school_id');
            $table->foreignId('user_id');
            $table->enum('fee_type', ['cash', 'check', 'other', 'purchase_order']);
            $table->string('transaction_id');
            $table->integer('amount');
            $table->string('comments');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_payments');
    }
};
