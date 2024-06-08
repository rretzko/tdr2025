<?php

namespace App\Http\Controllers\Students;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }
}