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
        Schema::table('artists', function (Blueprint $table) {
            $table->string('artist_name')->after('id');
            $table->string('alpha_name')->after('artist_name');
            $table->string('first_name')->after('alpha_name');
            $table->string('last_name')->after('first_name');
            $table->string('middle_name')->after('last_name');
            $table->foreignIdFor(\App\Models\User::class, 'created_by')->after('middle_name')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            //
        });
    }
};
