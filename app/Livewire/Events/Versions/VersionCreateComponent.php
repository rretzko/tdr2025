<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\Forms\VersionCreateForm;
use Livewire\Component;

class VersionCreateComponent extends BasePageVersion
{
    public VersionCreateForm $createForm;

    public function mount(): void
    {
        parent::mount();

        $this->createForm->setSeniorClassId();
    }

    public function render()
    {
        return view('livewire..events.versions.version-create-component',
            [
                'seniorClasses' => $this->getSeniorClasses(),
                'statuses' => self::STATUSES,
            ]);
    }

    public function save()
    {
        $version = $this->createForm->add($this->dto['id']);

        $this->showSuccessIndicator = true;

        $this->successMessage = 'The version has been added.';

        return redirect()->route('version.show', ['version' => $version]);
    }
}
