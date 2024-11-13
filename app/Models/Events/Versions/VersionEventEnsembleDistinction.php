<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Model;

class VersionEventEnsembleDistinction extends Model
{
    protected $fillable = [
        'by_grade',
        'by_score',
        'by_voice_part_id',
        'version_id',
    ];
}
