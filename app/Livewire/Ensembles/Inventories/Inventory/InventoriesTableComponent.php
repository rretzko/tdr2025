<?php

namespace App\Livewire\Ensembles\Inventories\Inventory;

use App\Livewire\Ensembles\Inventories\BasePageInventory;
use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Models\Libraries\Items\Item;
use App\Models\UserConfig;
use Illuminate\Support\Facades\DB;

class InventoriesTableComponent extends BasePageInventory
{
    public array $ensembles = [];
    public string $selectedTab = 'inventory';
    public array $tabs = self::ENSEMBLETABS;
    public string $sortColLabel = 'asset';

    public function mount(): void
    {
        parent::mount();

        $this->ensembles = $this->getEnsembles();
    }

    public function render()
    {
        return view('livewire..ensembles.inventories.inventory.inventories-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getInventories(),
            ]
        );
    }

    public function remove(int $inventoryId): void
    {
        $inventory = Inventory::find($inventoryId);

        $nonDeletableStatuses = ['assigned'];

        //confirm that item is deletable
        if (in_array($inventory->status, $nonDeletableStatuses)) {
            return;
        }

        $inventory->delete();
    }

    private function getColumnHeaders()
    {
        return [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'ensemble', 'sortBy' => 'ensemble'],
            ['label' => 'asset', 'sortBy' => 'asset'],
            ['label' => 'id', 'sortBy' => ''],
            ['label' => 'size', 'sortBy' => 'size'],
            ['label' => 'color(s)', 'sortBy' => ''],
            ['label' => 'comments', 'sortBy' => ''],
            ['label' => 'status', 'sortBy' => 'status'],
        ];
    }

    private function getEnsembles(): array
    {
        $schoolId = UserConfig::getValue('schoolId');

        return Ensemble::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    private function getInventories(): array
    {
        return DB::table('inventories')
            ->join('assets', 'assets.id', '=', 'inventories.asset_id')
            ->join('ensembles', 'inventories.ensemble_id', '=', 'ensembles.id')
            ->select('inventories.id', 'inventories.ensemble_id', 'inventories.item_id',
                'inventories.size', 'inventories.color',
                'inventories.comments', 'inventories.status',
                'ensembles.name AS ensembleName', 'ensembles.short_name', 'ensembles.abbr',
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
