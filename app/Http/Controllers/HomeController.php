<?php

namespace App\Http\Controllers;

use App\Data\ViewDataFactory;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Services\HomeDashboardTestForSchoolsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //early exit
        if (!auth()->user()->isTeacher()) {

            Auth::logout();

            abort(403, 'You must be a teacher to use TheDirectorsRoom.com');
        }

        //placeholder for possible action to take if teacher's email is unverified
//        if (!auth()->user()->teacher->isVerified()) {
//
//            return redirect()->route('school.create');
//        }

        //if(auth()->user() has no schools, redirect to school.create,
        //else continue to home.dashboard
        if (!Teacher::find(auth()->id())->schools->count()) {

            return redirect()->route('school.create');

        } else { //happy path

            $data = new ViewDataFactory(__METHOD__);

            $dto = $data->getDto();

            return view($dto['pageName'], compact('dto'));
        }


    }
}
