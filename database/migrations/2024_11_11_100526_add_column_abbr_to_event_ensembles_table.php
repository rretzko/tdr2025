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
        Schema::table('event_ensembles', function (Blueprint $table) {
            $table->string('abbr')->after('ensemble_short_name')->default('xx');
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
