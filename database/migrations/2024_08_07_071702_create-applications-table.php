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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Events\Versions\Participations\Candidate::class)->unique()->constrained();
            $table->foreignIdFor(\App\Models\Events\Versions\Version::class)->constrained();
            $table->dateTime('last_downloaded_at');
            $table->unsignedBigInteger('downloads');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
