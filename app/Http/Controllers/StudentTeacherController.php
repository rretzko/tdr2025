<?php

namespace App\Http\Controllers;

use App\Models\StudentTeacher;
use Illuminate\Http\Request;

class StudentTeacherController extends Controller
{
    public function index()
    {
        return StudentTeacher::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students'],
            'teacher_id' => ['required', 'exists:teachers'],
        ]);

        return StudentTeacher::create($data);
    }

    public function show(StudentTeacher $studentTeacher)
    {
        return $studentTeacher;
    }

    public function update(Request $request, StudentTeacher $studentTeacher)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students'],
            'teacher_id' => ['required', 'exists:teachers'],
        ]);

        $studentTeacher->update($data);

        return $studentTeacher;
    }

    public function destroy(StudentTeacher $studentTeacher)
    {
        $studentTeacher->delete();

        return response()->json();
    }
}
