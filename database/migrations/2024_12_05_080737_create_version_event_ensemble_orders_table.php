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
        Schema::create('version_event_ensemble_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Events\Versions\Version::class);
            $table->foreignIdFor(\App\Models\Events\EventEnsemble::class);
            $table->integer('order_by')->default(1);
            $table->timestamps();
            $table->unique(['version_id', 'event_ensemble_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_event_ensemble_orders');
    }
};
