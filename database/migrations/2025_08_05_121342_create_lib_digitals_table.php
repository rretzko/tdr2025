<?php

use App\Models\Libraries\Items\LibItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lib_digitals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LibItem::class)->constrained();
            $table->string('url')->nullable();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->timestamps();
            $table->unique(['lib_item_id', 'url'], 'uAll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_digitals');
    }
};
