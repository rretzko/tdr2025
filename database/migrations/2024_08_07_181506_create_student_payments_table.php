<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('version_id')->constrained();
            $table->foreignId('school_id')->index()->constrained();
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->unsignedBigInteger('amount');
            $table->string('transaction_id');
            $table->string('comments');
            $table->enum('payment_type', ['cash', 'check', 'epayment']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
