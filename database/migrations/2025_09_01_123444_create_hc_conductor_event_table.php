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
        Schema::create('hc_conductor_event', function (Blueprint $table) {
            $table->foreignIdFor(App\Models\Programs\HonorChoirs\HcConductor::class)->constrained();
            $table->foreignIdFor(App\Models\Programs\HonorChoirs\HcEvent::class)->constrained();
            $table->primary(['hc_conductor_id', 'hc_event_id'], 'uAll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_conductor_event');
    }
};
