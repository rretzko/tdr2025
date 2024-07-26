<?php

namespace App\Http\Controllers\Events;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class EventsParticipationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $versionId = UserConfig::getValue('versionId');

        $data = new ViewDataFactory(__METHOD__, $versionId);

        $dto = $data->getDto();

        $id = $versionId;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
