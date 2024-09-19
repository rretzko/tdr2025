<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Livewire\BasePage;
use Illuminate\Support\Facades\DB;

class AdjudicationPaperBackupComponent extends BasePage
{
    public array $columnHeaders = [];
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->sortCol = 'rooms.name';
        $this->sortColLabel = 'room';
        $this->versionId = $this->dto['versionId'];
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'room', 'sortBy' => 'room'],
            ['label' => 'paper', 'sortBy' => null],
            ['label' => 'csv', 'sortBy' => null],
            ['label' => 'checklist', 'sortBy' => null],
        ];
    }

    public function render()
    {
        return view('livewire..events.versions.reports.adjudication-paper-backup-component',
            [
                'rows' => $this->getRows(),
            ]
        );
    }

    private function getRows(): array
    {
        return DB::table('rooms')
            ->where('rooms.version_id', $this->versionId)
            ->select('rooms.id', 'rooms.room_name', 'rooms.order_by')
            ->orderBy('rooms.order_by')
            ->get()
            ->toArray();
    }

    public function pdf(string $pdfType, int $roomId): void
    {
        dd($pdfType.': '.$roomId);
        return;
    }
}
