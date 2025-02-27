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
        Schema::create('version_county_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Events\Versions\Version::class)->index()->constrained();
            $table->foreignIdFor(\App\Models\Events\Versions\VersionParticipant::class)->constrained();
            $table->foreignIdFor(\App\Models\County::class)->constrained();
            $table->timestamps();
            $table->unique(['version_id', 'county_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_county_assignments');
    }
};
