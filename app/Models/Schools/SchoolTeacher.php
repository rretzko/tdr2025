<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class SchoolTeacher extends Model
{
    use HasFactory;

    protected $table = 'school_teacher';

    protected $fillable = [
        'active',
        'email',
        'email_verified_at',
        'school_id',
        'teacher_id',
    ];

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
