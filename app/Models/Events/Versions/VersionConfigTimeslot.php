<?php

namespace App\Models\Events\Versions;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionConfigTimeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'start_time',
        'end_time',
        'duration',
    ];

    /**
     * Return array of all timeslot timestamps based on $start_time, $end_time and $duration
     * @return array
     */
    public function buildTimeslots(): array
    {
        $start = Carbon::createFromTimestamp($this->start_time ?? date('NOW'), 'America/New_York');
        $end = Carbon::createFromTimestamp($this->end_time ?? date('NOW'), 'America/New_York');
        $duration = $this->duration ?? 15;
        $timeslots = [];

        while ($start < $end) {
            $timeslots[] = $start;
            $start->addMinutes($duration);
        }

        return $timeslots;
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    protected function casts()
    {
        return [
            'start_time' => 'timestamp',
            'end_time' => 'timestamp',
        ];
    }
}
