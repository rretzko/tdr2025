<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Models\SchoolStudent;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use App\Models\StudentTeacher;
use App\Models\User;
use App\Models\UserConfig;
use Carbon\Carbon;

class StudentDossierComponent extends BasePage
{
    public bool $myStudent = false;
    public string $profileCreationDateTime = '';
    public string $searchFor = '';
    public Student $student;
    public array $students = [];
    public int $teacherId = 0;
    public bool $unassigned = false;

    public function mount(): void
    {
        parent::mount();

        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
    }

    public function render()
    {
        return view('livewire.students.student-dossier-component');
    }

    public function clickAssignStudent(): void
    {
        $lastName = $this->student->user->last_name;
        $schoolId = UserConfig::getValue('schoolId');
        SchoolStudent::create(
            [
                'school_id' => $schoolId,
                'student_id' => $this->student->id,
                'active' => 1,
            ]
        );

        StudentTeacher::create(
            [
                'student_id' => $this->student->id,
                'teacher_id' => $this->teacherId,
            ]
        );

        //remove the "Add this student" button
        $this->reset('unassigned');
    }

    public function clickStudentNameButton(int $studentId): void
    {
        $this->reset('searchFor', 'students');

        $this->student = Student::firstOrCreate(
            [
                'id' => $studentId,
            ],
            [
                'user_id' => $studentId,
                'voice_part_id' => VoicePart::where('descr', 'Soprano I')->first()->id, //default value
                'class_of' => date('Y'),
            ]
        );

        $this->profileCreationDateTime = Carbon::parse($this->student->created_at)->format('D, M j, Y @ g:i:s a');

        $this->unassigned = $this->setUnassigned();
    }

    /**
     * return true if $this->student is NOT assigned to a school,
     * else return false
     * @return bool
     */
    private function setUnassigned(): bool
    {
        return (!SchoolStudent::where('student_id', $this->student->id)->exists());
    }

    public function updatedSearchFor(string $value): void
    {
        $likeValue = "%$value%";

        $this->students = Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('student_teacher', 'students.id', '=', 'student_teacher.student_id')
            ->where('users.name', 'LIKE', $likeValue)
            ->where('student_teacher.teacher_id', $this->teacherId)
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('users.name', 'users.id')
            ->get()
            ->map(function ($item) {
                $item['unassigned'] = 0;
                return $item;
            })
            ->toArray();

        $this->students = array_merge($this->students, $this->unassignedStudents($likeValue));
    }

    private function unassignedStudents(string $likeValue): array
    {
        return Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('school_student', 'students.id', '=', 'school_student.student_id')
            ->leftJoin('student_teacher', 'students.id', '=', 'student_teacher.student_id')
            ->where('users.name', 'LIKE', $likeValue)
            ->whereNull('school_student.id')
            ->whereNull('student_teacher.id')
            ->select('users.name', 'students.id')
            ->get()
            ->map(function ($item) {
                $item['unassigned'] = 1; //interject 'unassigned = 1' into array
                return $item;
            })
            ->toArray();

    }

}
