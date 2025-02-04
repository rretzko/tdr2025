<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\Ensembles\Members\MembersTableComponent;
use App\Livewire\Forms\AssetAssignmentForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssignAssetComponent extends MembersTableComponent
{
    public array $availables = [];
    public AssetAssignmentForm $assetForm;
    public bool $displayAssetAssignmentForm = false;
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

        $this->availables = $this->setAvailables($this->ensemble->assets);
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

    public function clickName(int $studentId)
    {
        $this->assetForm->setStudent($studentId, $this->ensembleId, $this->srYear);
        $this->toggleDisplayAssetAssignmentForm();

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

    /** END OF PUBLIC FUNCTIONS *******************************************************************************************/

    private function getAssetNames()
    {
        //early exit
        if (!$this->ensembleId) {
            return [];
        }

        return $this->ensembleAssets->pluck('name')->toArray();
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

    private function getEnsembles(): array
    {
        $schoolId = UserConfig::getValue('schoolId');

        return Ensemble::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
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
                'students.id AS studentId', 'students.class_of',
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
     * @param Collection $assets
     * @return array
     * array:5 [▼ // app\Livewire\Ensembles\Inventories\AssignAssetComponent.php:192
     * 1 => array:2 [▼
     * 0 => array:1 [▼
     * "assetString" => "5 |  |  |  | "
     * ]
     * 1 => array:1 [▼
     * "assetString" => "6 | 1 | folio | dark green | "
     * ]
     * ]
     * 2 => array:1 [▶]
     * 4 => array:10 [▼
     * 0 => array:1 [▶]
     * 1 => array:1 [▼
     * "assetString" => "14 | 1 |  |  | "
     * ]
     * 2 => array:1 [▶]
     * 3 => array:1 [▼
     * "assetString" => "16 | 3 |  |  | "
     * ]
     * 4 => array:1 [▶]
     * 5 => array:1 [▶]
     * 6 => array:1 [▼
     * "assetString" => "20 | 7 | large | light green | "
     * ]
     * 7 => array:1 [▶]
     */
    private function setAvailables(Collection $assets): array
    {
        $inventories = [];
        foreach ($assets as $asset) {
            $assetId = $asset->id;
            $inventories[$assetId] = Inventory::query()
                ->where('asset_id', $assetId)
                ->where('ensemble_id', $this->ensembleId)
                ->where('status', 'available')
                ->select(
                    DB::raw("CONCAT(id, ' | ', item_id, ' | ', size, ' | ', color, ' | ', comments) AS assetString")
                )
                ->orderBy('id')
                ->get()
                ->toArray();
        }

        return $inventories;

    }

    private function toggleDisplayAssetAssignmentForm(): void
    {
        $this->displayAssetAssignmentForm = (!$this->displayAssetAssignmentForm);
    }


}
