<?php

namespace App\Http\Controllers\Events\Versions\Participations;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class ParticipationActiveController extends Controller
{
    /**
     * Handle the incoming request.
     * @param  Version  $version
     */
    public function __invoke(Version $version)
    {
        UserConfig::setProperty('versionId', $version->id);

        $data = new ViewDataFactory(__METHOD__, $version->id);

        $dto = $data->getDto();

        $id = $version->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
