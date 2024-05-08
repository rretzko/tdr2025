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

    }

    public function view(User $user, ViewPage $viewPage): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, ViewPage $viewPage): bool
    {
    }

    public function delete(User $user, ViewPage $viewPage): bool
    {
    }

    public function restore(User $user, ViewPage $viewPage): bool
    {
    }

    public function forceDelete(User $user, ViewPage $viewPage): bool
    {
    }
}
