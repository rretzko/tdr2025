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
//    public array $participants = [];
    public bool $showForm = false;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->counties = $this->getCounties([]);
        $this->versionId = $this->dto['id'];
//        $this->participants = $this->getParticipants();
    }

    public function render()
    {
        return view('livewire..events.versions.coregistration-managers-component',
            [
                'availableCounties' => $this->getAvailableCounties(),
                'rows' => $this->getRows(),
                'participants' => $this->getParticipants(),
            ]);
    }

    public function addCoregistrationManager(): void
    {
        $this->form->resetVars();
        $this->showForm = true;
    }

    public function edit(int $versionParticipantId): void
    {
        $set = $this->form->setEdit($versionParticipantId, $this->versionId);

        if ($set) {
//            $this->participants = $this->getParticipants();
            $this->showForm = !$this->showForm;
        }
    }

    public function remove(int $versionParticipantId): void
    {
        DB::table('version_county_assignments')
            ->where('version_id', $this->versionId)
            ->where('version_participant_id', $versionParticipantId)
            ->delete();
    }

    public function saveCoregistrationManager()
    {
        if ($this->form->add($this->versionId)) {
            $this->successMessage = 'New coregistration manager added';
            $this->reset('showForm');
        }

        return $this->redirect('/version/coregistrationManagers');
    }

    public function updateCoregistrationManager()
    {
        if ($this->form->update($this->versionId)) {
            $this->successMessage = 'Coregistration manager updated';
            $this->reset('showForm');
        }

        return $this->redirect('/version/coregistrationManagers');
    }

    public function getAvailableCounties(): array
    {
        $assignedCountyIds = VersionCountyAssignment::query()
            ->where('version_id', $this->versionId)
            ->whereNotIn('version_participant_id', [$this->form->sysId])
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
        $suppress = ($this->form->sysId === 0);

        $query = VersionCountyAssignment::query()
            ->where('version_id', $this->versionId)
            ->distinct();

        //user has requested to edit a record
        if ($this->form->sysId) {
            $query->where('version_county_assignments.version_participant_id', $this->form->sysId);
        }

        $coregistrationManagers = $query->pluck('version_participant_id')
            ->toArray();

        return Version::find($this->versionId)
            ->participantsArray($coregistrationManagers, $suppress);
    }

    private function getRows(): array
    {
        return DB::table('version_county_assignments')
            ->join('version_participants', 'version_county_assignments.version_participant_id', '=', 'version_participants.id')
            ->join('users', 'version_participants.user_id', '=', 'users.id')
            ->join('counties', 'version_county_assignments.county_id', '=', 'counties.id')
            ->where('version_county_assignments.version_id', $this->versionId)
            ->select('version_participants.id as versionParticipantId', 'users.name as name',
                DB::raw('GROUP_CONCAT(counties.name ORDER BY counties.name ASC SEPARATOR ", ") as countyNames'))
            ->groupBy('version_participants.id', 'users.name')
            ->get()
            ->toArray();
    }
}
