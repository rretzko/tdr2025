<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Events\Versions\Version;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Judge extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'room_id',
        'user_id',
        'judge_role',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
