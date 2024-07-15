<?php

namespace App\Policies;

use App\Models\Events\Event;
use App\Models\Events\EventManagement;
use App\Models\Events\Versions\Version;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class VersionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Version $version): bool
    {
        //
    }

    /**
     * Users who are founders or managers of the referenced $eventId can create new versions
     */
    public function create(User $user, Version $version, int $eventId): bool
    {
        return $user->isFounder() ||
            (EventManagement::query()
                ->where('event_id', $eventId)
                ->where('user_id', $user->id)
                ->where('role', 'manager')
                ->exists());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Version $version): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Version $version): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Version $version): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Version $version): bool
    {
        //
    }
}
