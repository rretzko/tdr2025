<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionScoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'abbr', 'best', 'file_type', 'order_by', 'multiplier', 'segment',
        'tolerance', 'version_id', 'worst',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
