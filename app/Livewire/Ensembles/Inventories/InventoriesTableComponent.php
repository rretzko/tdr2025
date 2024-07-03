<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\Ensembles\Inventories\BasePageInventory;
use Illuminate\Support\Facades\DB;

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
            ['label' => 'color(s)', 'sortBy' => ''],
            ['label' => 'comments', 'sortBy' => ''],
            ['label' => 'status', 'sortBy' => 'status'],
        ];
    }

    private function getInventories(): array
    {
        return DB::table('inventories')
            ->join('assets', 'assets.id', '=', 'inventories.asset_id')
            ->select('inventories.id', 'inventories.item_id', 'inventories.size', 'inventories.color',
                'inventories.comments', 'inventories.status',
                'assets.name')
            ->get()
            ->toArray();
    }

    public function updatedSelectedTab()
    {
        $uri = ($this->selectedTab === 'ensembles')
            ? '/ensembles'
            : '/ensembles/'.$this->selectedTab;

        $this->redirect($uri);
    }
}
