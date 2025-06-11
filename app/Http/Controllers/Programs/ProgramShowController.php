<?php

namespace App\Http\Controllers\Programs;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Programs\Program;
use Illuminate\Http\Request;

class ProgramShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Program $program)
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        $dto['programId'] = $program->id;

        return view($dto['pageName'], compact('dto'));
    }
}
