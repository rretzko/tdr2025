<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VersionRole extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'version_id',
        'version_participant_id',
        'role',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function versionParticipant(): BelongsTo
    {
        return $this->belongsTo(VersionParticipant::class);
    }
}
