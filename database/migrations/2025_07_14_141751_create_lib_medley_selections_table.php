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
        Schema::create('lib_medley_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Libraries\Items\LibItem::class)->constrained();
            $table->foreignIdFor(\App\Models\Libraries\Items\Components\LibTitle::class)->constrained();
            $table->foreignIdFor(\App\Models\Schools\Teacher::class)->constrained();
            $table->timestamps();
            $table->unique(['lib_item_id', 'lib_title_id'], 'uAll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_medley_selections');
    }
};
