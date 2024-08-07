<?php

namespace App\Models\Events\Versions\Participations;

use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'candidate_id',
        'version_id',
        'last_downloaded_at',
        'downloads'
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
