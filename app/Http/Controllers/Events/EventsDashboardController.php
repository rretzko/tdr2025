<?php

namespace App\Http\Controllers\Events;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventsDashboardController extends Controller
{
    public function __invoke()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }
}
