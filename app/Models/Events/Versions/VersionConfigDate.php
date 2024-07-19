<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionConfigDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'date_type',
        'version_date',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    protected function casts()
    {
        return [
            'version_date' => 'date',
        ];
    }
}
