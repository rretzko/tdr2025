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
        Schema::table('lib_item_docs', function (Blueprint $table) {
            $table->unique(['library_id', 'lib_item_id', 'user_id', 'url'], 'uAll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lib_item_docs', function (Blueprint $table) {
            //
        });
    }
};
