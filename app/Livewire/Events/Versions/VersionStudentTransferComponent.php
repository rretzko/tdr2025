<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;


class VersionStudentTransferComponent extends BasePage
{
    public int $schoolIdFrom = 0;
    public int $schoolIdTo = 0;
    public array $schools = [];
    public int $teacherIdFrom = 0;
    public int $teacherIdTo = 0;
    public array $teacherTos = [];

    public function mount(): void
    {
        parent::mount();

        $this->schools = $this->getSchools();
    }

    public function render()
    {
        return view('livewire..events.versions.version-student-transfer-component',
            [
                'teacherFroms' => $this->getTeacherFroms(),
            ]);
    }

    private function getSchools(): array
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

    private function getTeacherFroms(): array
    {
        return SchoolTeacher::query()
            ->join('teachers', 'teachers.id', '=', 'school_teacher.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('school_id', $this->schoolIdFrom)
            ->select('teachers.id AS id', 'users.name')
            ->get()
            ->toArray();
    }
}
