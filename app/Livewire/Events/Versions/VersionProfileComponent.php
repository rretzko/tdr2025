<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\Forms\VersionProfileForm;

class VersionProfileComponent extends BasePageVersion
{
    public VersionProfileForm $form;

    public function mount(): void
    {
        parent::mount();

        if ($this->dto['id']) {

            $this->form->setProfile($this->dto['id']);
        } else {

            if ($this->form->setProfileClone()) {

                $this->showSuccessIndicator = true;
            }
        }

        $this->form->setSeniorClassId();
    }

    public function render()
    {
        return view('livewire..events.versions.version-profile-component',
            [
                'seniorClasses' => $this->getSeniorClasses(),
                'statuses' => self::STATUSES,
            ]);
    }

    public function save()
    {
        $version = $this->form->update($this->dto['id']);

        $this->showSuccessIndicator = true;

        $this->successMessage = 'The version has been added.';

        $this->storeVersionId($version);

        return redirect()->route('version.show', ['version' => $version]);
    }
}
