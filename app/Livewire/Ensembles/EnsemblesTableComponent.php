<?php

namespace App\Livewire\Ensembles;

use App\Livewire\Forms\EnsembleForm;
use App\Models\Ensembles\Ensemble;
use App\Models\UserFilter;
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
        return view('livewire..ensembles.ensembles-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getEnsembles(),
            ]);
    }

    public function updatedSelectedTab()
    {
        $uri = ($this->selectedTab === 'ensemble')
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
            'name/school', 'short name', 'abbr', 'description', 'active', 'assets',
        ];
    }

    private function getEnsembles(): array
    {
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
                ->select('ensembles.*', 'schools.name AS schoolName')
                ->get()
                ->toArray();
        }

        $this->updateUserFiltersTable();

        $this->buildEnsembleAssetsCsvs($a);

        return $a;

    }

    private function getSchools(): array
    {
        return auth()->user()->teacher->schools
            ->pluck('name', 'id')
            ->toArray();
    }

    private function updateUserFiltersTable(): void
    {
        UserFilter::create(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header'],
                'schools' => json_encode($this->filters->schoolsSelectedIds)
            ]
        );
    }
}
