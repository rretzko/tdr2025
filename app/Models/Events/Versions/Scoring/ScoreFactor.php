<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoreFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'version_id',
        'score_category_id',
        'factor',
        'abbr',
        'best',
        'worst',
        'interval_by',
        'multiplier',
        'tolerance',
        'order_by',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getCountByVersionId(int $versionId): int
    {
        $scoreCount = $this->where('version_id', $versionId)->count('id');

        if (!$scoreCount) {
            $eventId = Version::find($versionId)->event_id;
            $scoreCount = $this->where('event_id', $eventId)->count('id');
        }

        return $scoreCount;
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function scoreCategory(): BelongsTo
    {
        return $this->belongsTo(ScoreCategory::class);
    }
}
