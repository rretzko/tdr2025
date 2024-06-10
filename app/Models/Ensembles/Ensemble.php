<?php

namespace App\Models\Ensembles;

use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ensemble extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'short_name',
        'abbr',
        'description',
        'active',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
