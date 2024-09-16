<?php

namespace App\Models\Events\Versions;

use App\Models\Events\Versions\Scoring\RoomVoicePart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'room_name',
        'tolerance',
        'order_by',
    ];

    public function roomVoiceParts(): HasMany
    {
        return $this->hasMany(RoomVoicePart::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
