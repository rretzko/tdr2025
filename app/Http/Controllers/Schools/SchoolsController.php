<?php

namespace App\Http\Controllers\Schools;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->dto();

        return view($dto['pageName'], compact('dto'));
    }
}
