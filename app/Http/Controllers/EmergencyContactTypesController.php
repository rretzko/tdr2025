<?php

namespace App\Http\Controllers;

use App\Models\Students\EmergencyContactType;
use Illuminate\Http\Request;

class EmergencyContactTypesController extends Controller
{
    public function index()
    {
        return EmergencyContactType::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'relationship' => ['required'],
            'pronoun_id' => ['required', 'exists:pronouns'],
            'order_by' => ['required', 'integer'],
        ]);

        return EmergencyContactType::create($data);
    }

    public function show(EmergencyContactType $emergencyContactTypes)
    {
        return $emergencyContactTypes;
    }

    public function update(Request $request, EmergencyContactType $emergencyContactTypes)
    {
        $data = $request->validate([
            'relationship' => ['required'],
            'pronoun_id' => ['required', 'exists:pronouns'],
            'order_by' => ['required', 'integer'],
        ]);

        $emergencyContactTypes->update($data);

        return $emergencyContactTypes;
    }

    public function destroy(EmergencyContactType $emergencyContactTypes)
    {
        $emergencyContactTypes->delete();

        return response()->json();
    }
}
