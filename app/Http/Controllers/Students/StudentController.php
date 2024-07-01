<?php

namespace App\Http\Controllers\Students;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Students\StudentRequest;
use App\Models\Schools\School;
use App\Models\SchoolStudent;
use App\Models\Students\Student;

class StudentController extends Controller
{
    public function index()
    {
        return Student::all();
    }

    public function store(StudentRequest $request)
    {
        return Student::create($request->validated());
    }

    public function create()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function edit(SchoolStudent $schoolStudent)
    {
        $data = new ViewDataFactory(__METHOD__, $schoolStudent->id);

        $dto = $data->getDto();

        $id = $schoolStudent->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }

    public function show(Student $student)
    {
        return $student;
    }

    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return $student;
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json();
    }
}
