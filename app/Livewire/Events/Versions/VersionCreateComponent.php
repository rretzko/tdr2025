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

    }

    public function render()
    {
        return view('livewire..events.versions.version-create-component');
    }
}
