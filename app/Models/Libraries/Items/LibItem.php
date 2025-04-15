<?php

namespace App\Models\Libraries\Items;

use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\LibStack;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LibItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'arranger_id',
        'choreographer_id',
        'composer_id',
        'lib_subtitle_id',
        'lib_title_id',
        'music_id',
        'wam_id', //words-and-music
        'words_id',
    ];

    public function getTitleAttribute(): string
    {
        return LibTitle::find($this->lib_title_id)->title;
    }

    public function composer(): Artist|null
    {
        if ($this->composer_id) {
            return Artist::where('id', $this->composer_id)->first();
        }

        return null;
    }
}
