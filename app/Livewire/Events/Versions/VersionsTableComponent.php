<?php

namespace App\Livewire\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Database\Eloquent\Builder;

class VersionsTableComponent extends BasePageVersion
{
    public int $eventId;

    public function mount(): void
    {
        parent::mount();

        $this->eventId = UserConfig::getValue('eventId');

        $this->sortCol = 'versions.senior_class_of';
    }

    public function render()
    {
        return view('livewire..events.versions.versions-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name/short name', 'sortBy' => 'name'], //users.last_name
            ['label' => 'senior class', 'sortBy' => 'seniorClass'],
            ['label' => 'status', 'sortBy' => null],
            ['label' => 'payPal', 'sortBy' => null],
            ['label' => 'fees', 'sortBy' => null],
            ['label' => 'pitch files', 'sortBy' => null],
        ];
    }

    private function getRows(): Builder
    {
        return Version::query()
            ->where('event_id', $this->dto['id'])
            ->orderByDesc('senior_class_of');
    }
}
