<?php

namespace App\Http\Controllers\Events\Versions;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Mail\SendInvitationConfirmationToEventManagerMail;
use App\Mail\SendInvitationConfirmationToRequesterMail;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\User;
use App\Models\UserConfig;
use App\Services\SendInvitationConfirmationsService;
use Illuminate\Http\Request;

class InviteVersionUserController extends Controller
{
    /**
     * Handle the incoming request from an event manager
     * who clicked an emailed button to invite the
     * referenced user to the referenced event version
     */
    public function __invoke(Request $request)
    {
        //early exit
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $versionParticipant = VersionParticipant::updateOrCreate(
            [
                'user_id' => $request['user'],
                'version_id' => $request['version'],
            ],
            [
                'status' => 'invited',
            ]);

        $name = User::find($request['user'])->name;
        $versionName = Version::find($request['version'])->name;

        if ($versionParticipant->status === 'invited') {

            $user = User::find($request['user']);
            $version = Version::find($request['version']);

            new SendInvitationConfirmationToEventManagerMail($user, $version);
            new SendInvitationConfirmationToRequesterMail($user, $version);

            return view('invitationConfirmation', ['name' => $name, 'versionName' => $versionName]);

        } else {

            dd('status: '.$versionParticipant->status);
        }

    }
}
