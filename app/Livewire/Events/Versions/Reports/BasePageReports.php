<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;

class BasePageReports extends BasePage
{
    public int $versionId;
    public Version $version;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $this->version = Version::find($this->versionId);
    }

    public function getSummaryColumnHeaders(): array
    {
        //early exit
        if (!$this->version->event) {
            return [];
        }

        $voiceParts = $this->version->event->voiceParts;

        return $voiceParts->pluck('abbr')->toArray();
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'classOf' => 'students.class_of',
            'count' => 'candidateCount',
            'name' => 'users.last_name',
            'registrant' => 'studentLastName',
            'recd' => 'version_package_receiveds.received',
            'school' => 'schools.name',
            'teacher' => 'teacher.last_name',
            'total' => 'schools.name', //use default and re-sort array in StudentCountsComponent
            'voicePart' => 'voice_parts.order_by',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];
    }

    protected function getSummaryCounts(): array
    {
        if (!$this->version->event) {
            return [];
        }

        $voicePartCounts = [];
        foreach ($this->version->event->voiceParts as $voicePart) {
            $voicePartCounts[] = $this->getCountOfRegistrants($voicePart->id);
        }

        return $voicePartCounts;
    }

    private function getCountOfRegistrants(int $voicePartId): int
    {
        return Candidate::query()
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where('candidates.voice_part_id', $voicePartId)
//            ->whereIn('candidates.school_id', $this->filters->participatingSchoolsSelectedIds)
//            ->whereIn('students.class_of', $this->filters->participatingClassOfsSelectedIds)
//            ->whereIn('candidates.voice_part_id', $this->filters->participatingVoicePartsSelectedIds)
            ->count('candidates.id');
    }

}
