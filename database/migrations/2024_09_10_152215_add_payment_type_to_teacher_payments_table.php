<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teacher_payments', function (Blueprint $table) {
            $table->enum('payment_type', ['cash', 'check', 'other', 'purchase order'])->after('fee_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_payments', function (Blueprint $table) {
            //
        });
    }
};
