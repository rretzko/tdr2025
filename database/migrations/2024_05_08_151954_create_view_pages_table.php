<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('view_pages', function (Blueprint $table) {
            $table->id();
            $table->string('controller');
            $table->string('method');
            $table->string('page_name');
            $table->string('header')->unique();
            $table->timestamps();
            $table->unique(['controller', 'method']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('view_pages');
    }
};
