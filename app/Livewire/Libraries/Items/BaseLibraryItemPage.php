<?php

namespace App\Livewire\Libraries\Items;

use App\Livewire\BasePage;
use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Library;

class BaseLibraryItemPage extends BasePage
{
    public const ITEMTYPES = [
        'sheet music' => 'sheet music',
        'medley' => 'medley',
        'book' => 'book',
        'digital' => 'digital',
        'cd' => 'cd',
        'dvd' => 'dvd',
        'cassette' => 'cassette',
        'vinyl' => 'vinyl',
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
