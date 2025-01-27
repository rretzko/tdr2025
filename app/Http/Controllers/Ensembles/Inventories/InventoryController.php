<?php

namespace App\Http\Controllers\Ensembles\Inventories;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Ensembles\Inventories\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
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

    public function edit(Inventory $inventory)
    {
        $data = new ViewDataFactory(__METHOD__, $inventory->id);

        $dto = $data->getDto();

        $id = $inventory->id;

        $dto['inventoryId'] = $id;

        return view($dto['pageName'], compact('dto', 'id'));
    }

}
