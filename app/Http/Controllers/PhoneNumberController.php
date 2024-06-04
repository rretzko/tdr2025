<?php

namespace App\Http\Controllers;

use App\Models\PhoneNumber;
use Illuminate\Http\Request;

class PhoneNumberController extends Controller
{
    public function index()
    {
        return PhoneNumber::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'phone_number' => ['required'],
            'phone_type' => ['required'],
        ]);

        return PhoneNumber::create($data);
    }

    public function show(PhoneNumber $phoneNumber)
    {
        return $phoneNumber;
    }

    public function update(Request $request, PhoneNumber $phoneNumber)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'phone_number' => ['required'],
            'phone_type' => ['required'],
        ]);

        $phoneNumber->update($data);

        return $phoneNumber;
    }

    public function destroy(PhoneNumber $phoneNumber)
    {
        $phoneNumber->delete();

        return response()->json();
    }
}
