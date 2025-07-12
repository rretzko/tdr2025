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
        Schema::create('lib_item_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Libraries\Library::class)->constrained();
            $table->foreignIdFor(\App\Models\Libraries\Items\LibItem::class)->constrained();
            $table->foreignIdFor(\App\Models\Schools\Teacher::class)->constrained();
            $table->integer('rating')->default(1);
            $table->string('difficulty')->default('easy');
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->unique(['library_id', 'lib_item_id', 'teacher_id'], 'unique_all');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_item_ratings');
    }
};
