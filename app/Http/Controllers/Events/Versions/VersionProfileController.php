<?php

namespace App\Http\Controllers\Events\Versions;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class VersionProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function create(Request $request, Event $event)
    {
        UserConfig::setProperty('eventId', $event->id);

        $data = new ViewDataFactory(__METHOD__);//, $event->id);

        $dto = $data->getDto();

        $id = $event->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }

    public function edit(Request $request)
    {
        $versionId = UserConfig::getValue('versionId');

        $data = new ViewDataFactory(__METHOD__, $versionId);

        $dto = $data->getDto();

        $id = $versionId;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
