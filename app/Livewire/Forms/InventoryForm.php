<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\AssetEnsemble;
use App\Models\Ensembles\Ensemble;
use App\Models\Schools\School;
use App\Rules\UniqueSchoolEnsembleName;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;

class InventoryForm extends Form
{


    #[Validate('required', message: 'An asset must be selected')]
    public int $assetId = 0;
    #[Validate('nullable', 'string')]
    public string $color = '';
    #[Validate('nullable', 'string')]
    public string $comments = '';
    #[Validate('required', 'string')]
    public string $item_id = '';
    #[Validate('nullable', 'string')]
    public string $size = '';
    #[Validate('required', 'string', 'exists:INVENTORYSTATUSES')]
    public string $status = 'available';
    public string $sysId = 'new';
    public int $userId = 0;
    public string $creator = '';

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', Rule::unique('ensembles')->where(function ($query) {
                    return $query->where('school_id', $this->schoolId);
                })
            ]
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
//            [
//                'name' => ['required', 'string'],
//                'shortName' => ['required', 'string'],
//                'abbr' => ['required', 'string'],
//                'description' => ['required', 'string'],
//                'active' => ['nullable', 'boolean'],
//            ]
        );

        ($this->sysId === 'new')
            ? $this->add()
            : $this->updateEnsemble();
    }

    private function add(): void
    {
        $ensemble = Ensemble::create(
            [
                'school_id' => $this->schoolId,
                'name' => $this->name,
                'short_name' => $this->shortName,
                'abbr' => $this->abbr,
                'description' => $this->description,
                'active' => $this->active,
            ]
        );

        //change systId from "new" to current id
        $this->sysId = $ensemble->id;

        $this->updateEnsembleAssets();
    }

    private function updateEnsembleAssets(): void
    {
        if (is_numeric($this->sysId)) {

            $this->deleteExistingAssetCategories();

            $ensemble = Ensemble::find($this->sysId);

            foreach ($this->ensembleAssets as $assetId) {

                $ensemble->assets()->attach($assetId);
            }

        }
    }

    private function deleteExistingAssetCategories(): void
    {
        AssetEnsemble::where('ensemble_id', $this->sysId)->delete();
    }

    #[NoReturn] private function updateEnsemble(): void
    {
        $ensemble = Ensemble::find($this->sysId);

        $ensemble->update(
            [
                'school_id' => $this->schoolId,
                'name' => $this->name,
                'short_name' => $this->shortName,
                'abbr' => $this->abbr,
                'description' => $this->description,
                'active' => $this->active,
            ]
        );

        $this->updateEnsembleAssets();
    }

}
