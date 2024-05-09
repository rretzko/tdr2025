<?php

namespace App\Http\Controllers\Schools;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schools\SchoolTeacherRequest;
use App\Models\Schools\SchoolTeacher;

class SchoolTeacherController extends Controller
{
    public function index()
    {
        return SchoolTeacher::all();
    }

    public function store(SchoolTeacherRequest $request)
    {
        return SchoolTeacher::create($request->validated());
    }

    public function show(SchoolTeacher $schoolTeacher)
    {
        return $schoolTeacher;
    }

    public function update(SchoolTeacherRequest $request, SchoolTeacher $schoolTeacher)
    {
        $schoolTeacher->update($request->validated());

        return $schoolTeacher;
    }

    public function destroy(SchoolTeacher $schoolTeacher)
    {
        $schoolTeacher->delete();

        return response()->json();
    }
}
