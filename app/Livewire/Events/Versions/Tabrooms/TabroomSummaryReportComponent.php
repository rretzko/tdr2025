<?php

declare(strict_types=1);

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\UserConfig;
use App\Services\VersionSummaryReportService;

class TabroomSummaryReportComponent extends BasePage
{
    public int $versionId = 0;
    public array $report  = [];

    public function mount(): void
    {
        parent::mount();

        $this->versionId = (int) UserConfig::getValue('versionId');

        $service      = new VersionSummaryReportService($this->versionId);
        $this->report = $service->getData();
    }

    public function render()
    {
        return view('livewire.events.versions.tabrooms.tabroom-summary-report-component');
    }
}
