<?php

namespace App\Services;

use App\Models\EmergencyContact;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Participations\Signature;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigRegistrant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CandidateSummaryTableService
{
    private array $candidateIds = [];
    private Collection $candidateObjects;
    private array $candidates = [];
    private array $coteacherIds = [];
    private bool $eApplication = false;
    private bool $hasRecordings = false;
    private array $uploadTypes = [];
    private Version $version;

    public function __construct(private readonly int $schoolId, private readonly int $versionId)
    {
        $this->coteacherIds = CoTeachersService::getCoTeachersIds();
        $this->version = Version::find($this->versionId);
        $this->hasRecordings = ($this->version->upload_type !== 'none');

        $vca = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->uploadTypes = $vca ? explode(',', $vca->upload_types) : [];

        $vcr = VersionConfigRegistrant::where('version_id', $this->versionId)->first();
        $this->eApplication = $vcr ? $vcr->eapplication : 0;

        $this->init();
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        $this->candidates = $this->getCandidatesArray();

        $this->getCandidateStatus();

        $this->getEmergencyContactProperties();

        $this->getSignatureStatus();

        $this->getRecordingStatus();
    }

    private function getCandidatesArray(): array
    {
        $candidates = Candidate::query()
            ->with('student', 'student.user', 'voicePart')
            ->where('version_id', $this->versionId)
            ->where('school_id', $this->schoolId)
            ->whereIn('teacher_id', $this->coteacherIds)
            ->get()
            ->map(function ($candidate) {
                return [
                    'lastName' => $candidate->student->user->last_name,
                    'firstName' => $candidate->student->user->first_name,
                    'candidateId' => $candidate->id,
                    'studentId' => $candidate->student->id,
                    'userId' => $candidate->student->user->id,
                    'programName' => $candidate->program_name,
                    'voicePartAbbr' => $candidate->voicePart->abbr,
                    'emergencyContactId' => $candidate->emergency_contact_id,
                ];
            })
            ->toArray();

        sort($candidates);

        $this->candidateIds = array_column($candidates, 'candidateId');
        $this->candidateObjects = Candidate::whereIn('id', $this->candidateIds)->get()->keyBy('id');

        return $candidates;
    }

    private function getCandidateStatus(): void
    {
        // Update the status for each candidate
        foreach ($this->candidates as $key => $candidate) {
            $this->candidates[$key]['status'] = CandidateStatusService::getStatus(Candidate::find($candidate['candidateId']));
        }
    }

    private function getEmergencyContactProperties(): void
    {
        // Update the emergency contact properties for each candidate
        foreach ($this->candidates as $key => $candidate) {

            if ($candidate['emergencyContactId']) {
                $ec = EmergencyContact::find($candidate['emergencyContactId']);
                $this->candidates[$key]['emergencyContactId'] = $candidate['emergencyContactId'];
                $this->candidates[$key]['emergencyContactName'] = $ec->name;
                $this->candidates[$key]['emergencyContactEmail'] = $ec->email;
                $this->candidates[$key]['emergencyContactPhoneMobile'] = $ec->phone_mobile;
            } else {
                $this->candidates[$key]['emergencyContactId'] = $candidate['emergencyContactId'];
                $this->candidates[$key]['emergencyContactName'] = '';
                $this->candidates[$key]['emergencyContactEmail'] = '';
                $this->candidates[$key]['emergencyContactPhoneMobile'] = '';
            }
        }
    }

    private function getSignatureStatus(): void
    {
        // Update the signature status for each candidate
        foreach ($this->candidates as $key => $candidate) {

            $builder = Signature::where('candidate_id', $candidate['candidateId']);

            $this->candidates[$key]['hasSignature'] = ($this->eApplication)
                ? $builder->count('id') === 2
                : $builder->count('id') === 1;
        }
    }

    private function getRecordingStatus(): void
    {
        // Update the recording status for each candidate
        foreach ($this->candidates as $key => $candidate) {

            if (count($this->uploadTypes)) {

                foreach ($this->uploadTypes as $uploadType) {

                    $recording = Recording::query()
                        ->where('candidate_id', $candidate['candidateId'])
                        ->where('file_type', $uploadType)
                        ->first();

                    $uploaded = (bool) $recording && strlen($recording->url);
                    $approved = (bool) $recording && (strlen($recording->approved) && $recording->approved_by && ($recording->approved_by > 0));

                    $this->candidates[$key]['recordings'][] =
                        [
                            'uploadType' => $uploadType,
                            'uploaded' => $uploaded,
                            'approved' => $approved,
                        ];
                }
//if($candidate['programName'] === 'Sophia Cheenath'){
//    dd($this->candidates[$key]['recordings']);
//}
            } else { //no recordings expected
                $this->candidates[$key]['recordings'] = [];
            }


        }
    }

    public function getRows(): array
    {
        return $this->candidates;
    }
}
