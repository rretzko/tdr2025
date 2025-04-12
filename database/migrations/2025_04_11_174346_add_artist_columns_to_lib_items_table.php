<?php

use App\Models\Libraries\Items\Components\Artist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lib_items', function (Blueprint $table) {
            $table->foreignIdFor(Artist::class, 'composer_id')->after('lib_title_id')->nullable();
            $table->foreignIdFor(Artist::class, 'arranger_id')->after('composer_id')->nullable();
            $table->foreignIdFor(Artist::class, 'words_id')->comment('or lyrics')->after('arranger_id')->nullable();
            $table->foreignIdFor(Artist::class, 'music_id')->after('words_id')->nullable();
            $table->foreignIdFor(Artist::class, 'wam_id')->comment('words-and-music')->after('music_id')->nullable();
            $table->foreignIdFor(Artist::class, 'choreographer_id')->after('wam_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lib_items', function (Blueprint $table) {
            //
        });
    }
};
