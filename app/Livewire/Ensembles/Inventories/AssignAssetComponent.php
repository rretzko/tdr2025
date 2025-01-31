<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\Ensembles\Members\MembersTableComponent;
use App\Models\Ensembles\Ensemble;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssignAssetComponent extends MembersTableComponent
{
    public Ensemble $ensemble;
    public int $ensembleId = 0;
    public Collection $ensembleAssets;
    public array $ensembleClassOfs = [];
    public array $ensembles = [];
    public int $srYear = 0;

    public function mount(): void
    {
        parent::mount();

        $this->hasFilters = false;

        $this->ensembles = $this->getEnsembles();

        if (!$this->srYear) {
            $service = new CalcSeniorYearService();
            $this->srYear = $service->getSeniorYear();
        }

        if (!$this->ensembleId) {
            $this->ensembleId = array_key_first($this->ensembles);
            $this->ensemble = Ensemble::find($this->ensembleId);
            $this->ensembleClassOfs = $this->ensemble->classOfsArray($this->srYear);
            $this->ensembleAssets = $this->ensemble->assets;
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

    public function updatedEnsembleId(): void
    {
        $this->ensemble = Ensemble::find($this->ensembleId);
        $this->ensembleClassOfs = $this->ensemble->classOfsArray($this->srYear);
        $this->ensembleAssets = $this->ensemble->assets;
    }

    public function updatedSrYear(): void
    {
        $this->ensembleClassOfs = $this->ensemble->classOfsArray($this->srYear);
    }

    private function getColumnHeaders(): array
    {
        $assetNames = $this->getAssetNames();

        $headers = [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'name', 'sortBy' => 'name'],
            ['label' => 'status', 'sortBy' => 'status'],
            ['label' => 'grade', 'sortBy' => 'grade'],
        ];

        // If there are no asset names, return the default headers
        if (empty($assetNames)) {
            return $headers;
        }

        // Map asset names to header arrays
        $assetHeaders = array_map(fn($name) => ['label' => substr($name, 0, 8), 'sortBy' => ''],
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

        return $this->ensembleAssets->pluck('name')->toArray();
    }

    private function getRows(): array
    {
        $schoolId = UserConfig::getValue('schoolId');

        return DB::table('ensemble_members')
            ->join('students', 'ensemble_members.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('ensemble_members.school_id', $schoolId)
            ->where('ensemble_members.ensemble_id', $this->ensembleId)
            ->where('ensemble_members.school_year', $this->srYear)
            ->whereIn('students.class_of', $this->ensembleClassOfs)
            ->where('users.name', 'LIKE', '%' . $this->search . '%')
            ->tap(function ($query) {
//                $this->filters->filterMembersByEnsemble($query);
//                $this->filters->filterMembersBySchoolYear($query);
            })
            ->select('users.name', 'users.first_name', 'users.middle_name', 'users.last_name',
                'students.class_of',
                'ensemble_members.school_year', 'ensemble_members.status', 'ensemble_members.office',
                'ensemble_members.id')
            ->selectRaw("
                CASE
                    WHEN ? > students.class_of THEN 'alum'
                    ELSE (12 - (students.class_of - ?))
                END AS calcGrade", [$this->srYear, $this->srYear]
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
