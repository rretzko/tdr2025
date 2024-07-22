<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigMembership;
use App\Models\Events\Versions\VersionConfigRegistrant;
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
    public int $judgeCount = 1;
    public bool $roomMonitor = false;
    public bool $scoresAscending = true;
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
        if (VersionConfigAdjudication::where('version_id', $versionId)->exists()) {

            $vca = VersionConfigAdjudication::where('version_id', $versionId)->first();

        } else { //use default values

            $vca = VersionConfigAdjudication::create(['version_id' => $versionId]);
        }

        $this->alternatingScores = $vca->alternating_scores ?? false;
        $this->averagedScores = $vca->averaged_scores ?? false;
        $this->fileTypes = $vca->upload_types ?? '';
        $this->fileUploadCount = $vca->upload_count ?? 1;
        $this->judgeCount = $vca->judge_per_room_count ?? 1;
        $this->roomMonitor = $vca->room_monitor ?? 0;
        $this->scoresAscending = $vca->scores_ascending;
        $this->sysId = $vca->id;
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
                ]
            );
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

    private function cleanFileTypes(): string
    {
        $parts = explode(',', $this->fileTypes);

        $trimmedParts = array_map('trim', $parts);

        return implode(',', $trimmedParts);
    }
}
