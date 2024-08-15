<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoreCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'descr',
        'order_by',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
