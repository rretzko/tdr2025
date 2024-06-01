<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pronoun_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@mfrholdings.com') && $this->hasVerifiedEmail();
    }

    public function isFounder(): bool
    {
        return $this->email === 'rick@mfrholdings.com';
    }

    /**
     * Returns true if user is a teacher with at least one school with a verified email address
     * - $this->id is found in teachers' table
     * - $this->id is found in school_teacher's table
     * - row in school_teacher' table contains a work email address (email)
     * - row in school_teacher' table contains a verified work email address (email_verified_at)
     * @return bool
     */
    public function isTeacher(): bool
    {
        return SchoolTeacher::query()
            ->join('teachers', 'teachers.id', '=', 'school_teacher.teacher_id')
            ->where('teachers.user_id', '=', $this->id)
            ->exists();
    }

    public function teacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Teacher::class);
    }
}
