<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('version_package_receiveds', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Version::class)->constrained();
            $table->foreignIdFor(School::class)->constrained();
            $table->boolean('received')->default(false);
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_package_receiveds');
    }
};
