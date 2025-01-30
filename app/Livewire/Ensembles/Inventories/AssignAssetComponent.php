<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\Ensembles\Members\MembersTableComponent;
use App\Models\Ensembles\AssetEnsemble;
use App\Models\Ensembles\Ensemble;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;
use Illuminate\Support\Facades\DB;

class AssignAssetComponent extends MembersTableComponent
{
    public int $ensembleId = 0;
    public array $ensembles = [];
    public int $srYear = 0;

    public function mount(): void
    {
        parent::mount();

        $this->ensembles = $this->getEnsembles();

        if (!$this->ensembleId) {
            $this->ensembleId = array_key_first($this->ensembles);
        }
    }

    private function getEnsembles(): array
    {
        $schoolId = UserConfig::getValue('schoolId');

        return Ensemble::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function render()
    {
        return view('livewire..ensembles.inventories.assign-asset-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows(),
                'classOfs' => $this->getClassOfs(),
                'assets' => $this->getAssetNames(),
            ]
        );
    }

    private function getColumnHeaders(): array
    {
        $assetNames = $this->getAssetNames();

        $headers = [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'name', 'sortBy' => 'name'],
        ];

        // If there are no asset names, return the default headers
        if (empty($assetNames)) {
            return $headers;
        }

        // Map asset names to header arrays
        $assetHeaders = array_map(fn($name) => ['label' => $name, 'sortBy' => ''],
            $assetNames);

        // Merge default headers with asset headers
        return array_merge($headers, $assetHeaders);
    }

    private function getAssetNames()
    {
        //early exit
        if (!$this->ensembleId) {
            return [];
        }

        return DB::table('asset_ensemble')
            ->join('assets', 'asset_ensemble.asset_id', '=', 'assets.id')
            ->where('asset_ensemble.ensemble_id', $this->ensembleId)
            ->orderBy('assets.name')
            ->pluck('assets.name')
            ->toArray();
    }

    private function getRows(): array
    {
        $schoolIds = auth()->user()->teacher->schools->pluck('id')->toArray();

        $service = new CalcSeniorYearService();
        $srYear = $service->getSeniorYear();

        return DB::table('ensemble_members')
            ->join('students', 'ensemble_members.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('school_student', 'ensemble_members.student_id', '=', 'school_student.student_id')
            ->join('ensembles', 'ensemble_members.ensemble_id', '=', 'ensembles.id')
            ->join('voice_parts', 'ensemble_members.voice_part_id', '=', 'voice_parts.id')
            ->join('schools', 'school_student.school_id', '=', 'schools.id')
            ->whereIn('ensemble_members.school_id', $schoolIds)
            ->where('users.name', 'LIKE', '%' . $this->search . '%')
            ->tap(function ($query) {
                $this->filters->filterStudentsBySchools($query);
                $this->filters->filterMembersByEnsemble($query);
                $this->filters->filterMembersBySchoolYear($query);
            })
            ->select('users.name', 'users.first_name', 'users.middle_name', 'users.last_name',
                'schools.name AS schoolName', 'ensembles.name AS ensembleName',
                'voice_parts.descr AS voicePartDescr', 'students.class_of',
                'ensemble_members.school_year', 'ensemble_members.status', 'ensemble_members.office',
                'ensemble_members.id')
            ->selectRaw("
                CASE
                    WHEN ? > students.class_of THEN 'alum'
                    ELSE (12 - (students.class_of - ?))
                END AS calcGrade", [$srYear, $srYear]
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->get()
            ->toArray();
    }

    /**
     * Return array of class_ofs based on ensemble members
     * @return array
     */
    private function getClassOfs(): array
    {
        //early exit
        if (!$this->ensembleId) {
            return [];
        }

        return DB::table('ensemble_members')
            ->where('ensemble_id', $this->ensembleId)
            ->distinct()
            ->pluck('school_year', 'school_year')
            ->toArray();
    }


}
