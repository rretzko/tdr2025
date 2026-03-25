<?php

use App\Models\Events\Versions\Version;
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
        Schema::create('daily_registration_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Version::class)->constrained();
            $table->date('snapshot_date');
            $table->unsignedInteger('registered_candidates')->default(0);
            $table->unsignedInteger('registered_schools')->default(0);
            $table->json('voice_part_counts')->nullable();
            $table->timestamps();

            $table->unique(['version_id', 'snapshot_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_registration_stats');
    }
};
