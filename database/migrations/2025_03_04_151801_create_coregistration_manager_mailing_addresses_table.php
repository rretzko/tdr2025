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
        $this->down();
        Schema::create('coregistration_manager_mailing_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Events\Versions\Version::class)->constrained();
            $table->foreignIdFor(\App\Models\Events\Versions\VersionParticipant::class)
                ->constrained()
                ->name('fk_version_participant_id');
            $table->string('mailing_address')->default('no mailing address provided');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coregistration_manager_mailing_addresses');
    }
};
