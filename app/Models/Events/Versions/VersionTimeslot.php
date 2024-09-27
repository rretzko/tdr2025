<?php

namespace App\Models\Events\Versions;

use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionTimeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'school_id',
        'timeslot',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    protected function casts()
    {
        return [
            'timeslot' => 'timestamp',
        ];
    }
}
