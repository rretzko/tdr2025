<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    public function index()
    {
        return EmergencyContact::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students'],
            'emergency_contact_type_id' => ['required', 'exists:emergency_contact_types'],
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'phoneHome' => ['required'],
            'phoneMobile' => ['required'],
            'phoneWork' => ['required'],
            'bestPhone' => ['required'],
        ]);

        return EmergencyContact::create($data);
    }

    public function show(EmergencyContact $emergencyContact)
    {
        return $emergencyContact;
    }

    public function update(Request $request, EmergencyContact $emergencyContact)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students'],
            'emergency_contact_type_id' => ['required', 'exists:emergency_contact_types'],
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'phoneHome' => ['required'],
            'phoneMobile' => ['required'],
            'phoneWork' => ['required'],
            'bestPhone' => ['required'],
        ]);

        $emergencyContact->update($data);

        return $emergencyContact;
    }

    public function destroy(EmergencyContact $emergencyContact)
    {
        $emergencyContact->delete();

        return response()->json();
    }
}
