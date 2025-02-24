<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\coregistrationManagerForm;
use App\Models\County;
use App\Models\Events\Versions\Version;

class CoregistrationManagersComponent extends BasePage
{
    public coregistrationManagerForm $form;
    public array $counties = [];
    public array $participants = [];
    public bool $showForm = true;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->counties = $this->getCounties();
        $this->versionId = $this->dto['id'];
        $this->participants = $this->getParticipants();

    }

    private function getCounties(): array
    {
        return County::orderBy('name')->pluck('name', 'id')->toArray();
    }

    private function getParticipants(): array
    {
        return Version::find($this->versionId)->participantsArray();
    }

    public function render()
    {
        return view('livewire..events.versions.coregistration-managers-component');
    }

    public function addCoregistrationManager(): void
    {
        $this->showForm = true;
    }

    public function saveCoregistrationManager()
    {
        if ($this->form->add($this->versionId)) {
            $this->successMessage = 'New coregistration manager added';
            $this->reset('showForm');
        }

        return $this->redirect('version/coregistrationManagers');
    }
}
