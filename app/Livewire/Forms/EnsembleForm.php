<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\AssetEnsemble;
use App\Models\Ensembles\Ensemble;
use App\Models\Schools\School;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EnsembleForm extends Form
{
    #[Validate('required', message: 'An abbreviation is required.')]
    public string $abbr = '';
    #[Validate('nullable', 'bool')]
    public bool $active = false;
    #[Validate('required', message: 'A description is required.')]
    public string $description = '';
    public array $ensembleAssets = [];
    #[Validate('required', message: 'The ensemble name is required.')]
    public string $name = '';
    public int $schoolId = 0;
    #[Validate('required', message: 'A short name is required.')]
    public string $shortName = '';
    public string $sysId = 'new';

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
                'name' => ['required', 'string'],
                'shortName' => ['required', 'string'],
                'abbr' => ['required', 'string'],
                'description' => ['required', 'string'],
                'active' => ['nullable', 'boolean'],
            ]
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

}