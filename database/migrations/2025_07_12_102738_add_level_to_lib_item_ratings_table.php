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
        Schema::table('lib_item_ratings', function (Blueprint $table) {
            $table->string('level')->after('rating')->default('high-school');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lib_item_ratings', function (Blueprint $table) {
            Schema::dropIfExists('lib_item_ratings');
        });
    }
};
