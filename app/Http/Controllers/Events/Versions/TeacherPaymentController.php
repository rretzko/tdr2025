<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\TeacherPayment;
use Illuminate\Http\Request;

class TeacherPaymentController extends Controller
{
    public function index()
    {
        return TeacherPayment::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'user_id' => ['required', 'exists:users'],
            'fee_type' => ['required'],
            'transaction_id' => ['required'],
            'amount' => ['required', 'integer'],
            'comments' => ['required'],
        ]);

        return TeacherPayment::create($data);
    }

    public function show(TeacherPayment $teacherPayment)
    {
        return $teacherPayment;
    }

    public function update(Request $request, TeacherPayment $teacherPayment)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'user_id' => ['required', 'exists:users'],
            'fee_type' => ['required'],
            'transaction_id' => ['required'],
            'amount' => ['required', 'integer'],
            'comments' => ['required'],
        ]);

        $teacherPayment->update($data);

        return $teacherPayment;
    }

    public function destroy(TeacherPayment $teacherPayment)
    {
        $teacherPayment->delete();

        return response()->json();
    }
}
