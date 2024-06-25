<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSort extends Model
{
    protected $fillable = [
        'user_id',
        'header',
        'column',
        'asc',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
