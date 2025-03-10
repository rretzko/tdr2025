<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\AssetEnsemble;
use App\Models\Ensembles\Ensemble;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Rules\UniqueSchoolEnsembleName;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;

class EnsembleForm extends Form
{
    #[Validate('required', message: 'An abbreviation is required.')]
    public string $abbr = '';
    #[Validate('nullable', 'bool')]
    public bool $active = false;
    public bool $canEdit = false;
    public bool $canRemove = false;
    #[Validate('required', message: 'A description is required.')]
    public string $description = '';
    public array $ensembleAssets = [];
    public array $grades = [];
    //#[Validate('required', message: 'The ensemble name is required.')]
    public string $name = '';
    public int $schoolId = 0;
    public int $teacherId = 0;
    #[Validate('required', message: 'A short name is required.')]
    public string $shortName = '';
    public string $sysId = 'new';

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', Rule::unique('ensembles')->where(function ($query) {
                    return $query->where('school_id', $this->schoolId)
                        ->whereNot('id', $this->sysId);
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
        $this->teacherId = $ensemble->teacher_id;

        if ($ensemble->assets->isNotEmpty()) {
            $this->ensembleAssets = $ensemble->assets->pluck('id')->toArray();
        }

        $this->grades = explode(',', $ensemble->grades);

        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $this->canEdit = $this->getCanEditGate($ensemble, $teacherId);
        $this->canRemove = $this->getCanRemoveGate($ensemble, $teacherId);
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
                'teacher_id' => $this->getTeacherId(),
                'name' => $this->name,
                'short_name' => $this->shortName,
                'abbr' => $this->abbr,
                'description' => $this->description,
                'active' => $this->active,
                'grades' => implode(',', $this->grades),
            ]
        );

        //change sysId from "new" to current id
        $this->sysId = $ensemble->id;

        $this->updateEnsembleAssets();
    }

    private function deleteExistingAssetCategories(): void
    {
        AssetEnsemble::where('ensemble_id', $this->sysId)->delete();
    }

    private function getCanEditGate(Ensemble $ensemble, int $teacherId): bool
    {
        return $ensemble->teacher_id === $teacherId;
    }

    private function getCanRemoveGate(Ensemble $ensemble, int $teacherId): bool
    {
        return $ensemble->teacher_id === $teacherId;
    }

    private function getTeacherId(): int
    {
        return Teacher::where('user_id', auth()->id())->first()->id;
    }

    #[NoReturn] private function updateEnsemble(): void
    {
        $ensemble = Ensemble::find($this->sysId);
        sort($this->grades);

        $ensemble->update(
            [
                'school_id' => $this->schoolId,
                'teacher_id' => $this->getTeacherId(),
                'name' => $this->name,
                'short_name' => $this->shortName,
                'abbr' => $this->abbr,
                'description' => $this->description,
                'active' => $this->active,
                'grades' => implode(',', $this->grades),
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
