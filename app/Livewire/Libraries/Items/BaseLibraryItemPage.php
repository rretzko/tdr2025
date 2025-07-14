<?php

namespace App\Livewire\Libraries\Items;

use App\Livewire\BasePage;
use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Library;

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

    public Library $library;
    public LibraryItemForm $form;

    public function mount(): void
    {
        parent::mount();

        if ($this->dto['id']) {
            $this->library = Library::find($this->dto['id']);
        }
    }
}
