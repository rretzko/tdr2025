<?php

namespace App\Models\Events\Versions\Scoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomScoreCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'score_category_id',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scoreCategory(): BelongsTo
    {
        return $this->belongsTo(ScoreCategory::class);
    }
}
