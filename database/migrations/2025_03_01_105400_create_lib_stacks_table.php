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
        Schema::create('lib_stacks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Libraries\Library::class)->constrained();
            $table->foreignIdFor(\App\Models\Libraries\Items\LibItem::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_stacks');
    }
};
