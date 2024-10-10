<?php

namespace App\Services;

use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use Illuminate\Support\Facades\DB;

class SchoolsTableService
{
    private array $rows = [];

    public function __construct()
    {
        $this->init();
    }

    public function getTableRows(): array
    {
        return $this->rows;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        $this->rows = DB::table('school_teacher')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->join('counties', 'counties.id', '=', 'schools.county_id')
            ->where('teacher_id', auth()->id())
            ->whereNull('school_teacher.deleted_at')
            ->select(
                'schools.id AS schoolId',
                'schools.name AS schoolName',
                'schools.city AS city',
                'schools.postal_code AS postalCode',
                'counties.name AS countyName',
                'school_teacher.active',
                'school_teacher.email',
                'school_teacher.email_verified_at',
                DB::raw('(SELECT GROUP_CONCAT(grade ORDER BY grade ASC SEPARATOR ",") FROM school_grades WHERE school_grades.school_id=schoolId) AS gradesTaught'),
                DB::raw('(SELECT GROUP_CONCAT(grade ORDER BY grade ASC SEPARATOR ",") FROM grades_i_teaches WHERE grades_i_teaches.school_id=schoolId AND grades_i_teaches.teacher_id=school_teacher.teacher_id) AS gradesITeach'),
                DB::raw('(SELECT GROUP_CONCAT(subject ORDER BY subject ASC SEPARATOR ",") FROM teacher_subjects WHERE teacher_subjects.teacher_id=school_teacher.teacher_id AND teacher_subjects.school_id=school_teacher.school_id) AS subjects')
            )
            ->get()
            ->toArray();

        foreach ($this->rows as $row) {
            $school = School::find($row->schoolId);
            $teacher = Teacher::where('user_id', auth()->id())->first();
            $row->studentCount = $school->students->count();

            $st = SchoolTeacher::query()
                ->where('school_id', $row->schoolId)
                ->where('teacher_id', $teacher->id)
                ->first();

            $row->active = $st->active;
        }

    }


}
