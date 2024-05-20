<?php

namespace App\Models\Schools;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function schools(): BelongsToMany|null
    {
        return $this->belongsToMany(School::class)
            ->withPivot('active');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
