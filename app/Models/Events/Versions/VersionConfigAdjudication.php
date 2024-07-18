<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionConfigAdjudication extends Model
{
    use HasFactory;

    protected $fillable = [
        'averaged_scores',
        'judge_per_room_count',
        'room_monitor',
        'scores_ascending',
        'upload_count',
        'upload_types',
        'version_id',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
