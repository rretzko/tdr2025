<?php

namespace App\Services;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
use App\Models\UserConfig;
use Illuminate\Support\Collection;

class CanTransferStudentService
{
    private bool $can = false;

    public function __construct(private readonly int $versionId)
    {
        $this->init();
    }

    private function init(): void
    {
        $versionRoles = $this->getVersionRoles();

        $this->can = (
            auth()->user()->isFounder() ||
            $versionRoles->contains('event manager') ||
            $versionRoles->contains('online registration manager')
        );

    }

    /**
     * @return Collection
     * @todo Replace hard-coded $engageds array with db table or Enum structure
     */
    private function getVersionRoles(): Collection
    {
        $engageds = ['invited', 'obligated', 'participating'];

        $versionParticipantId = VersionParticipant::query()
            ->where('user_id', auth()->id())
            ->where('version_id', $this->versionId)
            ->whereIn('status', $engageds)
            ->value('id');

        return ($versionParticipantId)
            ? VersionRole::query()
                ->where('version_participant_id', $versionParticipantId)
                ->where('version_id', $this->versionId)
                ->distinct('role')
                ->pluck('role')
            : collect();
    }

    public function canTransferStudent(): bool
    {
        return $this->can;
    }
}
