<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\VersionTimeslot;
use Illuminate\Http\Request;

class VersionTimeslotController extends Controller
{
    public function index()
    {
        return VersionTimeslot::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'timeslot' => ['required', 'date'],
        ]);

        return VersionTimeslot::create($data);
    }

    public function show(VersionTimeslot $versionTimeslot)
    {
        return $versionTimeslot;
    }

    public function update(Request $request, VersionTimeslot $versionTimeslot)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'school_id' => ['required', 'exists:schools'],
            'timeslot' => ['required', 'date'],
        ]);

        $versionTimeslot->update($data);

        return $versionTimeslot;
    }

    public function destroy(VersionTimeslot $versionTimeslot)
    {
        $versionTimeslot->delete();

        return response()->json();
    }
}
