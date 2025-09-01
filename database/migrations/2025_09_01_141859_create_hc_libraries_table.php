<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hc_libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Programs\HonorChoirs\HcEvent::class)->constrained();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('artist')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_libraries');
    }
};
