<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\SchoolStudent;
use App\Models\StudentTeacher;
use Illuminate\Support\Facades\DB;

class TransferStudentService
{
    public function __construct(
        private int $schoolIdFrom,
        private int $teacherIdFrom,
        private int $schoolIdTo,
        private int $teacherIdTo
    ) {
    }

    public function transfer($studentId)
    {
        return $this->transferStudent($studentId);
    }

    private function transferStudent($studentId)
    {
        try {
            $result = DB::transaction(function () use ($studentId) {

                //inactivate student at current school if different from new school
                $res1 = ($this->schoolIdFrom !== $this->schoolIdTo)
                    ? DB::update(
                        "update school_student SET active = 0 where school_id = $this->schoolIdFrom and student_id = $studentId")
                    : 1;

                //inactivate student at current teacher is unnecessary
                //do nothing

                //activate student at new school
                $res2 = ($this->schoolIdFrom !== $this->schoolIdTo)
                    ? (SchoolStudent::query()
                        ->where('student_id', $studentId)
                        ->where('school_id', $this->schoolIdTo)
                        ->exists())
                        ? DB::update("update school_student SET active = 1 where school_id = $this->schoolIdTo and student_id = $studentId")
                        : DB::insert("insert into school_student (school_id,student_id,active) VALUES ($this->schoolIdTo, $studentId, 1)")
                    : 1;

                //activate student at new teacher
                //delete the current student-teacher record
                DB::delete("delete from student_teacher where student_id = $studentId and teacher_id = $this->teacherIdFrom");

                //retain an existing or insert a new student-teacher record
                $res3 = ($this->teacherIdFrom === $this->teacherIdTo)
                    ? 1
                    : ((StudentTeacher::query()
                        ->where('student_id', $studentId)
                        ->where('teacher_id', $this->teacherIdTo)
                        ->exists())
                        ? 1
                        : DB::insert("insert into student_teacher (student_id, teacher_id) VALUES ($studentId, $this->teacherIdTo)"));

                //change candidate school, teacher, and statuses at current versions
                if ($this->FromCandidateExists($studentId)) {

                }

                return ($res1 && $res2 && $res3);
            });

            if ($result === 0) {

                return false;
            }

            //update was successful
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * Test to determine if a candidate exists
     * from currently open event versions
     * with school_id=$this->schoolIdFrom
     * @return bool
     */
    private function FromCandidateExists(int $studentId): bool
    {
        $candidates = Candidate::query()
            ->join('versions', 'versions.id', '=', 'candidates.version_id')
            ->where('candidates.student_id', $studentId)
            ->where('candidates.school_id', $this->schoolIdFrom)
            ->where('versions.status', 'active')
            ->get();

        return (bool) $candidates->count();
    }


}
