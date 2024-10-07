<?php

namespace App\Models\Events\Versions;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public function getAdjudicationButtonsAllArrayAttribute(): array
    {
        $voicePartIds = RoomVoicePart::where('room_id', $this->id)
            ->pluck('voice_part_id')
            ->toArray();

        $candidates = DB::table('candidates')
            ->join('room_voice_parts', 'room_voice_parts.voice_part_id', '=', 'candidates.voice_part_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('candidates.version_id', $this->version_id)
            ->whereIn('candidates.voice_part_id', $voicePartIds)
            ->where('candidates.status', 'registered')
            ->distinct('candidates.id')
            ->select('candidates.id', 'candidates.ref', 'voice_parts.descr', 'voice_parts.abbr', 'voice_parts.order_by')
            ->orderBy('voice_parts.order_by')
            ->orderBy('candidates.id')
            ->get()
            ->toArray();

        return $candidates;
    }

    public function getAdjudicationButtonsIncompleteArrayAttribute(): array
    {
        return [];
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
            ->select('candidates.id', 'voice_parts.abbr', 'voice_parts.order_by')
            ->orderBy('voice_parts.order_by')
            ->orderBy('candidates.id')
            ->get()
            ->toArray();
    }

    public function getScoringFactorsAttribute()
    {
        $roomScoreCategoriesIds = $this->roomScoreCategories->pluck('score_category_id')->toArray();

        return ScoreFactor::query()
            ->whereIn('score_category_id', $roomScoreCategoriesIds)
            ->orderBy('score_factors.order_by')
            ->get();
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
