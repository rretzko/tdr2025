<?php

namespace App\Livewire\Programs;

use DB;
use Livewire\Component;

class ProgramDashboardComponent extends ProgramsBasePage
{

    public function mount(): void
    {
        parent::mount();

    }

    public function render()
    {
        return view('livewire..programs.program-dashboard-component',
        [
            'widget01' => $this->getUniques(),
        ]);
    }

    private function getUniques(): array
    {
        return [
            'uniqueSchools' => $this->getUniqueSchools(),
            'uniqueProgramsWithLibraryItems' => $this->getUniqueProgramsWithLibraryItems(),
            'uniqueSongs' => $this->getUniqueLibraryItems(),
            'songsSungInMultipleSchools' => $this->getSongsSungInMultipleSchools(),
        ];
    }

    private function getSongsSungInMultipleSchools(): array
    {
        $count = DB::table('program_selections as ps')
            ->join('programs as p', 'ps.program_id', '=', 'p.id')
            ->select('ps.lib_item_id')
            ->groupBy('ps.lib_item_id')
            ->havingRaw('COUNT(DISTINCT p.school_id) > 1')
            ->count();

        return [
            'label' => 'Songs sung in multiple schools',
            'count' =>  $count,
        ];

    }

    private function getUniqueLibraryItems(): array
    {
        return [
            'label' => 'Unique songs',
            'count' =>  DB::table('program_selections')
                ->select('lib_item_id')
                ->distinct()
                ->get()
                ->count(),
        ];
    }

    private function getUniqueProgramsWithLibraryItems(): array
    {
        return [
            'label' => 'Unique programs with songs',
            'count' =>  DB::table('program_selections')
                ->select('program_id')
                ->distinct()
                ->get()
                ->count(),
        ];
    }

    private function getUniqueSchools(): array
    {
        return [
          'label' => 'Unique Schools contributing programs',
          'count' =>  DB::table('programs')
            ->select('school_id')
            ->distinct()
            ->get()
            ->count(),
        ];
    }
}
