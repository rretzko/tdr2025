<?php

namespace App\Http\Controllers\Schools;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schools\GradesITeachRequest;
use App\Models\Schools\GradesITeach;

class GradesITeachController extends Controller
{
    public function index()
    {
        return GradesITeach::all();
    }

    public function store(GradesITeachRequest $request)
    {
        return GradesITeach::create($request->validated());
    }

    public function show(GradesITeach $gradesITeach)
    {
        return $gradesITeach;
    }

    public function update(GradesITeachRequest $request, GradesITeach $gradesITeach)
    {
        $gradesITeach->update($request->validated());

        return $gradesITeach;
    }

    public function destroy(GradesITeach $gradesITeach)
    {
        $gradesITeach->delete();

        return response()->json();
    }
}
