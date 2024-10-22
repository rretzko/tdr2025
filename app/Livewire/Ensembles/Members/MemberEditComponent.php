<?php

namespace App\Livewire\Ensembles\Members;

use App\Models\Ensembles\AssetEnsemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Services\CoTeachersService;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class MemberEditComponent extends BasePageMember
{
    public string $assignedAssetId = '';
    public array $itemIds;

    public function mount(): void
    {
        parent::mount();

        $this->form->setMember($this->dto['id']);
    }

    public function render()
    {
        //ensure that any asset changes are refreshed
        $this->form->setAssignedAssets();
        //Log::info(print_r($this->form->memberAssets, true));
        return view('livewire..ensembles.members.member-edit-component',
            [
                'assets' => $this->getAssets(),
                'ensembles' => $this->filters->ensembles(),
                'offices' => self::OFFICES,
                'statuses' => self::STATUSES,
                'voiceParts' => $this->getVoiceParts(),
                'availableAssets' => $this->getAvailableAssets(),
            ]);
    }

    public function assignAssets(): void
    {
        foreach ($this->itemIds as $assetId => $inventoryId) {

            //remove current asset if exists
            $this->removeAsset($assetId, $this->form->userId);

            if ($inventoryId) {

                //assign inventory to member and change inventory status
                Inventory::find($inventoryId)
                    ->update([
                        'status' => 'assigned',
                        'assigned_to' => $this->form->userId,
                        'updated_by' => auth()->id(),
                    ]);
            }
        }

//        $this->form->setAssignedAssets();
    }

    /**
     * Remove asset from member
     * @param  int  $id
     * @return void
     */
    #[NoReturn] public function remove(int $id): void
    {
        $inventory = Inventory::find($id);

        $this->removeAsset($inventory->asset_id, $inventory->assigned_to);

//        $this->form->setAssignedAssets();

    }

    public function updatedAssignedAssetId(): void
    {
        $parts = explode('-', $this->assignedAssetId);
        $this->itemIds[$parts[0]] = $parts[1];
    }

    public function save()
    {
        $this->form->update();

        return redirect()->route('members');
    }

    private function getAvailableAssets(): array
    {
        $assetsIds = AssetEnsemble::query()
            ->where('ensemble_id', $this->form->ensembleId)
            ->pluck('asset_id as assetId')
            ->toArray();

        return Inventory::query()
            ->join('assets', 'assets.id', '=', 'inventories.asset_id')
            ->whereIn('inventories.asset_id', $assetsIds)
            ->where('inventories.status', 'available')
            ->whereIn('inventories.updated_by', CoTeachersService::getCoTeachersIds())
            ->select('inventories.*', 'assets.name AS assetName')
            ->orderBy('assetName')
            ->orderBy('inventories.item_id')
            ->get()
            ->groupBy('assetName')
            ->toArray();
    }

    private function removeAsset(int $assetId, int $assignedTo): void
    {
        if (Inventory::query()
            ->where('asset_id', $assetId)
            ->where('assigned_to', $assignedTo)
            ->exists()) {

            $inventory = Inventory::query()
                ->where('asset_id', $assetId)
                ->where('assigned_to', $assignedTo)
                ->first();

            $inventory->update(
                [
                    'status' => 'available',
                    'assigned_to' => null,
                    'updated_by' => auth()->id(),
                ]
            );
        }

    }


}
