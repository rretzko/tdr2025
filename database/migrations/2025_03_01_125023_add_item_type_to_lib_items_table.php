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
        Schema::table('lib_items', function (Blueprint $table) {
            $table->enum('item_type', ['book', 'cassette', 'cd', 'digital', 'dvd', 'medley', 'octavo', 'vinyl'])
                ->default('octavo')
                ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lib_items', function (Blueprint $table) {
            //
        });
    }
};
