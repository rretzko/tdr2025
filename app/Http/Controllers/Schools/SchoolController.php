<?php

namespace App\Http\Controllers\Schools;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schools\SchoolRequest;
use App\Models\Schools\School;

class SchoolController extends Controller
{
    public function index()
    {
        return School::all();
    }

    public function create()
    {

    }

    public function store(SchoolRequest $request)
    {
        return School::create($request->validated());
    }

    public function show(School $school)
    {
        return $school;
    }

    public function update(SchoolRequest $request, School $school)
    {
        $school->update($request->validated());

        return $school;
    }

    public function destroy(School $school)
    {
        $school->delete();

        return response()->json();
    }
}
