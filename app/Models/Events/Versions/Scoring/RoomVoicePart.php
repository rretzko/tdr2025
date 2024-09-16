<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomVoicePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'voice_part_id',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function voicePart(): BelongsTo
    {
        return $this->belongsTo(VoicePart::class);
    }
}
