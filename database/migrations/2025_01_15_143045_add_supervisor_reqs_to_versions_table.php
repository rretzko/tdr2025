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
            $table->boolean('supervisor_name_required')->after('school_county')->default(0);
            $table->boolean('supervisor_email_required')->after('supervisor_name_required')->default(0);
            $table->boolean('supervisor_phone_required')->after('supervisor_email_required')->default(0);
            $table->boolean('supervisor_name_preferred')->after('supervisor_phone_required')->default(0);
            $table->boolean('supervisor_email_preferred')->after('supervisor_name_preferred')->default(0);
            $table->boolean('supervisor_phone_preferred')->after('supervisor_email_preferred')->default(0);
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
