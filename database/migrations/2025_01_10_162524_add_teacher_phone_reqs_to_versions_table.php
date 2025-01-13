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
        Schema::table('versions', function (Blueprint $table) {
            $table->boolean('teacher_phone_mobile')->default(1)->after('participation_contract');
            $table->boolean('teacher_phone_work')->default(1)->after('teacher_phone_mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('versions', function (Blueprint $table) {
            //
        });
    }
};
