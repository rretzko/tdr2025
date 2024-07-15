<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_management', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Events\Event::class)->constrained();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->enum('role', ['manager']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_management');
    }
};
