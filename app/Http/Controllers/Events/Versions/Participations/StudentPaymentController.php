<?php

namespace App\Http\Controllers\Events\Versions\Participations;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\StudentPayment;
use Illuminate\Http\Request;

class StudentPaymentController extends Controller
{
    public function index()
    {
        return StudentPayment::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'candidate_id' => ['required', 'exists:candidates'],
            'student_id' => ['required', 'exists:students'],
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'amount' => ['required', 'integer'],
            'transaction_id' => ['required'],
            'comments' => ['required'],
            'payment_type' => ['required'],
        ]);

        return StudentPayment::create($data);
    }

    public function show(StudentPayment $studentPayment)
    {
        return $studentPayment;
    }

    public function update(Request $request, StudentPayment $studentPayment)
    {
        $data = $request->validate([
            'candidate_id' => ['required', 'exists:candidates'],
            'student_id' => ['required', 'exists:students'],
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'amount' => ['required', 'integer'],
            'transaction_id' => ['required'],
            'comments' => ['required'],
            'payment_type' => ['required'],
        ]);

        $studentPayment->update($data);

        return $studentPayment;
    }

    public function destroy(StudentPayment $studentPayment)
    {
        $studentPayment->delete();

        return response()->json();
    }
}
