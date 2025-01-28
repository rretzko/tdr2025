<?php

namespace App\Http\Controllers\Ensembles\Inventories;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryMassAddController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }
}
