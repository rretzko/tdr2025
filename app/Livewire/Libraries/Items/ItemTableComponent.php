<?php

namespace App\Livewire\Libraries\Items;

use App\Livewire\BasePage;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;

class ItemTableComponent extends BasePage
{
    public array $columnHeaders;
    public Library $library;
    public bool $displayForm = false;

    public function mount(): void
    {
        parent::mount();

        $this->library = Library::find($this->dto['id']);

        $this->columnHeaders = $this->getColumnHeaders();
    }

    public function render()
    {
        return view('livewire..libraries.items.item-table-component',
            [
                'rows' => $this->getRows(),
            ]
        );
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'type', 'sortBy' => 'type'],
            ['label' => 'title', 'sortBy' => 'title'],
        ];
    }

    private function getRows(): array
    {
        return LibStack::query()
            ->join('lib_items', 'lib_stacks.lib_item_id', '=', 'lib_items.id')
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->where('lib_stacks.library_id', $this->library->id)
            ->select('lib_stacks.id', 'lib_titles.title', 'lib_items.item_type')
            ->get()
            ->toArray();
    }

    /**
     * @return null
     */
    public function clickForm()
    {
        return $this->redirect("/library/{$this->dto['id']}/item/new");
    }
}
