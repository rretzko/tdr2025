<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Ensembles\Asset::class)->constrained();
            $table->string('item_id');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->longText('comments')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('assigned_to')->comment('user id')->nullable();
            $table->unsignedBigInteger('updated_by')->comment('user id');
            $table->timestamps();
            $table->unique(['asset_id', 'item_id']);
            $table->unique(['asset_id', 'assigned_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
