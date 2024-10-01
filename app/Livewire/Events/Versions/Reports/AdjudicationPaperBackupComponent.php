<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Exports\AdjudicationBackupExport;
use App\Livewire\BasePage;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

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
            ['label' => 'xlsx', 'sortBy' => null],
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
        $rooms = [];
        $rooms[] = (object) ['id' => 0, 'room_name' => 'All Rooms', 'order_by' => 0];

        $singleRooms = DB::table('rooms')
            ->join('judges', 'judges.room_id', '=', 'rooms.id')
            ->where('rooms.version_id', $this->versionId)
            ->select('rooms.id', 'rooms.room_name', 'rooms.order_by',
                DB::raw('COUNT(judges.id) AS judgeCount'))
            ->orderBy('rooms.order_by')
            ->groupBy('rooms.id')
            ->groupBy('rooms.room_name')
            ->groupBy('rooms.order_by')
            ->get()
            ->toArray();

        return array_merge($rooms, $singleRooms);
    }

    public function export(int $roomId)
    {
        $rooms = $this->getRoomsForExport($roomId);
        $fileName = $this->getFileNameForExport($rooms);

        return Excel::download(new AdjudicationBackupExport(
            $rooms,
        ), $fileName);

    }

    public function pdf(string $pdfType, int $roomId)
    {
        $uri = ($pdfType === 'backup')
            ? '/pdf/adjudicationBackupPaper/'.$roomId
            : '/pdf/adjudicationMonitorChecklist/'.$roomId;

        return $this->redirect($uri);
    }

    private function getFileNameForExport(Collection $rooms): string
    {
        $fileNameExtension = 'Backup.xlsx';
        $fileName = 'allRooms';

        if ($rooms->count() === 1) {
            $fileName = Str::camel($rooms->first()->room_name);
        }

        return $fileName.$fileNameExtension;
    }

    private function getRoomsForExport(int $roomId): Collection
    {
        $version = Version::find($this->versionId);

        return ($roomId)
            ? $version->rooms()->with('judges')->where('id', $roomId)->get()
            : $version()->rooms->with('judges')->get();
    }
}
