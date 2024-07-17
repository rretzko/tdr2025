<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->string('name');
            $table->string('short_name');
            $table->smallInteger('senior_class_of');
            $table->string('status');
            $table->enum('upload_type', ['audio', 'none', 'video'])->default('none');
            $table->boolean('epayment_student')->default(0);
            $table->boolean('epayment_teacher')->default(0);
            $table->integer('fee_registration')->default(0);
            $table->integer('fee_on_site_registration')->default(0);
            $table->integer('fee_participation')->default(0);
            $table->integer('fee_epayment_surcharge')->default(0);
            $table->boolean('pitch_files_student')->default(1);
            $table->boolean('pitch_files_teacher')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['event_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versions');
    }
};
