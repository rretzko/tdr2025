<?php

namespace App\Http\Controllers\Events;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use Illuminate\Http\Request;

class EventManageEditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Event $event)
    {
        $data = new ViewDataFactory(__METHOD__, $event->id);

        $dto = $data->getDto();

        $id = $event->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
