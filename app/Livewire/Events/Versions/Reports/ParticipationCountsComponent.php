<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Exports\ParticipationCountsExport;
use App\Livewire\BasePage;
use App\Models\County;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ParticipationCountsComponent extends BasePage
{
    public array $columnHeaders = [];
    public Collection $counties;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->counties = County::orderBy('name')->get();
        $this->versionId = $this->dto['id'];
    }

    private function getColumnHeaders(): array
    {
        return [
            'county',
            'obligated',
            'participating',
            'students',
            'registration manager',
        ];
    }

    public function render()
    {
        return view('livewire..events.versions.reports.participation-counts-component',
            [
                'rows' => $this->getRows(),
            ]
        );
    }

    private function getRows(): array
    {
        $rows = [];
        foreach ($this->counties as $county) {
            $rows[] = [
                'name' => $county->name,
                'obligated' => $county->participantCount($this->versionId, 'obligated'),
                'participating' => $county->participantCount($this->versionId, 'participating'),
                'students' => $county->studentCount($this->versionId),
                'regMgrName' => $county->registrationManagerName($this->versionId)
            ];
        }

        return $rows;
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new ParticipationCountsExport(
            $this->getRows(),
        ), 'participationCounts.csv');
    }
}
