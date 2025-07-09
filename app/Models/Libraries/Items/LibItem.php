<?php

namespace App\Models\Libraries\Items;

use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\LibStack;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
        'voicing_id',
        'wam_id', //words-and-music
        'words_id',
    ];

    public function arranger(): HasOne
    {
        return $this->hasOne(Artist::class, 'id', 'arranger_id');
    }

    public function choreographer(): HasOne
    {
        return $this->hasOne(Artist::class, 'id', 'choreographer_id');
    }

    public function composer(): HasOne
    {
        return $this->hasOne(Artist::class, 'id', 'composer_id');
    }

    public function formatLocation(int $libraryId): string
    {
        $libItemLocation = LibItemLocation::query()
            ->where('lib_item_id', $this->id)
            ->where('library_id', $libraryId)
            ->first();

        if (!$libItemLocation) {
            return $this->id;
        }

        return $libItemLocation->formatLocation;
    }

    public function longLink(): string
    {
        $str = '<div>';

        //title + voicing descr
        $str .= "<div>".strtoupper($this->title)." ($this->voicingDescr)</div>";

        //composer
        if ($this->composer) {
            $str .= "<div class='ml-2'><b>".$this->composer->alpha_name."</b></div>";
        }

        //arranger
        if ($this->arranger) {
            $str .= "<div class='ml-2'>".$this->arranger->alpha_name." (arr)</div>";
        }

        //words-and-music
        if ($this->wam) {
            $str .= "<div class='ml-2'>".$this->wam->alpha_name." (words and music)</div>";
        }

        //words
        if ($this->words) {
            $str .= "<div class='ml-2'>".$this->words->alpha_name." (words)</div>";
        }

        //music
        if ($this->music) {
            $str .= "<div class='ml-2'>".$this->music->alpha_name." (music)</div>";
        }

        //choreographer
        if ($this->choreographer) {
            $str .= "<div class='ml-2'>".$this->choreographer->alpha_name." (choreo)</div>";
        }

        $str .= '</div>';

        return $str;
    }

    public function getTitleAttribute(): string
    {
        return LibTitle::find($this->lib_title_id)->title;
    }

    public function getVoicingDescrAttribute(): string
    {
        return Voicing::find($this->voicing_id)->descr ?? '';
    }

    public function music(): HasOne
    {
        return $this->hasOne(Artist::class, 'id', 'music_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function wam(): HasOne
    {
        return $this->hasOne(Artist::class, 'id', 'wam_id');
    }

    public function words(): HasOne
    {
        return $this->hasOne(Artist::class, 'id', 'words_id');
    }
}
