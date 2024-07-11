<?php

namespace App\Models\Events;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'audition_count',
        'created_by',
        'ensemble_count',
        'frequency',
        'grades',
        'logo_file',
        'logo_file_alt',
        'max_registrant_count',
        'max_upper_voice_count',
        'name',
        'organization',
        'required_height',
        'required_shirt_size',
        'short_name',
        'status',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
