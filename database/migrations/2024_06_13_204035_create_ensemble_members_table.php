<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ensemble_members', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Schools\School::class)->index()->constrained();
            $table->foreignIdFor(\App\Models\Ensembles\Ensemble::class)->index()->constrained();
            $table->smallInteger('school_year');
            $table->foreignIdFor(\App\Models\Students\Student::class)->constrained();
            $table->smallInteger('class_of');
            $table->foreignIdFor(\App\Models\Students\VoicePart::class)->constrained();
            $table->string('office')->comment('def: BasePageMember');
            $table->string('status')->comment('def: BasePageMember');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['ensemble_id', 'student_id', 'school_year'], 'multi');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('ensemble_members');
    }
};
