<?php

namespace App\Http\Controllers\Events\Versions;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class CurrentVersionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Event $event)
    {
        //set eventId property
        UserConfig::setProperty('eventId', $event->id);

        $version = $event->getCurrentVersion();

        //persist versionId property
        UserConfig::setProperty('versionId', $version->id);

        if ($version->id) { //existing version found

            UserConfig::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'property' => 'versionId',
                ],
                [
                    'value' => $version->id,
                ]
            );

            return redirect()->route('version.show', ['version' => $version]);

        } else { //create a new version

            return redirect()->route('version.create', ['event' => $event]);
        }

    }
}
