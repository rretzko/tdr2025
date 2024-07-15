<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventEnsemble extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'event_id',
        'ensemble_name',
        'ensemble_short_name',
        'grades',
        'voice_part_ids',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
