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
//            $table->foreignIdFor(\App\Models\User::class, 'created_by')->after('middle_name')->constrained();
            //for some reason, the above command generates the following error:
            //SQLSTATE[HY000]: General error: 1824 Failed to open the referenced table 'created_bies' (Connection: mysql, SQL: alter table `artists` add constraint `artists_created_by_foreign` foreign key (`created_by`) references `created_bies` (`id`))
            $table->unsignedBigInteger('created_by')->after('middle_name');
            $table->foreign('created_by')->references('id')->on('users');

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
