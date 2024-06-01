<?php

namespace App\Models\Students;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'class_of',
        'user_id',
        'height',
        'birthday',
        'shirt_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function birthday(): BelongsTo
    {
        return $this->belongsTo(\datetime::class, 'birthday');
    }
}
