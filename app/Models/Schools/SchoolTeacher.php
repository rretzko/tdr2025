<?php

namespace App\Models\Schools;

use App\Jobs\TeacherCreatedEmailJob;
use App\Models\User;
use App\ValueObjects\SchoolTeacherValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SchoolTeacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_teacher';

    protected $fillable = [
        'active',
        'email',
        'email_verified_at',
        'school_id',
        'teacher_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function($model) {
            $teacher = Teacher::find($model->teacher_id);
            TeacherCreatedEmailJob::dispatch($teacher);
        });
    }

    public function getGradesTaughtCsvAttribute(): string
    {
        return implode(', ', SchoolGrade::query()
            ->where('school_id', $this->school_id)
            ->pluck('grade')
            ->toArray());
    }

    public function getSchoolNameAttribute(): string
    {
        return School::find($this->school_id)->name;
    }

    public function getSchoolVoAttribute(): string
    {
        return SchoolTeacherValueObject::getVo($this);
    }

    public function getUserAttribute(): User
    {
        return User::find($this->teacher_id);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }


    public function updateGradesITeach(array $grades, int $schoolId): void
    {
        //clear the table of $this grades
        DB::table('grades_i_teaches')
            ->where('school_id', $this->school_id)
            ->where('teacher_id', $this->teacher_id)
            ->delete();

        foreach ($grades as $grade) {

            GradesITeach::create(
                [
                    'school_id' => $this->school_id,
                    'teacher_id' => $this->teacher_id,
                    'grade' => $grade,
                ]
            );
        }
    }
}
