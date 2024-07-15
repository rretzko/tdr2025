<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\VersionForm;

class BasePageVersion extends BasePage
{
    public VersionForm $form;

    public function mount(): void
    {
        parent::mount();
    }

    public function render()
    {
        return view('livewire..events.versions.version-create-component');
    }
}
