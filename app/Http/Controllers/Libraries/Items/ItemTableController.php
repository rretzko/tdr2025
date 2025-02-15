<?php

namespace App\Http\Controllers\Libraries\Items;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Libraries\Library;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class ItemTableController extends Controller
{
    use AuthorizesRequests;
    public function __invoke(Request $request, Library $library)
    {
        //access policy
        if (!policy($library)->view($request->user(), $library)) {
            abort(403);
        }

        $id = $library->id;

        $data = new ViewDataFactory(__METHOD__, $id);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
