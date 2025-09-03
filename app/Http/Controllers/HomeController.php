<?php

namespace App\Http\Controllers;

use App\Data\ViewDataFactory;
use App\Models\Libraries\LibLibrarian;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Services\HomeDashboardTestForSchoolsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //early exit test for not a teacher and not a librarian
        if (!auth()->user()->isTeacher() && !auth()->user()->isLibrarian()) {

            Auth::logout();

            abort(403, 'You must be a teacher to use TheDirectorsRoom.com');
        }

        //placeholder for possible action to take if teacher's email is unverified
//        if (!auth()->user()->teacher->isVerified()) {
//
//            return redirect()->route('school.create');
//        }

        //if(auth()->user() is a librarian, redirect to library page with assigned library
        if (LibLibrarian::where('user_id', auth()->id())->exists()) {
            return redirect()->route('librarian');
//            auth()->loginUsingId(45);
//            return redirect()->route('home');

        }

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
