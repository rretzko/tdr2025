<?php

namespace App\Models\Programs;

use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\Components\Voicing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class ProgramSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'act_id',
        'closer',
        'lib_item_id',
        'ensemble_id',
        'opener',
        'order_by',
        'program_id',
    ];

    public function ensemble(): BelongsTo
    {
        return $this->belongsTo(Ensemble::class);
    }

    public function getEnsembleNameAttribute(): string
    {
        return $this->ensemble->name;
    }

    public function getArtistBlockAttribute(): string
    {
        $artists = [
            'composer' => '',
            'arranger' => '(arr)',
            'wam' => '(words and music)',
            'words' => '(words)',
            'music' => '(music',
            'choreographer' => '(choreo)',
        ];
        $str = '';

        foreach ($artists as $role => $abbr) {

            $artist = $this->libItem->$role;

            if ($artist) {
                $str .= '<div>'.$artist->artist_name.' '.$abbr.'</div>';
            }
        }

        return $str;
    }

    public function getTitleAttribute(): string
    {
        $libItem = $this->libItem;
        return LibTitle::find($libItem->lib_title_id)->title;
    }

    /**
     * Returns voicing description ex. satb
     * @return string
     */
    public function getVoicingAttribute(): string
    {
        $libItem = $this->libItem;

        return Voicing::find($libItem->voicing_id)->descr;
    }

    public function libItem(): BelongsTo
    {
        return $this->belongsTo(LibItem::class);
    }
}
