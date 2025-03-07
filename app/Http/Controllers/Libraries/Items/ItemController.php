<?php

namespace App\Http\Controllers\Libraries\Items;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\Library;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Library $library, LibItem $libItem = new LibItem())
    {
        //access policy
        if (!policy($library)->view($request->user(), $library)) {
            abort(403);
        }

        $id = $library->id;

        $data = new ViewDataFactory(__METHOD__, $id);

        $dto = $data->getDto();

        $dto['libItem'] = $libItem;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
