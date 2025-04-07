<?php

namespace App\Livewire\Forms;

use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigMembership;
use App\Models\Events\Versions\VersionConfigRegistrant;
use App\Models\Events\Versions\VersionEventEnsembleOrder;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionConfigsForm extends Form
{
    // adjudication vars
    public bool $alternatingScores = true;
    public bool $averagedScores = false;
    public string $fileTypes;
    public int $fileUploadCount = 1;
    public int $firstEnsembleId = 7;
    public int $judgeCount = 1;
    public bool $roomMonitor = false;
    public int $scoresAscending = 0;
    public bool $showAllScores = true;
    public string $sysId = 'new';

    //registrants vars
    public bool $eapplication = false;
    public int $auditionCount = 1;

    //membership vars
    public bool $membershipCard = false;
    public string $validThru = ''; //expecting a date

    //advisory vars
    public array $advisories = [];


    public function setRowAdjudication(int $versionId): void
    {
        //check of a VersionConfigAdjudication row exists
        $vca = VersionConfigAdjudication::where('version_id', $versionId)
            ->first();

        //else check if a previous $vca version exists.
        // if so, clone it
        if (!$vca && $this->canClonePreviousVersion($versionId)) {
            $vca = $this->clonePreviousVersion($versionId);
        }

        //if neither of the above (first version of a new event),
        //create a fresh $vsa using default values
        if (!$vca) {
            $vca = VersionConfigAdjudication::create(['version_id' => $versionId]);
        }

        if (VersionConfigAdjudication::where('version_id', $versionId)->exists()) {

            $vca = VersionConfigAdjudication::where('version_id', $versionId)->first();

        }

        $this->alternatingScores = $vca->alternating_scores ?? false;
        $this->averagedScores = $vca->averaged_scores ?? false;
        $this->fileTypes = $vca->upload_types ?? '';
        $this->fileUploadCount = $vca->upload_count ?? 1;
        $this->judgeCount = $vca->judge_per_room_count ?? 1;
        $this->roomMonitor = $vca->room_monitor ?? 0;
        $this->scoresAscending = $vca->scores_ascending ?? 0;
        $this->showAllScores = $vca->show_all_scores ?? 1;
        $this->sysId = $vca->id;
        $this->firstEnsembleId = $this->getFirstEnsembleId($versionId);
    }

    public function setRowAdvisory(int $versionId): void
    {
        //reset var
        $this->advisories = [];

        $version = VersionConfigAdjudication::where('version_id', $versionId)->first();

        $this->advisories[] = '<p>The following will be used to determine ensemble
<b>eligibility</b> in this event\'s ensemble(s)
<ul><li>Voice Part</li><li>Grade</li></ul></p>';

        $this->advisories[] = ($version && $version->alternating_scores)
            ? '<p>In addition to scoring, the following will be used to
determine <b>participation</b> in the event\'s ensemble(s):
<ul>
<li>One cutoff score will be set per voice part. Participants will then be assigned to
alternating ensembles by score.
Individual scores may contain multiple individuals.</li>
</ul></p>'
            : '<p>One cutoff score will be set per voice part and per ensemble. The ensemble\'s
cutoff score inclusively will determine the ensemble participants per voice part.  Individual
scores may contain multiple individuals.';

    }

    public function setRowMembership(int $versionId): void
    {
        if (VersionConfigMembership::where('version_id', $versionId)->exists()) {

            $mbr = VersionConfigMembership::where('version_id', $versionId)->first();

        } else { //use default values

            $mbr = VersionConfigMembership::create(['version_id' => $versionId]);
        }

        $this->membershipCard = $mbr->membership_card ?? false;
        $this->validThru = Carbon::parse($mbr->valid_thru)->format('Y-m-d');
    }

    public function setRowRegistrants(int $versionId): void
    {
        if (VersionConfigRegistrant::where('version_id', $versionId)->exists()) {

            $vcr = VersionConfigRegistrant::where('version_id', $versionId)->first();

        } else { //use default values

            $vcr = VersionConfigRegistrant::create(['version_id' => $versionId]);
        }

        $this->eapplication = $vcr->eapplication ?? false;
        $this->auditionCount = $vcr->audition_count ?? 1;
    }

    public function updateAdjudication(int $versionId)
    {
        VersionConfigAdjudication::where('version_id', $versionId)
            ->update(
                [
                    'upload_count' => $this->fileUploadCount,
                    'upload_types' => $this->cleanFileTypes(),
                    'judge_per_room_count' => $this->judgeCount,
                    'room_monitor' => $this->roomMonitor,
                    'averaged_scores' => $this->averagedScores,
                    'scores_ascending' => $this->scoresAscending,
                    'alternating_scores' => $this->alternatingScores,
                    'show_all_scores' => $this->showAllScores,
                ]
            );

        $this->updateVersionEventEnsembleOrders($versionId);
    }

    public function updateMembership(int $versionId)
    {
        VersionConfigMembership::where('version_id', $versionId)
            ->update(
                [
                    'membership_card' => $this->membershipCard,
                    'valid_thru' => $this->validThru,
                ]
            );
    }

    public function updateRegistrants(int $versionId)
    {
        VersionConfigRegistrant::where('version_id', $versionId)
            ->update(
                [
                    'eapplication' => $this->eapplication,
                    'audition_count' => $this->auditionCount,
                ]
            );
    }

    private function canClonePreviousVersion(int $versionId): bool
    {
        $eventId = Version::find($versionId)->event_id;
        $event = Event::find($eventId);
        $versions = $event->versions();

        return (bool) $versions->count();
    }

    private function clonePreviousVersion(int $versionId): VersionConfigAdjudication
    {
        $eventId = Version::find($versionId)->event_id;
        $event = Event::find($eventId);
        //exclude current version
        $mostRecentVersion = $event->versions()
            ->whereNot('id', $versionId)
            ->first();

        if ($mostRecentVersion) {
            $mostRecentVca = VersionConfigAdjudication::where('version_id', $mostRecentVersion->id)
                ->first();
        } else {
            $vca = VersionConfigAdjudication::create(['version_id' => $versionId]);
            $mostRecentVca = $vca;
        }

        return VersionConfigAdjudication::create(
            [
                'version_id' => $versionId,
                'upload_count' => $mostRecentVca->upload_count ?? 0,
                'upload_types' => $mostRecentVca->upload_types,
                'judge_per_room_count' => $mostRecentVca->judge_per_room_count,
                'room_monitor' => $mostRecentVca->room_monitor,
                'averaged_scores' => $mostRecentVca->averaged_scores,
                'scores_ascending' => $mostRecentVca->scores_ascending,
                'show_all_scores' => $mostRecentVca->show_all_scores,
                'alternating_scores' => $mostRecentVca->alternating_scores,
            ]
        );
    }

    private function cleanFileTypes(): string
    {
        $parts = explode(',', $this->fileTypes);

        $trimmedParts = array_map('trim', $parts);

        return implode(',', $trimmedParts);
    }

    private function getFirstEnsembleId(int $versionId): int
    {
        //early exit
        if (!$this->alternatingScores) {
            return 0;
        }

        $defaultEventEnsemblesId = Version::find($versionId)->event->eventEnsembles->first()->id;

        return VersionEventEnsembleOrder::query()
            ->where('version_id', $versionId)
            ->where('order_by', 1)
            ->first()
            ->event_ensemble_id ?? $defaultEventEnsemblesId;
    }

    private function updateVersionEventEnsembleOrders(int $versionId): void
    {
        if ($this->alternatingScores) {

            $veeos = VersionEventEnsembleOrder::where('version_id', $versionId)->get();

            if ($veeos) {

                foreach ($veeos as $veeo) {
                    $veeo->order_by = ($veeo->event_ensemble_id === $this->firstEnsembleId)
                        ? 1 : 2;
                    $veeo->save();
                }
            }
        }
    }
}
