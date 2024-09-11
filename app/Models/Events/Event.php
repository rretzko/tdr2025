<?php

namespace App\Models\Events;

use App\Models\Ensembles\Ensemble;
use App\Models\Events\Versions\Version;
use App\Models\Students\VoicePart;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'audition_count',
        'created_by',
        'ensemble_count',
        'frequency',
        'grades',
        'logo_file',
        'logo_file_alt',
        'max_registrant_count',
        'max_upper_voice_count',
        'name',
        'organization',
        'required_height',
        'required_shirt_size',
        'short_name',
        'status',
        'user_id',
    ];

    public function eventEnsembles(): HasMany
    {
        return $this->hasMany(EventEnsemble::class);
    }

    /**
     * Return the event version with the most recent senior_class_of value
     * or a new Version object if none found
     * @return Version
     */
    public function getCurrentVersion(): Version
    {
        return Version::query()
            ->where('event_id', $this->id)
            ->orderByDesc('senior_class_of')
            ->first() ?? new Version();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return a collection of unique VoicePart models used by $this->eventEnsembles
     * @return Collection
     */
    public function getVoicePartsAttribute(): Collection
    {
        return $this->eventEnsembles
            ->flatMap(fn($eventEnsemble) => $eventEnsemble->voiceParts)
            ->unique();
//        $voiceParts = collect();
//
//        foreach($this->eventEnsembles AS $eventEnsemble){
//
//            $voiceParts = $voiceParts->merge($eventEnsemble->voiceParts);
//
//        }
//
//        return $voiceParts->unique();
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Version::class)->orderByDesc('senior_class_of');
    }

}
