<?php

namespace App\Http\Controllers\Schools;

use App\Data\ViewDataFactory;
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
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function edit(School $school)
    {
        $data = new ViewDataFactory(__METHOD__, $school);

        $dto = $data->getDto();

        $id = $school->id;

        return view($dto['pageName'], compact(['dto', 'id']));
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
