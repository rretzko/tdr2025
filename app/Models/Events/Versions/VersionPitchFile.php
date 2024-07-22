<?php

namespace App\Models\Events\Versions;

use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VersionPitchFile extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'version_id',
        'file_type',
        'voice_part_id',
        'url',
        'description',
        'order_by',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function voicePart(): BelongsTo
    {
        return $this->belongsTo(VoicePart::class);
    }
}
