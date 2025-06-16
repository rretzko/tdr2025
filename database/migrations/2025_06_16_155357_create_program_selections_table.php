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
        Schema::create('program_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Programs\Program::class)->constrained();
            $table->foreignIdFor(\App\Models\Libraries\Items\LibItem::class)->constrained();
            $table->foreignIdFor(\App\Models\Ensembles\Ensemble::class)->constrained();
            $table->integer('order_by')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_selections');
    }
};
