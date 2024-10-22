<?php

namespace App\Models\Schools\Teachers;

use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'school_id',
        'subject',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Update model if at least on value in $subjects
     * @param  array  $subjects
     * @return void
     */
    public function updateTeacherSubject(array $subjects): void
    {
        if (!count($subjects)) {

            //early exit
            //Log::info('*** '.__METHOD__.': '.__LINE__.' ***');
            //Log::info('*** $subjects array is empty for teacher_id='.$this->teacher_id.' and school_id='.$this->school_id.'. ***');

        } else {

            //store values
            $teacherId = $this->teacher_id;
            $schoolId = $this->school_id;

            //delete existing rows
            DB::table('teacher_subjects')
                ->where('teacher_id', $teacherId)
                ->where('school_id', $schoolId)
                ->delete();

            //insert new rows
            foreach ($subjects as $subject) {

                $this->create(
                    [
                        'teacher_id' => $teacherId,
                        'school_id' => $schoolId,
                        'subject' => $subject,
                    ]
                );
            }
        }
    }
}
