<?php

namespace App\Models\Events\Versions;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'room_name',
        'tolerance',
        'order_by',
    ];

    public function judges(): HasMany
    {
        return $this->hasMany(Judge::class)
            ->with('user');
    }

    public function roomScoreCategories(): HasMany
    {
        return $this->hasMany(RoomScoreCategory::class);
    }

    public function getRegistrantsByIdAttribute(): Collection
    {
        return Candidate::query()
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('version_id', $this->version_id)
            ->whereIn('voice_part_id', $this->voicePartIds)
            ->where('status', 'registered')
            ->select('candidates.id', 'voice_parts.abbr')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return array of [id, voicePartAbbr]
     */
    public function getRegistrantsByIdArrayAttribute(): array
    {
        return Candidate::query()
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('version_id', $this->version_id)
            ->whereIn('voice_part_id', $this->voicePartIds)
            ->where('status', 'registered')
            ->select('candidates.id', 'voice_parts.abbr')
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    public function getVoicePartIdsAttribute(): array
    {
        return $this->roomVoiceParts->pluck('voice_part_id')->toArray();
    }

    public function roomVoiceParts(): HasMany
    {
        return $this->hasMany(RoomVoicePart::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
