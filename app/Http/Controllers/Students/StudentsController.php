<?php

namespace App\Http\Controllers\Students;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;

use App\Services\MissingGradesService;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function __invoke(Request $request)
    {
        //abort if user should not have access to the module
        //i.e. grades or gradesITeach is missing from the UserConfig::getValue('schoolId') school profile
        /** @deprecated and resplaced with disabled add/edit student buttons and an advisory notice. */
//        if (MissingGradesService::missingGrades()) {
//            abort(404);
//        }

        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }
}
