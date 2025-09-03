<?php

use App\Models\Programs\HonorChoirs\HcEvent;
use App\Models\Schools\School;
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
        Schema::create('hc_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HcEvent::class)->constrained();
            $table->string('school_name');
            $table->string('instrument_name');
            $table->tinyInteger('instrument_order_by');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_participants');
    }
};
