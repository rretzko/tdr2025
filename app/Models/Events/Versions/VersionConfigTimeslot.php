<?php

namespace App\Models\Events\Versions;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class VersionConfigTimeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'start_time',
        'end_time',
        'duration',
    ];

    protected $casts = [
        'start_time' => 'datetime',
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
            $timeslots[] = Carbon::parse($start)->format('Y-m-d H:i:s'); //ex 2024-11-20 16:30:40
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
