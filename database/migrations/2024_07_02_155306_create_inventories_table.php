<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained();
            $table->string('item_id');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->longText('comments')->nullable();
            $table->string('status');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->unique(['asset_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
