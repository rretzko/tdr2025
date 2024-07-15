<?php

namespace App\Livewire\Events;

use App\Exports\EventsExport;
use App\Livewire\BasePage;
use App\Models\Events\Event;
use App\Models\Events\EventManagement;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class EventsTableComponent extends BasePage
{
    public function mount(): void
    {
        parent::mount();

        $this->sortCol = 'events.name';
    }

    public function render()
    {
        $this->saveSortParameters();

        return view('livewire..events.events-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new EventsExport, 'events.csv');
    }

    public function remove(int $eventId): void
    {
        $event = Event::find($eventId);
        $eventName = $event->name;

        $eventManagement = EventManagement::query()
            ->where('event_id', $eventId)
            ->where('user_id', auth()->id())
            ->where('role', 'manager')
            ->first();

        $eventManagement->delete();

        $this->successMessage = $eventName.' has been removed from your roster.';
        $this->showSuccessIndicator = true;
    }

    /** END OF PUBLIC FUNCtiONS **************************************************/

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name/short name/org', 'sortBy' => 'name'], //users.last_name
            ['label' => 'grades', 'sortBy' => null],
            ['label' => 'ensembles', 'sortBy' => null],
            ['label' => 'status', 'sortBy' => null],
            ['label' => 'versions', 'sortBy' => null],
        ];
    }

    private function getRows(): Builder
    {
        return Event::query()
            ->join('event_management', 'event_management.event_id', '=', 'events.id')
            ->where('event_management.user_id', auth()->id())
            ->whereNull('event_management.deleted_at')
            ->select('events.*')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'));

    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name' => 'events.name',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }
}
