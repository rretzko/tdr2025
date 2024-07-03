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
    #[Validate('required', message: 'An asset must be selected')]
    #[Validate('min:1', message: 'An asset must be selected.')]
    public int $assetId = 0;
    //#[Validate('nullable', 'string')]
    public string $color = '';
    //#[Validate('nullable', 'string')]
    public string $comments = '';
    // #[Validate('required', 'string')]
    public string $itemId = '';
    // #[Validate('nullable', 'string')]
    public string $size = '';
    //#[Validate('required', 'string', 'exists:INVENTORYSTATUSES')]
    public string $status = 'available';
    public string $sysId = 'new';
    public int $userId = 0;
    public string $creator = '';

//    public function rules(): array
//    {
//        return [
//            'assetId' => ['required', 'exists:assets,id'],
//        ];
//        return [
//            'name' => [
//                'required', 'string', Rule::unique('ensembles')->where(function ($query) {
//                    return $query->where('school_id', $this->schoolId);
//                })
//            ]
//        ];
//
//    }

    public function messages(): array
    {
        return [
            'assetId.exists' => 'An asset must be selected.',
            'itemId.required' => 'An item id must be entered.',
        ];
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

    public function setSchool(School $school)
    {
        $this->schoolId = $school->id;
    }

    public function update()
    {
        $this->validate(
            [
                'assetId' => ['required', 'exists:assets,id'],
                'itemId' => ['required', 'string'],
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
        $inventory = Inventory::create(
            [
                'asset_id' => $this->assetId,
                'item_id' => $this->itemId,
                'size' => $this->size,
                'color' => $this->color,
                'comments' => $this->comments,
                'status' => $this->status,
                'user_id' => $this->userId,
            ]
        );

        //change systId from "new" to current id
        $this->sysId = $inventory->id;
    }

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
            ]
        );
    }

}
