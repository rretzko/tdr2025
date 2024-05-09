<?php

namespace App\Http\Controllers\Schools;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schools\TeacherRequest;
use App\Models\Schools\Teacher;

class TeacherController extends Controller
{
    public function index()
    {
        return Teacher::all();
    }

    public function store(TeacherRequest $request)
    {
        return Teacher::create($request->validated());
    }

    public function show(Teacher $teacher)
    {
        return $teacher;
    }

    public function update(TeacherRequest $request, Teacher $teacher)
    {
        $teacher->update($request->validated());

        return $teacher;
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return response()->json();
    }
}
