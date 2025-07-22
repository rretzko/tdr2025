<?php

namespace App\Livewire\Libraries\Items;

use App\Livewire\BasePage;
use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Library;
use App\Models\User;

class BaseLibraryItemPage extends BasePage
{
    public const ARTISTTYPES = [
        'composer',
        'arranger',
        'wam',
        'words',
        'music',
        'choreographer',
    ];

    public bool $isLibrary = false;

    public Library $library;
    public LibraryItemForm $form;

    public function mount(): void
    {
        parent::mount();

        if (auth()->user()->isLibrarian()) {
            $this->library = Library::find($this->dto['libraryId']);
        }

        if (auth()->user()->isTeacher() && $this->dto['id']) {
            $this->library = Library::find($this->dto['id']);
        }
    }
}
