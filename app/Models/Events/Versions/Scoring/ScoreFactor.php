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
        /**
         * @todo WORKAROUND for Morris Area Choral Directors
         * @todo do refactoring to determine how an event which changes its scoring factors should be handled
         * @todo in the future for mixing previous scoring factors and new scoring factors
         * @todo i.e. for version 85 score_factor ids: [30,31,32,39,40,41,42] where
         * @todo [30,31,32] are used by previous versions but [39,40,41,42] are new scoring factors
         */
        if($versionId == 85){
            return 7;
        }

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
