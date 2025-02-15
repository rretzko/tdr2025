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
        Schema::table('ensembles', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Schools\Teacher::class)->default(368)->after('school_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ensembles', function (Blueprint $table) {
            //
        });
    }
};
