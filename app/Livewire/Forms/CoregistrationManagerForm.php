<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\CoregistrationManager;
use App\Models\Events\Versions\CoregistrationManagerMailingAddress;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionCountyAssignment;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CoregistrationManagerForm extends Form
{
    #[Validate('array | min:1')]
    public array $countyIds = [];
    public array $mailingAddress = [];
    #[Validate('string', 'nullable')]
    public string $mailingAddressString = '';
    #[Validate('int | min:0')]
    public int $sysId = 0;
    public int $versionParticipantId = 0;

    protected function messages(): array
    {
        return [
            'countyIds.min' => 'At least one county must be selected, otherwise use the "Remove" button.',
            'countyIds.*.min' => 'At least one county must be selected, otherwise use the "Remove" button.',
        ];
    }


    public function add(int $versionId): bool
    {
        $this->validate();

        $versionRoleId = $this->setVersionRole($versionId);

        $this->setMailingAddress($versionId);

        return (bool)$this->assignCounties($versionId);

    }

    public function assignCounties(int $versionId): bool
    {
        $versionParticipantId = $this->sysId ?: $this->versionParticipantId;

        //remove any current counties
        VersionCountyAssignment::query()
            ->where('version_id', $versionId)
            ->where('version_participant_id', $versionParticipantId)
            ->delete();

        //add new counties
        foreach ($this->countyIds as $countyId) {
            VersionCountyAssignment::create(
                [
                    'version_id' => $versionId,
                    'version_participant_id' => $versionParticipantId,
                    'county_id' => $countyId,
                ]
            );
        }

        return true;
    }

    public function resetVars(): void
    {
        $this->sysId = 0;
        $this->versionParticipantId = 0;
        $this->countyIds = [];
        $this->mailingAddress = [];
        $this->mailingAddressString = '';
    }

    public function setEdit(int $versionParticipantId, $versionId): bool
    {
        $coregistrationManager = CoregistrationManager::getCoregistrationManager($versionId, $versionParticipantId);
        $versionParticipant = VersionParticipant::find($versionParticipantId);
        $userName = $versionParticipant->user->name;
        $mailingAddress = CoregistrationManagerMailingAddress::query()
            ->where('version_participant_id', $versionParticipantId)
            ->where('version_id', $versionId)
            ->first()
            ->mailing_address ?? '';

        if ($coregistrationManager) {
            $this->sysId = $coregistrationManager->id;
            $this->countyIds = explode(',', $coregistrationManager->countyIds);
            $this->mailingAddressString = $mailingAddress;
            $this->mailingAddress[0] = $userName;
            $this->mailingAddress = array_merge($this->mailingAddress, explode(',', $this->mailingAddressString));

            return true;
        }

        return false;
    }

    public function update(int $versionId): bool
    {
        $this->validate();

        $this->assignCounties($versionId);

        $this->setMailingAddress($versionId);

        return (bool)$this->assignCounties($versionId);

    }

    private function setMailingAddress(int $versionId)
    {
        $versionParticipantId = ($this->sysId) ?: $this->versionParticipantId;

//        if(! $this->mailingAddressString){
//            if(CoregistrationManagerMailingAddress::query()
//                ->where('version_id', $versionId)
//                ->where('version_participant_id', $versionParticipantId)
//                ->exists())
//            {
        CoregistrationManagerMailingAddress::updateOrCreate(
            [
                'version_id' => $versionId,
                'version_participant_id' => $versionParticipantId,
            ],
            [
                'mailing_address' => $this->mailingAddressString,
            ]
        );

//            }
//        }
    }

    private function setVersionRole(int $versionId): int
    {
        return VersionRole::updateOrCreate(
            [
                'version_id' => $versionId,
                'version_participant_id' => $this->versionParticipantId,
                'role' => 'coregistration manager',
            ]
        )
            ->id;

    }
}
