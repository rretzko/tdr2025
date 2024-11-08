<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Models\UserConfig;
use App\Models\UserFilter;
use App\Services\TransferStudentService;


class VersionStudentTransferComponent extends BasePage
{
    public int $schoolIdFrom = 0;
    public int $schoolIdTo = 0;
    public array $schools = [];
    public array $studentIdFroms = [];
    public int $teacherIdFrom = 0;
    public int $teacherIdTo = 0;
    public array $transferErrors = [];

    public function mount(): void
    {
        parent::mount();

        $this->schools = $this->getSchools();
    }

    public function render()
    {
        return view('livewire..events.versions.version-student-transfer-component',
            [
                'studentFroms' => $this->getStudents('from'),
                'studentTos' => $this->getStudents('to'),
                'teacherFroms' => $this->getTeachers('from'),
                'teacherTos' => $this->getTeachers('to'),
            ]);
    }

    public function transferStudents(): void
    {
        $this->reset('transferErrors');

        $service = new TransferStudentService(
            $this->schoolIdFrom,
            $this->teacherIdFrom,
            $this->schoolIdTo,
            $this->teacherIdTo
        );

        foreach ($this->studentIdFroms as $key => $studentId) {

            if ($service->transfer($studentId)) {
                unset($this->studentIdFroms[$key]);
            } else {
                $name = Student::find($studentId)->user->name;

                $this->transferErrors[] = "Unable to transfer $name at this time.";
            }
        }

        //remove filters from $this->teacher* to reset these values
        $userIdFrom = Teacher::where('id', $this->teacherIdFrom)->first()->user_id;
        $userIdTo = Teacher::where('id', $this->teacherIdTo)->first()->user_id;

        UserFilter::whereIn('user_id', [$userIdFrom, $userIdTo])
            ->whereIn('header', ['candidates', 'students'])
            ->delete();
    }

    public function getSchools(): array
    {
        return School::query()
            ->join('geostates', 'geostates.id', '=', 'schools.geostate_id')
            ->join('counties', 'counties.id', '=', 'schools.county_id')
            ->select('schools.id', 'schools.name', 'schools.city', 'schools.county_id', 'schools.geostate_id',
                'geostates.abbr', 'counties.name AS countyName')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    private function getStudents(string $fromTo): array
    {
        $qualifier = ucwords($fromTo);

        $schoolProperty = 'schoolId'.$qualifier;
        $schoolId = $this->$schoolProperty;

        $teacherProperty = 'teacherId'.$qualifier;
        $teacherId = $this->$teacherProperty;

        $seniorYear = Version::find(UserConfig::getValue('versionId'))->senior_class_of;

        return Student::query()
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('school_student', 'school_student.student_id', '=', 'students.id')
            ->join('student_teacher', 'student_teacher.student_id', '=', 'students.id')
            ->where('school_student.school_id', $schoolId)
            ->where('school_student.active', 1)
            ->where('student_teacher.teacher_id', $teacherId)
            ->where('students.class_of', '>=', $seniorYear)
            ->select('students.id', 'students.class_of', 'users.name')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();
    }

    private function getTeachers(string $fromTo): array
    {
        $property = 'schoolId'.ucwords($fromTo);

        return SchoolTeacher::query()
            ->join('teachers', 'teachers.id', '=', 'school_teacher.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('school_id', $this->$property)
            ->select('teachers.id AS id', 'users.name')
            ->get()
            ->toArray();
    }

}
