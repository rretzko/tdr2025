<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_ensembles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->string('ensemble_name');
            $table->string('ensemble_short_name');
            $table->string('grades');
            $table->string('voice_part_ids');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['event_id', 'ensemble_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_ensembles');
    }
};
