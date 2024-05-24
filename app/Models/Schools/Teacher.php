<?php

namespace App\Models\Schools;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function isVerified(): bool
    {
        return SchoolTeacher::query()
            ->join('teachers', 'teachers.id', '=', 'school_teacher.teacher_id')
            ->where('teachers.user_id', '=', $this->id)
            ->whereNotNull('school_teacher.email')
            ->whereNotNull('school_teacher.email_verified_at')
            ->exists();
    }
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
