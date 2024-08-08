<?php

namespace App\Http\Controllers;

use App\Models\Epayment;
use Illuminate\Http\Request;

class EpaymentController extends Controller
{
    public function index()
    {
        return Epayment::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'user_id' => ['required', 'exists:users'],
            'fee_type' => ['required'],
            'candidate_id' => ['required', 'integer'],
            'transaction_id' => ['required'],
            'amount' => ['required', 'integer'],
            'comments' => ['required'],
        ]);

        return Epayment::create($data);
    }

    public function show(Epayment $epayment)
    {
        return $epayment;
    }

    public function update(Request $request, Epayment $epayment)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'user_id' => ['required', 'exists:users'],
            'fee_type' => ['required'],
            'candidate_id' => ['required', 'integer'],
            'transaction_id' => ['required'],
            'amount' => ['required', 'integer'],
            'comments' => ['required'],
        ]);

        $epayment->update($data);

        return $epayment;
    }

    public function destroy(Epayment $epayment)
    {
        $epayment->delete();

        return response()->json();
    }
}
