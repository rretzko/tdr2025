<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\BasePage;
use App\Livewire\Forms\InventoryForm;
use App\Models\Ensembles\Asset;


class BasePageInventory extends BasePage
{
    public const INVENTORYSTATUSES =
        [
            'assigned' => 'assigned',
            'available' => 'available',
            'lost' => 'lost',
            'removed' => 'removed',
            'unreturned' => 'unreturned',
        ];
    public array $assets;
    public InventoryForm $form;

    public function mount(): void
    {
        parent::mount();

        $this->form->userId = auth()->id();

        $this->form->creator = auth()->user()->name;

        $this->assets = Asset::query()
            ->whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
