<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Programs\ProgramSelection;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('program_addendums', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProgramSelection::class)->constrained();
            $table->string('addendum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_addendums');
    }
};
