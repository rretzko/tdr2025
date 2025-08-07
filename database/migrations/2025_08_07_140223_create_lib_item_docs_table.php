<?php

use App\Models\Libraries\Items\LibItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Comment\Doc;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lib_item_docs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Libraries\Library::class)->constrained();
            $table->foreignIdFor(LibItem::class)->constrained();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->string('url');
            $table->string('label');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_item_docs');
    }
};
