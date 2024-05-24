<?php

namespace App\Http\Controllers\Schools;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schools\SchoolGradeRequest;
use App\Models\Schools\SchoolGrade;

class SchoolGradeController extends Controller
{
    public function index()
    {
        return SchoolGrade::all();
    }

    public function store(SchoolGradeRequest $request)
    {
        return SchoolGrade::create($request->validated());
    }

    public function show(SchoolGrade $schoolGrade)
    {
        return $schoolGrade;
    }

    public function update(SchoolGradeRequest $request, SchoolGrade $schoolGrade)
    {
        $schoolGrade->update($request->validated());

        return $schoolGrade;
    }

    public function destroy(SchoolGrade $schoolGrade)
    {
        $schoolGrade->delete();

        return response()->json();
    }
}
