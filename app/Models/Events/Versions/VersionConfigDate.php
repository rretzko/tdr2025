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

    protected array $dates = [
        'version_date',
        'created_at',
        'updated_at',
    ];

//    protected $casts = ['version_date' => 'datetime'];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    protected function casts()
    {
        return [
            'version_date' => 'datetime',
        ];
    }
}
