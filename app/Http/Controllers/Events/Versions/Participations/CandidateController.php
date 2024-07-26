<?php

namespace App\Http\Controllers\Events\Versions\Participations;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function __invoke()
    {
        $id = (int) UserConfig::getValue('versionId');

        $data = new ViewDataFactory(__METHOD__, $id);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
