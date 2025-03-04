<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\CoregistrationManager;
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
    }

    public function setEdit(int $versionParticipantId, $versionId): bool
    {
        $coregistrationManager = CoregistrationManager::getCoregistrationManager($versionId, $versionParticipantId);

        if ($coregistrationManager) {
            $this->sysId = $coregistrationManager->id;
            $this->countyIds = explode(',', $coregistrationManager->countyIds);
            return true;
        }

        return false;
    }

    public function update(int $versionId): bool
    {
        $this->validate();

        $this->assignCounties($versionId);

        return (bool)$this->assignCounties($versionId);

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
