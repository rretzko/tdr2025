<?php

namespace App\Services;

use App\Models\Libraries\Library;

class RemoveLibraryService
{
    private Library $library;

    public function __construct(private readonly int $libraryId)
    {
        $this->library = Library::find($this->libraryId);
        $this->init();
    }

    private function init(): void
    {
        //remove item links
        $this->deleteLibraryItemLinks();

        //remove library
        $this->deleteLibrary();
    }

    private function deleteLibraryItemLinks()
    {
        //$this->library->items->delete;
    }

    private function deleteLibrary(): void
    {
        $this->library->delete();
    }
}
