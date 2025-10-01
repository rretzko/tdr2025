<?php

namespace App\Livewire\Programs;

use App\Models\Programs\ProgramStats;

class ProgramChoirTrendsComponent extends ProgramsBasePage
{
    public function mount(): void
    {
        parent::mount();

    }

    public function render()
    {
        return view('livewire..programs.program-choir-trends-component',
        [
            'widget01' => $this->getProgramStats(),
        ]);
    }

    private function getProgramStats(): array
    {
        $programStats = new ProgramStats();

        return $programStats->getStats();
    }


}
