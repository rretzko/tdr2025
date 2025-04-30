<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Libraries\Items\Components\Voicing;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lib_items', function (Blueprint $table) {
            $table->foreignIdFor(Voicing::class)->after('lib_title_id')->constrained();
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
