<?php

namespace App\Livewire\Libraries\Items;

use App\Livewire\BasePage;

class ItemTableComponent extends BasePage
{
    public array $columnHeaders;
    public bool $displayForm = false;

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'title', 'sortBy' => 'title'],
//            ['label' => 'school', 'sortBy' => 'school'],
        ];
    }

    public function render()
    {
        return view('livewire..libraries.items.item-table-component',
            [
                'rows' => $this->getRows(),
            ]
        );
    }

    private function getRows(): array
    {
        return [];
    }

    /**
     * @return null
     */
    public function clickForm()
    {
        return $this->redirect("/library/{$this->dto['id']}/item/new");
    }
}
