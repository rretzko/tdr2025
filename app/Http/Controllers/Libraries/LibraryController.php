<?php

namespace App\Http\Controllers\Libraries;

use App\Http\Controllers\Controller;
use App\Models\Libraries\Library;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        return Library::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => ['required', 'exists:schools'],
            'user_id' => ['required', 'exists:users'],
            'name' => ['required'],
        ]);

        return Library::create($data);
    }

    public function show(Library $library)
    {
        return $library;
    }

    public function update(Request $request, Library $library)
    {
        $data = $request->validate([
            'school_id' => ['required', 'exists:schools'],
            'user_id' => ['required', 'exists:users'],
            'name' => ['required'],
        ]);

        $library->update($data);

        return $library;
    }

    public function destroy(Library $library)
    {
        $library->delete();

        return response()->json();
    }
}
