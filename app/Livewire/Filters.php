<?php

namespace App\Livewire;

use App\Models\Schools\School;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Form;

class Filters extends Form
{
    public string $header = '';
    public array $schoolsSelectedIds = [];

    public function init(string $header)
    {
        $this->header = $header;


        //initially set schools filter to include ALL schools
        $this->schoolsSelectedIds = auth()->user()->teacher->schools->pluck('id')
            ->toArray();

    }

    public function apply($query)
    {
        return $query->whereIn('ensembles.school_id', $this->schoolsSelectedIds);
    }

    public function filterStudentsBySchools($query)
    {
        return $query->whereIn('school_student.school_id', $this->schoolsSelectedIds);
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
