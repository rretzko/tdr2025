<?php

namespace App\Livewire\Ensembles;

use App\Livewire\Forms\EnsembleForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Schools\Teacher;
use App\Models\UserFilter;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportRedirects\Redirector;

class EnsemblesTableComponent extends BasePageEnsemble
{
    public EnsembleForm $form;
    public array $ensembleAssetsArray = [];
    public string $selectedTab = 'ensembles';
    public array $tabs = self::ENSEMBLETABS;

    public function mount(): void
    {
        parent::mount();

        if (count($this->schools) > 1) {

            $this->hasFilters = true;
        }
    }

    public function render()
    {
        $rows = $this->getEnsembles();

        $memberCounts = $this->getMemberCounts($rows);

        $this->filters->setFilter('schoolsSelectedIds', $this->dto['header']);

        return view('livewire..ensembles.ensembles-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $rows,
                'memberCounts' => $memberCounts,
            ]);
    }

    public function updatedSelectedTab()
    {
        $uri = ($this->selectedTab === 'ensembles')
            ? '/ensembles'
            : '/ensembles/'.$this->selectedTab;

        $this->redirect($uri);
    }

    private function buildEnsembleAssetsCsvs(array $ensembles)
    {
        foreach ($ensembles as $schoolEnsembles) {

            foreach ($schoolEnsembles as $ensemble) {

                //clear previous array rows
                $this->ensembleAssetsArray[$ensemble['id']] = [];

                foreach ($ensemble['assets'] as $asset) {

                    $this->ensembleAssetsArray[$ensemble['id']][] = $asset['name'];
                }
            }
        }
    }

    private function getColumnHeaders(): array
    {
        return [
            'name/school', 'short name', 'abbr', 'description', 'grades', 'members', 'active', 'assets',
        ];
    }

    private function getEnsembles(): array
    {
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $a = [];

        foreach (array_flip($this->schools) as $schoolId) {

            $a[] = Ensemble::query()
                ->with([
                    'assets' => function ($query) {
                        $query->select('assets.id', 'assets.name');
                    }
                ])
                ->join('schools', 'ensembles.school_id', '=', 'schools.id')
                ->where('school_id', $schoolId)
                ->tap(function ($query) {
                    $this->filters->apply($query);
                })
                ->orderBy('schools.name')
                ->orderBy('ensembles.active')
                ->orderBy('ensembles.name')
                ->select('ensembles.*', 'schools.name AS schoolName',
                    DB::raw("ensembles.teacher_id=$teacherId AS canEdit"),
                    DB::raw("ensembles.teacher_id=$teacherId AS canRemove")
                )
                ->get()
                ->toArray();
        }

//        $this->updateUserFiltersTable();

        $this->buildEnsembleAssetsCsvs($a);

        return $a;
    }

    private function getMemberCounts(array $rows): array
    {
        $a = [];

        foreach ($rows as $schoolEnsembles) {

            foreach ($schoolEnsembles as $ensemble) {

                $schoolEnsemble = Ensemble::find($ensemble['id']);
                $currentMemberCount = $schoolEnsemble->countCurrentMembers();
                $lifetimeMemberCount = $schoolEnsemble->countLifetimeMembers();

                $a[$ensemble['id']] = [
                    'countCurrent' => $currentMemberCount,
                    'countLifetime' => $lifetimeMemberCount,
                ];
            }
        }

        return $a;
    }

    public function getSchools(): array
    {
        return auth()->user()->teacher->schools
            ->pluck('name', 'id')
            ->toArray();
    }

//    private function updateUserFiltersTable(): void
//    {
//        UserFilter::create(
//            [
//                'user_id' => auth()->id(),
//                'header' => $this->dto['header'],
//                'filter' => 'schoolSelectedIds',
//                'values' => implode(',', $this->filters->schoolsSelectedIds)
//
//            ]
//        );
//    }
}
