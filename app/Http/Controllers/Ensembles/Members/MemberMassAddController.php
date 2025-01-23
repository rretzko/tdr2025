<?php

namespace App\Http\Controllers\Ensembles\Members;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class MemberMassAddController extends Controller
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
