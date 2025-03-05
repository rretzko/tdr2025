<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\CoregistrationManagerForm;
use App\Models\County;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionCountyAssignment;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CoregistrationManagersComponent extends BasePage
{
    public CoregistrationManagerForm $form;
    public array $counties = [];
    public bool $showForm = false;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->counties = $this->getCounties([]);
        $this->versionId = $this->dto['id'];
    }

    public function render()
    {
        return view('livewire..events.versions.coregistration-managers-component',
            [
                'availableCounties' => $this->getAvailableCounties(),
                'rows' => $this->getRows(),
                'participants' => $this->getParticipants(),
                'mailingAddress' => $this->getMailingAddress(),
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

    public function getAvailableCounties(): array
    {
        $query = VersionCountyAssignment::query()
            ->where('version_id', $this->versionId);

        if ($this->form && $this->form->sysId) {
            $query->whereNotIn('version_participant_id', [$this->form->sysId]);
        }

        $assignedCountyIds = $query->pluck('county_id')
            ->toArray();

        return $this->getCounties($assignedCountyIds);
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

    public function updatedFormVersionParticipantId(): void
    {
        $versionParticipant = VersionParticipant::find($this->form->versionParticipantId);
        $user = $versionParticipant->user;
        $this->form->mailingAddress[0] = $user->name;
    }

    public function updatedFormMailingAddressString(): void
    {
        $versionParticipant = ($this->form->sysId)
            ? VersionParticipant::find($this->form->sysId)
            : VersionParticipant::find($this->form->versionParticipantId);

        //early exit
        if (!$versionParticipant) {
            $this->form->mailingAddress = [];
            return;
        }

        //prefix $this->form->mailingAddressString with the user's name
        $userName = User::find($versionParticipant->user_id)->name;
        $str = $userName . ',' . $this->form->mailingAddressString;

        $this->form->mailingAddress = explode(',', $str);

    }

    private function getCounties(array $assignedCountyIds): array
    {
        return County::query()
            ->whereNotIn('id', $assignedCountyIds)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    private function getMailingAddress(): array
    {
        $default = 'Mailing address sample...';
        $defaultMailingAddress = [$default];

        return ($this->form->mailingAddress)
            ? $this->form->mailingAddress
            : $defaultMailingAddress;
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
            ->join('coregistration_manager_mailing_addresses', 'version_participants.id', '=', 'coregistration_manager_mailing_addresses.version_participant_id')
            ->where('version_county_assignments.version_id', $this->versionId)
            ->where('coregistration_manager_mailing_addresses.version_id', $this->versionId)
            ->select('version_participants.id as versionParticipantId', 'users.name as name',
                DB::raw('GROUP_CONCAT(counties.name ORDER BY counties.name ASC SEPARATOR ", ") as countyNames'),
                DB::raw("CONCAT(users.last_name, ', ', users.first_name, ' ' , users.middle_name) as alphaName"),
                'coregistration_manager_mailing_addresses.mailing_address as mailingAddress',
            )
            ->groupBy('version_participants.id', 'users.name', 'users.last_name', 'users.first_name', 'users.middle_name', 'coregistration_manager_mailing_addresses.mailing_address')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();
    }
}
