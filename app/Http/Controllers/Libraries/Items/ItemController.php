<?php

namespace App\Http\Controllers\Libraries\Items;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibLibrarian;
use App\Models\Libraries\Library;
use App\Models\Schools\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Library $library, LibItem|null $libItem = new LibItem())
    {
        //access policy
        //for use by student library,
        //define User as the owner of the library
        if (auth()->user()->isLibrarian()) {
            $librarian = LibLibrarian::where('user_id', auth()->user()->id)->first();
            $library = Library::where('id', $librarian->library_id)->first();
            $teacher = Teacher::where('id', $library->teacher_id)->first();
            $user = User::where('id', $teacher->user_id)->first();
        } else {
            $user = $request->user();
        }

//        if (!policy($library)->view($request->user(), $library)) {
        if (!policy($library)->view($user, $library)) {
            abort(403);
        }

        $id = $library->id;

        $data = new ViewDataFactory(__METHOD__, $id);

        $dto = $data->getDto();

        $dto['libItem'] = $libItem;

        if (auth()->user()->isLibrarian()) {
            $dto['libraryId'] = $library->id;
        }

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
