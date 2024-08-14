<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Obligation;
use App\Models\Events\Versions\Version;
use Carbon\Carbon;

class ObligationsComponent extends BasePage
{
    public int $eventId = 0;
    public string $obligationFile = '';
    public int $versionId = 0;
    public string $versionShortName = '';

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['id'];
        $version = Version::find($this->versionId);
        $this->eventId = $version->event_id;
        $this->versionShortName = $version->short_name;
    }

    public function render()
    {
        $accepted = $this->getAcceptanceDate();

        return view('livewire..events.versions.participations.obligations-component',
            [
                'content' => $this->getObligations(),
                'obligationAccepted' => $accepted,
                'acceptedDate' => Carbon::parse($accepted)->format('M j, Y h:i:s a'),
            ]);
    }

    public function acceptObligation(): void
    {
        $obligation = Obligation::updateOrCreate(
            [
                'version_id' => $this->versionId,
                'teacher_id' => auth()->user()->teacher->id,
            ],
            [
                'accepted' => Carbon::now(),
            ]
        );
    }

    private function getObligations(): string
    {
        $fileName = 'obligations.blade.php';
        $basePath = base_path(); //ex. C:\xampp\htdocs\staging\tdr2025
        $eventDirectory = $basePath.DIRECTORY_SEPARATOR.'resources\views\components\obligations'.DIRECTORY_SEPARATOR.$this->eventId;
        $versionDirectory = $eventDirectory.DIRECTORY_SEPARATOR.$this->versionId;

        $componentPath = 'components'.DIRECTORY_SEPARATOR.'obligations'.DIRECTORY_SEPARATOR.$this->eventId.DIRECTORY_SEPARATOR;
//dd($versionDirectory.DIRECTORY_SEPARATOR.$fileName);
//dd($eventDirectory.DIRECTORY_SEPARATOR.$fileName);
        //use the version-specific rendering of the obligations page if it exists
        if (file_exists($versionDirectory.DIRECTORY_SEPARATOR.$fileName)) {

            $this->obligationFile = $componentPath
                .$this->versionId
                .DIRECTORY_SEPARATOR
                .'obligations';
        } elseif (file_exists($eventDirectory.DIRECTORY_SEPARATOR.$fileName)) {

            $this->obligationFile = $componentPath
                .'obligations';
        } else {

            dd('none found at: '.$componentPath);
        }


        return 'Get the version obligations...';
    }

    public function rejectObligation(): void
    {
        $obligation = Obligation::updateOrCreate(
            [
                'version_id' => $this->versionId,
                'teacher_id' => auth()->user()->teacher->id,
            ],
            [
                'accepted' => null,
            ]
        );


    }

    private function getAcceptanceDate(): string
    {
        return Obligation::query()
            ->where('version_id', $this->versionId)
            ->where('teacher_id', auth()->user()->teacher->id)
            ->value('accepted') ?? '';
    }
}
