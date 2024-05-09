<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ViewPage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViewPagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isFounder();
    }

    public function view(User $user, ViewPage $viewPage): bool
    {
        return $user->isFounder();
    }

    public function create(User $user): bool
    {
        return $user->isFounder();
    }

    public function update(User $user, ViewPage $viewPage): bool
    {
        return $user->isFounder();
    }

    public function delete(User $user, ViewPage $viewPage): bool
    {
        return $user->isFounder();
    }

    public function restore(User $user, ViewPage $viewPage): bool
    {
        return $user->isFounder();
    }

    public function forceDelete(User $user, ViewPage $viewPage): bool
    {
        return $user->isFounder();
    }
}
