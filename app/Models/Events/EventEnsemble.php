<?php

namespace App\Models\Events;

use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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

    public function getVoicePartsAttribute(): Collection
    {
        $voicePartIds = explode(',', $this->voice_part_ids);

        return VoicePart::find($voicePartIds);
    }
}
