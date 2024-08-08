<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('epayment_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->unsignedBigInteger('version_id')->default(0);
            $table->string('epayment_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_payment_credentials');
    }
};
