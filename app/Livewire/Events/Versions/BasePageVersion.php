<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\VersionForm;
use App\Services\CalcSeniorYearService;

class BasePageVersion extends BasePage
{
    public VersionForm $form;
    const STATUSES = ['active' => 'active', 'inactive' => 'inactive', 'closed' => 'closed', 'sandbox' => 'sandbox'];

    public function mount(): void
    {
        parent::mount();
    }

    public function render()
    {
        return view('livewire..events.versions.version-create-component');
    }

    protected function getSeniorClasses(): array
    {
        $service = new CalcSeniorYearService();

        return $service->getSeniorYearsArray();
    }
}
