<?php

namespace App\Models\Events\Versions;

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
