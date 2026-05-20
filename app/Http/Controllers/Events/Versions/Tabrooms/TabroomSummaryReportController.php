<?php

declare(strict_types=1);

namespace App\Http\Controllers\Events\Versions\Tabrooms;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class TabroomSummaryReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $versionId = UserConfig::getValue('versionId');

        $data = new ViewDataFactory(__METHOD__, $versionId);
        $dto  = $data->getDto();
        $id   = $versionId;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
