<?php

namespace App\Models\Events\Versions\Scoring;

use Illuminate\Database\Eloquent\Model;

class VersionCutoff extends Model
{
    protected $fillable = [
        'event_ensemble_id',
        'score',
        'version_id',
        'voice_part_id',
    ];
}
