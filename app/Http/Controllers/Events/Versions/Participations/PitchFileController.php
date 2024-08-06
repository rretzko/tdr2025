<?php

namespace App\Http\Controllers\Events\Versions\Participations;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class PitchFileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $id = (int) UserConfig::getValue('versionId');

        $data = new ViewDataFactory(__METHOD__, $id);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
