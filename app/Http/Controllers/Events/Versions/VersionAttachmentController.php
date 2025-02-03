<?php

namespace App\Http\Controllers\Events\Versions;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class VersionAttachmentController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        $id = UserConfig::getValue('versionId');

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
