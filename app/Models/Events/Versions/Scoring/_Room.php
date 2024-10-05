<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @deprecated */
class _Room extends Model
{

    use HasFactory;

    protected $fillable = [
        'version_id',
        'room_name',
        'tolerance',
        'order_by',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
