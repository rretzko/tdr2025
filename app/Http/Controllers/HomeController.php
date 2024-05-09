<?php

namespace App\Http\Controllers;

use App\Data\ViewDataFactory;
use App\Services\HomeDashboardTestForSchoolsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = new ViewDataFactory(__METHOD__);

        //if user is a teacher with schools return all cards,
        //else remove the students and events cardsphp
        $service = new HomeDashboardTestForSchoolsService($data->dto());

        $dto = $service->getDto();

        return view($dto['pageName'], compact('dto'));
    }
}
