<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;

class ObligationsComponent extends BasePage
{
    public string $obligationFile = '';
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['id'];
    }

    public function render()
    {
        return view('livewire..events.versions.participations.obligations-component',
            [
                'content' => $this->getObligations(),
            ]);
    }

    private function getObligations(): string
    {
        $basePath = base_path(); //ex. C:\xampp\htdocs\staging\tdr2025
        $eventId = Version::find($this->versionId)->event_id;
        $fileName = 'obligations.blade.php';
        $eventDirectory = $basePath.DIRECTORY_SEPARATOR.'resources\views\livewire\events\obligations'.DIRECTORY_SEPARATOR.$eventId;
        $versionDirectory = $eventDirectory.DIRECTORY_SEPARATOR.$this->versionId;

        //use the version-specific rendering of the obligations page if it exists
        if (file_exists($versionDirectory.DIRECTORY_SEPARATOR.$fileName)) {

            dd('version directory file found');
        } elseif (file_exists($eventDirectory.DIRECTORY_SEPARATOR.$fileName)) {

            $this->obligationFile = 'x-obligations\27\obligations';
        } else {

            dd('none found');
        }


        return 'Get the version obligations...';
    }
}
