<?php

namespace App\Http\Controllers;

use App\Models\SchoolStudent;
use Illuminate\Http\Request;

class SchoolStudentController extends Controller
{
    public function index()
    {
        return SchoolStudent::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => ['required', 'exists:schools'],
            'student_id' => ['required', 'exists:students'],
            'active' => ['boolean'],
        ]);

        return SchoolStudent::create($data);
    }

    public function show(SchoolStudent $schoolStudent)
    {
        return $schoolStudent;
    }

    public function update(Request $request, SchoolStudent $schoolStudent)
    {
        $data = $request->validate([
            'school_id' => ['required', 'exists:schools'],
            'student_id' => ['required', 'exists:students'],
            'active' => ['boolean'],
        ]);

        $schoolStudent->update($data);

        return $schoolStudent;
    }

    public function destroy(SchoolStudent $schoolStudent)
    {
        $schoolStudent->delete();

        return response()->json();
    }
}
