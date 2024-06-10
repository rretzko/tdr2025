<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\Ensemble;
use App\Models\Schools\School;
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
            : $this->update();
    }

    public function add(): void
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
    }
}
