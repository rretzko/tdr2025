<?php

namespace App\Livewire\Ensembles\Members;

class MembersTableComponent extends BasePageMember
{
    public function mount(): void
    {
        parent::mount();

        $this->hasFilters = true;
        $this->hasSearch = true;
    }

    public function render()
    {
        return view('livewire..ensembles.members.members-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getMembers(),
                'tabs' => self::ENSEMBLETABS,
            ]);
    }

    private function getColumnHeaders(): array
    {
        return ['name/school', 'ensemble', 'voice part', 'grade', 'year', 'status', 'office'];
    }

    private function getMembers(): array
    {
        return [];
    }
}
