<?php

use App\Models\Programs\HonorChoirs\HcOrganization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hc_events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HcOrganization::class)->constrained();
            $table->string('name');
            $table->smallInteger('year_of');
            $table->string('program_link');
            $table->string('image_link');
            $table->string('video_link');
            $table->timestamps();
            $table->comment('Honor Choir Events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_events');
    }
};
