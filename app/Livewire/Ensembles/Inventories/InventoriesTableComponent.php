<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\Ensembles\Inventories\BasePageInventory;

class InventoriesTableComponent extends BasePageInventory
{
    public string $selectedTab = 'inventory';
    public array $tabs = self::ENSEMBLETABS;
    public string $sortColLabel = 'asset';

    public function mount(): void
    {
        parent::mount();
    }

    public function render()
    {
        return view('livewire..ensembles.inventories.inventories-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getInventories(),
            ]
        );
    }

    private function getColumnHeaders()
    {
        return [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'asset', 'sortBy' => 'asset'],
            ['label' => 'size', 'sortBy' => 'size'],
            ['label' => 'year', 'sortBy' => 'schoolYear'],
            ['label' => 'color(s)', 'sortBy' => ''],
        ];
    }

    private function getInventories(): array
    {
        return [];
    }

    public function updatedSelectedTab()
    {
        $uri = ($this->selectedTab === 'ensembles')
            ? '/ensembles'
            : '/ensembles/'.$this->selectedTab;

        $this->redirect($uri);
    }
}
