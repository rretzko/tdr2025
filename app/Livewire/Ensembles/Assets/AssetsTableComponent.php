<?php

namespace App\Livewire\Ensembles\Assets;

use App\Livewire\BasePage;
use App\Models\Ensembles\Asset;
use Illuminate\Support\Facades\DB;

class AssetsTableComponent extends BasePage
{
    public string $assetName = '';
    public string $selectedTab = 'assets';
    public string $sysId = 'new';
    public array $tabs = [];

    public function mount(): void
    {
        parent::mount();

        $this->tabs = self::ENSEMBLETABS;
    }

    public function render()
    {
        return view('livewire..ensembles.assets.asset-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getAssets(),
            ]);
    }

    protected function getColumnHeaders(): array
    {
        return ['name'];
    }

    protected function getAssets(): array
    {
        return Asset::query()
            ->whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->orderBy('name')
            ->select('id', 'name', 'user_id')
            ->get()
            ->toArray();
    }

    public function editAsset(Asset $asset): void
    {
        $this->sysId = $asset->id;
        $this->assetName = $asset->name;
    }

    public function remove(Asset $asset)
    {
        //delete ensemble Assignments
        DB::table('asset_ensemble')
            ->where('asset_id', $asset->id)
            ->delete();

        /** @todo DELETE ASSET ASSIGNMENTS FROM ENSEMBLE-STUDENT-ASSETS */

        $asset->delete();

        return redirect()->back();
    }

    public function save()
    {
        $this->validate([
            'assetName' => ['required', 'string', 'min:1'],
        ]);

        if ($this->sysId === 'new') {
            $asset = Asset::updateOrCreate(
                [
                    'name' => strtolower($this->assetName),
                ],
                [
                    'user_id' => auth()->id(),
                ]
            );
        } else {

            $asset = Asset::find($this->sysId);

            $asset->update([
                'name' => strtolower($this->assetName),
            ]);
        }

        if ($asset) {
            $this->sysId = $asset->id;
        }

        return redirect()->route('assets');
    }

    public function updatedSelectedTab()
    {
        $root = '/ensembles';

        $uri = ($this->selectedTab === 'ensembles')
            ? $root
            : $root.'/'.$this->selectedTab;

        $this->redirect($uri);
    }
}
