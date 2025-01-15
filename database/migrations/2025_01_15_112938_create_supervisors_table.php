<?php

use App\Models\Schools\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Schools\School::class)->constrained();
            $table->foreignIdFor(Teacher::class)->constrained();
            $table->string('supervisor_name');
            $table->string('supervisor_email');
            $table->string('supervisor_phone');
            $table->timestamps();
            $table->unique(['school_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
