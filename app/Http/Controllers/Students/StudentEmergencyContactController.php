<?php

namespace App\Http\Controllers\Students;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Students\Student;
use Illuminate\Http\Request;

class StudentEmergencyContactController extends Controller
{
    public function edit(Student $student)
    {
        $data = new ViewDataFactory(__METHOD__, $student->id);

        $dto = $data->getDto();

        $id = $student->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }
}
