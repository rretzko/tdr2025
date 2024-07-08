<?php

namespace App\Http\Controllers\Schools\Teachers;

use App\Http\Controllers\Controller;
use App\Models\Schools\Teachers\TeacherSubject;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function index()
    {
        return TeacherSubject::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => ['required', 'exists:teachers'],
            'school_id' => ['required', 'exists:schools'],
            'subject' => ['required'],
        ]);

        return TeacherSubject::create($data);
    }

    public function show(TeacherSubject $teacherSubject)
    {
        return $teacherSubject;
    }

    public function update(Request $request, TeacherSubject $teacherSubject)
    {
        $data = $request->validate([
            'teacher_id' => ['required', 'exists:teachers'],
            'school_id' => ['required', 'exists:schools'],
            'subject' => ['required'],
        ]);

        $teacherSubject->update($data);

        return $teacherSubject;
    }

    public function destroy(TeacherSubject $teacherSubject)
    {
        $teacherSubject->delete();

        return response()->json();
    }
}
