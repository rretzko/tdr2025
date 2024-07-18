<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionConfigMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'membership_card',
        'valid_thru',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    protected function casts()
    {
        return [
            'valid_thru' => 'date',
        ];
    }
}
