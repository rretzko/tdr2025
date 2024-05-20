<?php

namespace App\Http\Controllers;

use App\Data\ViewDataFactory;
use App\Models\Schools\SchoolTeacher;
use App\Services\HomeDashboardTestForSchoolsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //early exit
        if (!auth()->user()->isTeacher()) {

            abort(403, 'You must be a teacher to use TheDirectorsRoom.com');
        }

        //display the school-create page if no schools exist
        if (!auth()->user()->teacher->isVerified()) {

            return redirect()->route('school.create');
        }

        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        //if user is a teacher with school(s) return all cards,
        //else display school creation form
        //$service = new HomeDashboardTestForSchoolsService($data->getDto());

        //$dto = $service->getDto();

        return view($dto['pageName'], compact('dto'));
    }
}
