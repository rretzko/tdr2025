<?php

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
        Schema::table('ensembles', function (Blueprint $table) {
            $table->string('voicing')->after('abbr')->default('mixed');
            $table->boolean('acappella')->after('voicing')->default(0);
            $table->boolean('jazz')->after('acappella')->default(0);
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
