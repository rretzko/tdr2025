<?php

namespace App\Http\Controllers\Events\Versions;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\UserConfig;

class VersionParticipantController extends Controller
{
    public function index()
    {
        $versionId = UserConfig::getValue('versionId');

        $data = new ViewDataFactory(__METHOD__, $versionId);

        $dto = $data->getDto();

        $id = $versionId;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
