<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Carbon\Carbon;

class TabroomCloseAuditionsComponent extends BasePage
{
    public string $auditionCloseDateTime = '';
    public string $buttonLabel = 'Close Auditions';
    public Version $version;

    public function mount(): void
    {
        parent::mount();

        $versionId = UserConfig::getValue('versionId');
        $this->version = Version::find($versionId);
        if ($this->version->status === 'closed') {
            $this->auditionCloseDateTime = $this->getAuditionCloseDateTime();
        }
    }

    private function getAuditionCloseDateTime(): string
    {
        return Carbon::parse($this->version->updated_at)->format('D, d M, Y @ g:i:s a');
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-close-version-component');
    }

    public function clickButton()
    {
        if ($this->auditionCloseDateTime) {
            $this->version->update(['status' => 'active']);
            $this->reset('auditionCloseDateTime', 'buttonLabel');
        } else {
            $this->version->update(['status' => 'closed']);
            $this->setAuditionCloseDateTime();
            $this->buttonLabel = 'Re-open Event';
        }
    }
}
