<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\Ensembles\Members\MembersTableComponent;
use App\Livewire\Forms\AssetAssignmentForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Models\Students\Student;
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
    public array $inventoryEdits = [];
    public array $inventoryAdds = [];
    public array $inventoryErrors = [];

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

    public function save()
    {
        foreach ($this->inventories as $inventory) {
            $parts = explode('_', $inventory);
            $studentId = $parts[0];
            $assetId = $this->assetForm->assetIds[$parts[1]];
            Inventory::updateOrCreate(
                [
                    'ensemble_id' => $this->ensembleId,
                    'asset_id' => $assetId,
                ],
                [
                    'item_id' => '',
                    'assigned_to' => $studentId,
                    'status' => 'assigned',
                ]
            );
        }

        return $this->redirectRoute('members');
    }

    public function saveAndStay(): void
    {
        $this->reset('inventoryAdds', 'inventoryErrors');

        foreach ($this->inventoryEdits as $key => $inventoryId) {

            if ($this->isValid($key, $inventoryId)) {

                //$inventoryId may be system id or may be user's personal identification
                $inventory = $this->getInventoryObject($key, $inventoryId);
                $studentId = explode('_', $key)[0];

                $inventory->update(
                    [
                        'status' => 'assigned',
                        'assigned_to' => $studentId,
                    ]
                );

                $this->inventoryAdds[$key] = 'Added.';
            }
        }

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

    private function currentStudentAsset(string $key, string $inventoryId): bool
    {
        $parts = explode('_', $key);
        $assetKey = $parts[1];
        $asset = $this->ensembleAssets[$assetKey];
        $studentId = $parts[0];

        return Inventory::query()
            ->where('ensemble_id', $this->ensembleId)
            ->where('asset_id', $asset->id)
            ->where('status', 'assigned')
            ->where('assigned_to', $studentId)
            ->where(function ($query) use ($asset, $inventoryId) {
                $query->where('id', $inventoryId)
                    ->orWhere('item_id', $inventoryId);
            })
            ->exists();
    }

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

    /**
     * Prevalidated object must exist, so no testing for that here
     *
     * @param string $key
     * @param string $inventoryId
     * @return Inventory
     */
    private function getInventoryObject(string $key, string $inventoryId): Inventory
    {
        $assetKey = explode('_', $key)[1];
        $asset = $this->ensembleAssets[$assetKey];

        return Inventory::query()
            ->where('ensemble_id', $this->ensembleId)
            ->where('asset_id', $asset->id)
            ->where('status', 'available')
            ->where(function ($query) use ($asset, $inventoryId) {
                $query->where('id', $inventoryId)
                    ->orWhere('item_id', $inventoryId);
            })
            ->first();
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
     * @param string $key //ex. 17290_1 = studentid_$this->assets[key]
     * @param string $inventoryId //ex. 40 or 92d15L = inventories.id or inventories.item_id
     * @return bool
     */
    private function isValid(string $key, string $inventoryId): bool
    {
        //early exit
        if ($this->currentStudentAsset($key, $inventoryId)) {
            return false;
        }

        return ($this->isValidAsset($key, $inventoryId) && $this->isValidStudentMember($key));
    }

    private function isValidAsset(string $key, string $inventoryId): bool
    {
        $keys = explode('_', $key);
        $assetKey = $keys[1] ?? null;
        $studentId = $keys[0] ?? null;

        //early exit
        if (!isset($this->ensembleAssets[$assetKey])) {
            return false;
        }

        //ex Cummerbund, Gown, etc.
        $asset = $this->ensembleAssets[$assetKey];

        //early exit
        if (!$asset) {
            return false;
        }

        $inventory = Inventory::query()
            ->where('ensemble_id', $this->ensembleId)
            ->where('asset_id', $asset->id)
            ->where('status', 'available')
            ->where(function ($query) use ($asset, $inventoryId) {
                $query->where('id', $inventoryId)
                    ->orWhere('item_id', $inventoryId);
            })
            ->exists();

        if ($inventory) {
            return true;
        }

        $this->inventoryErrors[$key][] = "Inventory id #$inventoryId is either invalid or assigned.";
        return false;
    }

    private function isValidStudentMember(string $key): bool
    {
        $parts = explode('_', $key);
        $studentId = $parts[0] ?? null;

        //early exit
        if (!$studentId) {
            return false;
        }

        $studentMember = Student::query()
            ->join('ensemble_members', 'students.id', '=', 'ensemble_members.student_id')
            ->where('students.id', $parts[0])
            ->where('ensemble_members.ensemble_id', $this->ensembleId)
            ->exists();

        if ($studentMember) {
            return true;
        }

        $this->inventoryErrors[$key][] = 'Student id is invalid.';
        return false;
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
