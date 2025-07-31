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
        Schema::create('lib_perusal_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Libraries\Library::class)->constrained();
            $table->foreignIdFor(\App\Models\Schools\Teacher::class)->constrained();
            $table->boolean('octavo')->default(false);
            $table->boolean('medley')->default(false);
            $table->boolean('book')->default(false);
            $table->boolean('digital')->default(false);
            $table->boolean('cd')->default(false);
            $table->boolean('dvd')->default(false);
            $table->boolean('cassette')->default(false);
            $table->boolean('vinyl')->default(false);
            $table->boolean('use_item_id')->nullable()->comment('for location value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusal_copies');
    }
};
