<?php

namespace App\Http\Controllers\Ensembles\Assets;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Ensembles\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function create()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function edit(Asset $asset)
    {
        $data = new ViewDataFactory(__METHOD__, $ensemble->id);

        $dto = $data->getDto();

        $id = $asset->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
