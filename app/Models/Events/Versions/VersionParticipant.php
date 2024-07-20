<?php

namespace App\Models\Events\Versions;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VersionParticipant extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'version_id',
        'user_id',
        'status',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
