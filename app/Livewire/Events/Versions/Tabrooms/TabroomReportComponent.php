<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;

class TabroomReportComponent extends BasePage
{
    public string $displayReportData = 'byVoicePart';
    public bool $displayReport = true;
    public int $voicePartId = 0;
    public Collection $voiceParts;

    public function mount(): void
    {
        parent::mount();

        $this->voiceParts = $this->getVoiceParts();
        $this->voicePartId = $this->voiceParts->first()->id;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getVoiceParts(): Collection
    {
        $versionId = UserConfig::getValue('versionId');
        return Version::find($versionId)->event->voiceParts;
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-report-component');
    }

    #[NoReturn] public function clickButton(string $type): void
    {
        $this->displayReport = !$this->displayReport;
        $this->displayReportData = $type;
    }
}
