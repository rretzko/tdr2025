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
        Schema::create('lib_item_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Libraries\Library::class)->constrained();
            $table->foreignIdFor(\App\Models\Libraries\Items\LibItem::class)->constrained();
            $table->string('location1')->nullable();
            $table->string('location2')->nullable();
            $table->string('location3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
