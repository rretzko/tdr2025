<?php

namespace App\Livewire\Forms;

use App\Services\Libraries\CreateLibItemService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LibraryItemForm extends Form
{
    public string $itemType = 'sheet music';
    public int $sysId = 0;
    public string $title = '';

    /**
     * Translate $this->itemType into blade file name counterpart
     */
    public function itemTypeBlade(): string
    {
        $xlats = [
            'sheet music' => 'sheetMusic',
        ];

        return (array_key_exists($this->itemType, $xlats))
            ? $xlats[$this->itemType]
            : $this->itemType;
    }


}
