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
            $table->foreignIdFor(\App\Models\User::class, 'assigned_to')->comment('user id')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'updated_by')->comment('user id')->constrained();
            $table->timestamps();
            $table->unique(['asset_id', 'item_id'], ['asset_id', 'assigned_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
