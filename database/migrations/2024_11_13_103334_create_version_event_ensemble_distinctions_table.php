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
        Schema::create('version_event_ensemble_distinctions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Events\Versions\Version::class)->unique()->constrained();
            $table->boolean('by_grade')->default(0);
            $table->boolean('by_score')->default(0);
            $table->boolean('by_voice_part_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_event_ensemble_distinct_bies');
    }
};
