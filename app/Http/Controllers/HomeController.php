<?php

namespace App\Http\Controllers;

use App\Data\ViewDataFactory;
use Illuminate\Http\Request;

class HomeController extends Controller
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
