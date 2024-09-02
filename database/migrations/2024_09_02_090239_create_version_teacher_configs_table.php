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
        Schema::create('version_teacher_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Schools\Teacher::class)->constrained();
            $table->foreignIdFor(\App\Models\Events\Versions\Version::class)->constrained();
            $table->boolean('epayment_student')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_teacher_configs');
    }
};
