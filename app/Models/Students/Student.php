<?php

namespace App\Models\Students;

use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'voice_part_id',
        'class_of',
        'height',
        'birthday',
        'shirt_size'
    ];

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class)
            ->withPivot('active');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voicePart(): BelongsTo
    {
        return $this->belongsTo(VoicePart::class);
    }
}
