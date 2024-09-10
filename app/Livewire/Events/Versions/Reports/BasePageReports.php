<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Livewire\BasePage;
use App\Models\UserConfig;

class BasePageReports extends BasePage
{
    public int $versionId;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'count' => 'candidateCount',
            'name' => 'users.last_name',
            'school' => 'schools.name',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }

}
