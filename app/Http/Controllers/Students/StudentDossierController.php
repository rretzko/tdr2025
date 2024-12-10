<?php

namespace App\Http\Controllers\Students;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDossierController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

//        $id = $versionId;

        return view($dto['pageName'], compact('dto'));
    }
}
