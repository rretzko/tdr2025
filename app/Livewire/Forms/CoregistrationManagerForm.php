<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\VersionCountyAssignment;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CoregistrationManagerForm extends Form
{
    #[Validate(
        [
            'countyIds' => ['array', 'min:1'],
            'countyIds.*' => ['int', 'min:1']
        ]
    )]
    public array $countyIds = [];
    public int $sysId = 0;
    #[Validate('int')]
    #[Validate('int', 'min:1')]
    public int $userId = 0;

    public function add(int $versionId): bool
    {
        $this->validate();

        $versionRoleId = $this->setVersionRole($versionId);

        return (bool)$this->assignCounties($versionId);

    }

    public function assignCounties(int $versionId): bool
    {
        //remove any current counties
        VersionCountyAssignment::query()
            ->where('version_id', $versionId)
            ->where('user_id', $this->userId)
            ->delete();
        //add new counties
        foreach ($this->countyIds as $countyId) {
            VersionCountyAssignment::create(
                [
                    'version_id' => $versionId,
                    'user_id' => $this->userId,
                    'county_id' => $countyId,
                ]
            );
        }

        return true;
    }

    private function setVersionRole(int $versionId): int
    {
        $versionParticipantId = VersionParticipant::query()
            ->where('version_id', $versionId)
            ->where('user_id', $this->userId)
            ->first()
            ->id;

        return VersionRole::updateOrCreate(
            [
                'version_id' => $versionId,
                'version_participant_id' => $versionParticipantId,
                'role' => 'coregistration manager',
            ]
        )
            ->id;

    }
}
