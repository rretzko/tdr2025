<?php

namespace App\Livewire\Libraries;

use App\Livewire\Libraries\Items\ItemTableComponent;

class LibrarianComponent extends ItemTableComponent
{
    public bool $displayForm = false;

    public function mount(): void
    {
        parent::mount();

        $this->hasSearch = true;
    }

    public function render()
    {
        $rows = parent::getLibraryItems(
            $this->library->id,
            0,
            $this->globalSearch,
            $this->sortCol,
            $this->sortAsc,
        );
        $locations = $this->getItemLocations($rows, $this->library->id);
        $performances = $this->getItemPerformances($rows);
        $tags = $this->getItemTags($rows);
        $medleySelections = $this->getMedleySelections($rows);
        return view('livewire.libraries.librarian-component',
            [
                'rows' => $rows,
                'locations' => $locations,
                'performances' => $performances,
                'tags' => $tags,
                'medleySelections' => $medleySelections,
            ]

        );
    }
}
