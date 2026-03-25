<?php

declare(strict_types=1);

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyRegistrationStat extends Model
{
    protected $fillable = [
        'version_id',
        'snapshot_date',
        'registered_candidates',
        'registered_schools',
        'voice_part_counts',
    ];

    protected function casts(): array
    {
        return [
            'snapshot_date' => 'date',
            'voice_part_counts' => 'array',
        ];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
