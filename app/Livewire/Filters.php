<?php

namespace App\Livewire;

use App\Models\Ensembles\Ensemble;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Form;

class Filters extends Form
{
    public array $ensemblesSelectedIds = [];
    public string $header = '';
    public array $schoolsSelectedIds = [];

    public function init(string $header)
    {
        $this->header = $header;

        //initially set ensembles filter to include ALL schools' ensembles

        foreach (auth()->user()->teacher->schools as $school) {
            foreach ($school->ensembles as $ensemble) {
                $this->ensemblesSelectedIds[] = $ensemble->id;
            }
        }

        //initially set schools filter to include ALL schools
        $this->schoolsSelectedIds = auth()->user()->teacher->schools->pluck('id')
            ->toArray();

    }

    public function apply($query)
    {
        return $query->whereIn('ensembles.school_id', $this->schoolsSelectedIds);
    }

    public function ensembles(): array
    {
        $a = [];

        foreach (auth()->user()->teacher->schools as $school) {

            foreach ($school->ensembles as $ensemble) {

                $a[$ensemble->id] = $ensemble->abbr.' ('.$ensemble->school->abbr.')';
            }
        }

        return $a;
    }

    public function filterStudentsByMyStudents($query)
    {
        $grades = auth()->user()->teacher->getGradesITeachArray();


    }

    public function filterStudentsBySchools($query)
    {
        return $query->whereIn('school_student.school_id', $this->schoolsSelectedIds);
    }

    public function filterMembersByEnsemble($query)
    {
        return $query->whereIn('ensemble_members.ensemble_id', $this->ensemblesSelectedIds);
    }

    public function schools(): array
    {
        return auth()->user()->teacher->schools->pluck('abbr', 'id')->toArray();
    }

    #[NoReturn] public function updatedSchoolsSelectedIds(): void
    {
        dd(__METHOD__);
    }
}
