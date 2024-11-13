<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Students\VoicePart;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationCardsComponent extends BasePage
{
    public string $candidateId = "";
    public array $eligibleVoiceParts = [];
    public int $schoolId = 0;
    public array $schools = [];
    public int $versionId = 0;
    public int $voicePartId = 0;
    public array $voiceParts = [];
    public string $test = "";

    public function mount(): void
    {
        $this->versionId = $this->dto['versionId'];
        $version = Version::find($this->versionId);
        $this->schools = $this->getSchools();
        $eligibleVoiceParts = $version->event->voiceParts;
        $this->voiceParts = $this->getVoiceParts($eligibleVoiceParts);
    }

    public function getSchools(): array
    {
        return $this->query()
            ->distinct('candidates.school_id')
            ->orderBy('schools.name')
            ->pluck('schools.name', 'schools.id')
            ->toArray();
    }

    private function query(): Builder
    {
        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered');
    }

    private function getVoiceParts(): array
    {
        return $this->query()
            ->distinct('candidates.voice_part_id')
            ->select('candidates.voice_part_id AS id', 'voice_parts.descr', 'voice_parts.order_by')
            ->orderBy('voice_parts.order_by')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire..events.versions.reports.registration-cards-component');
    }

    public function clickVoicePart(int $voicePartId): void
    {
        if (VoicePart::find($voicePartId)->exists()) {
            $this->pdf($voicePartId);
        }
    }

    public function updatedCandidateId(): void
    {
        $this->resetErrorBag('candidateId');

        //length of $this->candidateId === 6
        $candidateIdLength = (strlen($this->versionId) + 4);

        if ((Str::length($this->candidateId) == $candidateIdLength) &&
            $this->validateCandidateId()
        ) {
            $this->pdf($this->candidateId);
        } else {

            $this->addError('candidateId', 'ID must be '.$candidateIdLength.' characters.');
        }
    }

    public function updatedSchoolId(): void
    {
        $this->resetErrorBag('schoolId');

        if ($this->schoolId) {
            if (School::find($this->schoolId)) {
                $this->pdf($this->schoolId);
            } else {
                $this->addError('schoolId', 'School not found.');
            }
        }
    }

    private function validateCandidateId(): bool
    {
        $this->resetErrorBag();

        //assumption that candidate count will never exceed 9999 candidates per version
        $testVersionId = substr($this->candidateId, 0, -4);
        $validVersionId = ($testVersionId == $this->versionId);

        $exists = Candidate::where('id', $this->candidateId)->exists();

        //errors
        if (!$validVersionId) {
            $this->addError('candidateId', 'Invalid id: '.$this->candidateId);
        }
        if (!$exists) {
            $this->addError('candidateId', 'Id '.$this->candidateId.' not found');
        }

        return ($validVersionId && $exists);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function pdf(mixed $dto)
    {
        $root = "/pdf/registrationCards/";
        $uri = match ($dto) {
            $this->candidateId => $root.'candidates/'.$this->candidateId,
            $this->schoolId => $root.'schools/'.$this->schoolId,
            default => $root.'voiceParts/'.$dto,
        };

        return $this->redirect($uri);

    }
}
