<?php

namespace App\Http\Controllers\Libraries\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Libraries\LibLibrarian;
use App\Models\Libraries\Library;
use App\Models\PageInstruction;
use App\Models\Schools\School;
use Illuminate\Http\Request;

class LibrarianController extends Controller
{
    public function __invoke(Request $request)
    {
        $dto = [];
        $librarian = LibLibrarian::where('user_id', auth()->id())->first();

        //early exit
        if ($librarian === null) {
            return abort(404);
        }

        $dto['librarianId'] = $librarian->id;
        $dto['libraryId'] = $librarian->library_id;
        $library = Library::find($dto['libraryId']);
        $dto['teacherId'] = $library->teacher_id;
        $school = School::find($library->school_id);
        $dto['schoolId'] = $school->id;
        $dto['schoolName'] = $school->name;
        $dto['livewireComponent'] = 'libraries.librarian-component';
        $dto['schoolCount'] = 1;
        $dto['header'] = 'librarian';
        $dto['pageInstructions'] = PageInstruction::where('header', 'librarian')->first()->instructions;
//auth()->loginUsingId(45);
//return redirect()->route('home');
        return view('pages.livewirePage', compact('dto'));

    }
}
