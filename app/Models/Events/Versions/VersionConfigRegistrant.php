<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionConfigRegistrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'eapplication',
        'audition_count',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }
}
