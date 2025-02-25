<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\coregistrationManagerForm;
use App\Models\County;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionCountyAssignment;
use Illuminate\Support\Facades\DB;

class CoregistrationManagersComponent extends BasePage
{
    public coregistrationManagerForm $form;
    public array $counties = [];
    public array $participants = [];
    public bool $showForm = false;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->counties = $this->getCounties([]);
        $this->versionId = $this->dto['id'];
        $this->participants = $this->getParticipants();
    }

    public function render()
    {
        return view('livewire..events.versions.coregistration-managers-component',
            [
                'availableCounties' => $this->getAvailableCounties(),
                'rows' => $this->getRows(),
            ]);
    }

    public function addCoregistrationManager(): void
    {
        $this->showForm = true;
    }

    public function edit(int $userId): void
    {
        dd(__METHOD__);
    }

    public function remove(int $userId): void
    {
        dd(__METHOD__);
    }

    public function saveCoregistrationManager()
    {
        if ($this->form->add($this->versionId)) {
            $this->successMessage = 'New coregistration manager added';
            $this->reset('showForm');
        }

        return $this->redirect('/version/coregistrationManagers');
    }

    public function getAvailableCounties(): array
    {
        $assignedCountyIds = VersionCountyAssignment::query()
            ->where('version_id', $this->versionId)
            ->pluck('county_id')
            ->toArray();

        return $this->getCounties($assignedCountyIds);
    }

    private function getCounties(array $assignedCountyIds): array
    {
        return County::query()
            ->whereNotIn('id', $assignedCountyIds)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Return participants who do NOT have counties assigned
     * @return array
     */
    private function getParticipants(): array
    {
        $coregistrationManagers = VersionCountyAssignment::query()
            ->where('version_id', $this->versionId)
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        return Version::find($this->versionId)
            ->participantsArray($coregistrationManagers);
    }

    private function getRows(): array
    {
        return DB::table('version_county_assignments')
            ->join('users', 'version_county_assignments.user_id', '=', 'users.id')
            ->join('counties', 'version_county_assignments.county_id', '=', 'counties.id')
            ->select('users.id as userId', 'users.name as name', DB::raw('GROUP_CONCAT(counties.name ORDER BY counties.name ASC SEPARATOR ", ") as countyNames'))
            ->groupBy('users.id', 'users.name')
            ->get()
            ->toArray();
    }
}
