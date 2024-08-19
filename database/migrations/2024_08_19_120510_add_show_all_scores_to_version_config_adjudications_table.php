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
        Schema::table('version_config_adjudications', function (Blueprint $table) {
            $table->boolean('show_all_scores')->default(1)->after('alternating_scores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('version_config_adjudications', function (Blueprint $table) {
            //
        });
    }
};
