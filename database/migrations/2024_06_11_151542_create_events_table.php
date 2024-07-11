<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->string('organization');
            $table->tinyInteger('audition_count')->default(1);
            $table->tinyInteger('max_registrant_count')->default(0);
            $table->tinyInteger('max_upper_voice_count')->default(0);
            $table->tinyInteger('ensemble_count')->default(1);
            $table->string('frequency')->default('annual');
            $table->string('grades');
            $table->enum('status', ['active', 'closed', 'inactive', 'sandbox'])->default('sandbox');
            $table->string('logo_file')->nullable();
            $table->string('logo_file_alt')->default('event logo image');
            $table->boolean('required_height')->default(0);
            $table->boolean('required_shirt_size')->default(0);
            $table->unsignedBigInteger('created_by')->comment('user id');
            $table->timestamps();
            $table->unique(['name', 'organization']);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
