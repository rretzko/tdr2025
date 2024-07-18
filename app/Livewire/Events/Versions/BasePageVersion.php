<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\VersionForm;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;

class BasePageVersion extends BasePage
{
    const STATUSES = ['active' => 'active', 'inactive' => 'inactive', 'closed' => 'closed', 'sandbox' => 'sandbox'];

    protected const TABS = ['adjudication', 'registrants', 'membership', 'advisory'];

    public function mount(): void
    {
        parent::mount();
    }

    public function render()
    {
        return view('livewire..events.versions.version-profile-component');
    }

    protected function getSeniorClasses(): array
    {
        $service = new CalcSeniorYearService();

        return $service->getSeniorYearsArray();
    }

    protected function storeVersionId(Version $version): void
    {
        UserConfig::setProperty('versionId', $version->id);
    }
}
