<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\AssetEnsemble;
use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Models\Schools\School;
use App\Rules\UniqueSchoolEnsembleName;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;

class InventoryForm extends Form
{
    public int $assetCount = 1;
    #[Validate('required', message: 'An asset must be selected')]
    #[Validate('min:1', message: 'An asset must be selected.')]
    public int $assetId = 0;
//    #[Validate('required', 'An id starting point must be entered')]
    #[Validate('int', 'A numeric id starting point must be entered')]
    public string $assetIdStartingPoint = '1';
    public string $assetName = '';

    public string $assetNamePlural = '';
    //#[Validate('nullable', 'string')]
    public string $color = '';
    //#[Validate('nullable', 'string')]
    public string $comments = '';
    public array $duplicateItemComments = [];
    #[Validate('min:1', message: 'An ensemble must be selected.')]
    public int $ensembleId = 0;
    // #[Validate('required', 'string')]
    public string $itemId = '';
    // #[Validate('nullable', 'string')]
    public string $size = '';
    //#[Validate('required', 'string', 'exists:INVENTORYSTATUSES')]
    public string $status = 'available';
    public string $sysId = 'new';
    public int $userId = 0;
    public string $creator = '';

    public function messages(): array
    {
        return [
            'assetId.exists' => 'An asset must be selected.',
        ];
    }

    public function addMultiple(): void
    {
//        $itemId = (int)$this->assetIdStartingPoint;

        for ($i = 0; $i < $this->assetCount; $i++) {

//            $itemId = $this->checkForDuplicateItemId($this->ensembleId, $this->assetId, $itemId);
//            if (count($this->duplicateItemComments)) {
//                $this->comments .= implode('Note: ', $this->duplicateItemComments);
//            }

            Inventory::create(
                [
                    'asset_id' => $this->assetId,
                    'color' => $this->color,
                    'comments' => $this->comments,
                    'ensemble_id' => $this->ensembleId,
//                    'item_id' => $itemId,
                    'size' => $this->size,
                    'status' => $this->status,
                    'updated_by' => auth()->id(),
                ]
            );

//            $itemId++;
//            $this->duplicateItemComments = [];
        }

    }

    public function setEnsemble(Ensemble $ensemble)
    {
        $this->abbr = $ensemble->abbr;
        $this->active = $ensemble->active;
        $this->description = $ensemble->description;
        $this->name = $ensemble->name;
        $this->schoolId = $ensemble->school_id;
        $this->shortName = $ensemble->short_name;
        $this->sysId = $ensemble->id;

        if ($ensemble->assets->isNotEmpty()) {
            $this->ensembleAssets = $ensemble->assets->pluck('id')->toArray();
        }
    }

    public function setInventoryItem(int $inventoryId)
    {
        $inventory = Inventory::find($inventoryId);

        $this->sysId = $inventory->id;
        $this->assetId = $inventory->asset_id;
        $this->ensembleId = $inventory->ensemble_id;
        $this->itemId = $inventory->item_id ?? '';
        $this->size = $inventory->size;
        $this->color = $inventory->color;
        $this->status = $inventory->status;
        $this->comments = $inventory->comments;
    }

    public function setSchool(School $school)
    {
        $this->schoolId = $school->id;
    }

    public function update()
    {
        $this->validate(
            [
                'assetId' => ['required', 'exists:assets,id'],
                'itemId' => ['nullable', 'string'],
                'size' => ['nullable', 'string'],
                'color' => ['nullable', 'string'],
                'status' => ['required', 'string'],
                'comments' => ['nullable', 'string', 'max:255'],
            ]
        );

        ($this->sysId === 'new')
            ? $this->add()
            : $this->updateInventory();
    }

    private function add(): void
    {
//        $itemId = $this->checkForDuplicateItemId($this->ensembleId, $this->assetId, $this->itemId);
//        if (count($this->duplicateItemComments)) {
//            $this->comments .= implode('Note: ', $this->duplicateItemComments);
//        }

        $inventory = Inventory::create(
            [
                'asset_id' => $this->assetId,
                'color' => $this->color,
                'comments' => $this->comments,
                'ensemble_id' => $this->ensembleId,
                'item_id' => $this->itemId,
                'size' => $this->size,
                'status' => $this->status,
                'user_id' => $this->userId,
                'updated_by' => auth()->id(),
            ]
        );

        //change systId from "new" to current id
        $this->sysId = $inventory->id;

        $this->duplicateItemComments = [];
    }

//    private function checkForDuplicateItemId(int $ensemble_id, int $assetId, int $itemId): int
//    {
//        while (Inventory::query()
//            ->where('ensemble_id', $ensemble_id)
//            ->where('asset_id', $assetId)
//            ->where('item_id', $itemId)
//            ->exists()) {
//            //record duplicate-found action
//            $this->duplicateItemComments[] = 'Duplicate item id: ' . $itemId . ' was found.  The item id has been
//            changed to: ' . ($itemId + 1) . '.';
//
//            //increment $itemId and try again
//            $itemId++;
//        }
//
//        return $itemId;
//    }

    #[NoReturn] private function updateInventory(): void
    {
        $inventory = Inventory::find($this->sysId);

        $inventory->update(
            [
                'asset_id' => $this->assetId,
                'item_id' => $this->itemId,
                'size' => $this->size,
                'color' => $this->color,
                'comments' => $this->comments,
                'status' => $this->status,
                'user_id' => $this->userId,
                'updated_by' => auth()->id(),
            ]
        );
    }

}
