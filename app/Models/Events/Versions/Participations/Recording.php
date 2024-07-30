<?php

namespace App\Models\Events\Versions\Participations;

use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recording extends Model
{
    use HasFactory;

    protected $fillable = [
        'approved',
        'approved_by',
        'candidate_id',
        'file_type',
        'uploaded_by',
        'url',
        'version_id',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    protected function casts()
    {
        return [
            'approved' => 'datetime',
        ];
    }
}
