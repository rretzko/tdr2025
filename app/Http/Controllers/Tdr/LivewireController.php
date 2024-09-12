<?php

namespace App\Http\Controllers\Tdr;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\UserConfig;
use Illuminate\Http\Request;
use App\Traits\MakeMethodStringTrait;

class LivewireController extends Controller
{
    use MakeMethodStringTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $method = $this->makeMethodString($request);

        $versionId = UserConfig::getValue('versionId');

        $data = new ViewDataFactory($method, $versionId);

        $dto = $data->getDto();

        $id = $versionId;

        return view($dto['pageName'], compact('dto', 'id'));
    }


}
