<?php

namespace App\Models\Events\Versions\Participations;

use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'version_id',
        'voice_part_id',
        'school_id',
        'voice_part_order_by',
        'score_count',
        'total',
        'accepted',
        'acceptance_abbr',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function voicePart(): BelongsTo
    {
        return $this->belongsTo(VoicePart::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
