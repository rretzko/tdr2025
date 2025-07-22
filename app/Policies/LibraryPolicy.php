<?php

namespace App\Policies;

use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Library;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Coteacher;
use App\Models\User;
use App\Services\CoTeachersService;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class LibraryPolicy
{
//
//    /**
//     * Determine whether the user can view any models.
//     */
//    public function viewAny(User $user): bool
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can view the model.
//     */
    public function view(User $user, Library $library): bool
    {
        $coTeachers = CoTeachersService::getCoTeachersIds($user->id);

        return in_array($library->teacher_id, $coTeachers);
    }
//
//    /**
//     * Determine whether the user can create models.
//     */
    public function create(User $user, Ensemble $ensemble): bool
    {
        Log::info(__METHOD__);

        return false;
    }
//
//    /**
//     * Determine whether the user can update the model.
//     */
//    public function update(User $user, Ensemble $ensemble): bool
//    {
//        //
//    }

    /**
     * Determine whether the user can delete the model.
     */
//    public function delete(User $user, Ensemble $ensemble): bool
//    {
//        //
//    }

    /**
     * Determine whether the user can restore the model.
     */
//    public function restore(User $user, Ensemble $ensemble): bool
//    {
//        //
//    }

    /**
     * Determine whether the user can permanently delete the model.
     */
//    public function forceDelete(User $user, Ensemble $ensemble): bool
//    {
//        //
//    }
}
