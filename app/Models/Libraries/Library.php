<?php

namespace App\Models\Libraries;

use App\Jobs\LibraryCreatedEmailJob;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Library extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function($model) {
            LibraryCreatedEmailJob::dispatch($model);
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getTeacherEmail(): string
    {
        $teacher = Teacher::find($this->teacher_id);

        return $teacher->user->email;
    }

    public function getTeacherName(): string
    {
        $teacher = Teacher::find($this->teacher_id);

        return $teacher->user->name;
    }

    public function user(): User
    {
        return User::find($this->teacher->user_id);
    }
}
