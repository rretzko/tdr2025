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
        Schema::create('version_cutoffs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Events\Versions\Version::class);
            $table->foreignIdFor(\App\Models\Students\VoicePart::class);
            $table->foreignIdFor(\App\Models\Events\EventEnsemble::class);
            $table->integer('score');
            $table->timestamps();
            $table->unique(['version_id', 'voice_part_id', 'event_ensemble_id'], 'uall');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_cutoffs');
    }
};
