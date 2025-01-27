<?php

namespace App\Services;

use App\Models\Ensembles\Ensemble;
use App\Models\Students\Student;
use Illuminate\Support\Facades\DB;

class EnsembleNonMemberService
{
    private array $classOfs;
    private array $members;

    public function __construct(
        private readonly Ensemble $ensemble,
        private int               $schoolId,
        private int               $schoolYear
    )
    {
        $this->classOfs = $this->ensemble->classOfsArray($this->schoolYear);
        $this->members = $this->ensemble->allStatusStudentIdsArray($this->schoolYear);
    }

    public function getNamesArray($name): array
    {
        return Student::query()
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('school_student', 'school_student.student_id', '=', 'students.id')
            ->where('users.name', 'LIKE', '%' . $name . '%')
            ->where('school_student.school_id', $this->schoolId)
            ->whereIn('students.class_of', $this->classOfs)
            ->whereNotIn('students.id', $this->members)
            ->select(DB::raw("CONCAT(users.name, ' (', students.class_of, ')') as name_with_class"), 'students.id')
            ->pluck('name_with_class', 'students.id')
            ->toArray();
    }
}
