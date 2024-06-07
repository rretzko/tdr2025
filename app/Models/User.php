<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Services\JoinNamePartsIntoNameService;
use App\Services\UserNameService;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

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
        'prefix_name',
        'first_name',
        'middle_name',
        'last_name',
        'suffix_name',
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

//    protected static function boot(): void
//    {
//        parent::boot();
//
//        static::created(function ($user) {
//            app(UserNameService::class)->persistNameParts($user);
//        });
//
//        static::updating(function ($user) {
//            app(UserNameService::class)->persistNameParts($user);
//        });
//    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@mfrholdings.com') && $this->hasVerifiedEmail();
    }

    public function isFounder(): bool
    {
        return $this->email === 'rick@mfrholdings.com';
    }

    public function isStudent(): bool
    {
        return Student::where('user_id', $this->id)->exists();
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

    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * This should be called whenever a name part is updated
     */
    public function updateName(): void
    {
        $service = new JoinNamePartsIntoNameService($this);

        $this->update(['name' => $service->getName()]);
    }
}
