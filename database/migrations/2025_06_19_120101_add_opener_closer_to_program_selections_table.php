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
        Schema::table('program_selections', function (Blueprint $table) {
            $table->boolean('opener')->default(false)->comment('section opener')->after('order_by');
            $table->boolean('closer')->default(false)->comment('section opener')->after('opener');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_selections', function (Blueprint $table) {
            //
        });
    }
};
