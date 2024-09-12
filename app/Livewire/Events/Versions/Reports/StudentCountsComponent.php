<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Exports\StudentCountsExport;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentCountsComponent extends BasePageReports
{
    public array $columnHeaders;

    public function mount(): void
    {
        parent::mount();

        $this->sortCol = 'users.last_name';
    }

    public function render()
    {
        return view('livewire..events.versions.reports.student-counts-component');
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

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new StudentCountsExport(
            $this->versionId,
        ), 'studentCounts.csv');
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'teacher', 'sortBy' => 'teacher'], //users.last_name
            ['label' => 'registrant', 'sortBy' => 'registrant'],
            ['label' => 'grade', 'sortBy' => 'classOf'],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
        ];
    }

    private function getRows(): Builder
    {
        return DB::table('candidates')
            ->where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'));
    }

    private function getSummaryCounts(): array
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
}
