<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Obligation;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigDate;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Schools\School;
use App\Models\UserConfig;
use App\Services\ConvertToUsdService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Money\Currency;

class ObligationsComponent extends BasePage
{
    public int $eventId = 0;
    public string $finalTeacherChanges = '';
    public string $obligationFile = '';
    public float $registrationFee = 0;
    public string $schoolName = '';
    public string $schoolCountyName = '';
    public int $versionId = 0;
    public int $versionSchoolCounty = 0;
    public string $versionShortName = '';

    public function mount(): void
    {
        parent::mount();

        $school = School::find(UserConfig::getValue('schoolId'));
        $this->schoolName = $school->name;
        $this->schoolCountyName = $school->county->name;

        $this->versionId = $this->dto['id'];
        $version = Version::find($this->versionId);
        $this->eventId = $version->event_id;
        $this->finalTeacherChanges = $this->getFinalTeacherChangeDate($version);
        $this->registrationFee = ConvertToUsdService::penniesToUsd($version->fee_registration);
        $this->versionShortName = $version->short_name;
        $this->versionSchoolCounty = $version->school_county;
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

        $this->setObligation('obligated');
    }

    private function getObligations(): string
    {
        $fileName = 'obligations.blade.php';
        $basePath = base_path(); //ex. C:\xampp\htdocs\staging\tdr2025
        $eventDirectory = $basePath
            .DIRECTORY_SEPARATOR
            .'resources'
            .DIRECTORY_SEPARATOR
            .'views'
            .DIRECTORY_SEPARATOR
            .'components'
            .DIRECTORY_SEPARATOR
            .'obligations'
            .DIRECTORY_SEPARATOR
            .$this->eventId;

        $versionDirectory = $eventDirectory
            .DIRECTORY_SEPARATOR
            .$this->versionId;

        $componentPath = 'components'.DIRECTORY_SEPARATOR.'obligations'.DIRECTORY_SEPARATOR.$this->eventId.DIRECTORY_SEPARATOR;

        //Log::info('event path: '.$eventDirectory.DIRECTORY_SEPARATOR.$fileName);
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

            //Log::info($fileName.' not found at: '.$versionDirectory.DIRECTORY_SEPARATOR.$fileName);
            //Log::info($fileName.' not found at: '.$eventDirectory.DIRECTORY_SEPARATOR.$fileName);

            abort(403, "Obligations page was not found.
            Please notify your Event Manager that you received this message.
            Please use your back key to return to the previous page.");
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

        $this->setObligation('invited');
    }

    private function getAcceptanceDate(): string
    {
        return Obligation::query()
            ->where('version_id', $this->versionId)
            ->where('teacher_id', auth()->user()->teacher->id)
            ->value('accepted') ?? '';
    }

    private function getFinalTeacherChangeDate(Version $version): string
    {
        $dt = VersionConfigDate::query()
            ->where('version_id', $version->id)
            ->where('date_type', 'final_teacher_changes')
            ->value('version_date');

        return Carbon::parse($dt)->format('l F jS'); //ex Thursday April 9th
    }

    private function setObligation(string $status)
    {
        $versionParticipant = VersionParticipant::query()
            ->where('version_id', $this->versionId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$versionParticipant) {
            $versionParticipant = VersionParticipant::create(
                [
                    'version_id' => $this->versionId,
                    'user_id' => auth()->id(),
                    'status' => 'invited'
                ]
            );
        }

        $versionParticipant->update(['status' => $status]);
    }
}
