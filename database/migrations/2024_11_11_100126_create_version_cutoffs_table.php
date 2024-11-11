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
        Schema::table('version_cutoffs', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Events\Versions\Version::class);
            $table->foreignIdFor(\App\Models\Students\VoicePart::class);
            $table->foreignIdFor(\App\Models\Events\EventEnsemble::class);
            $table->integer('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_ensembles', function (Blueprint $table) {
            //
        });
    }
};
