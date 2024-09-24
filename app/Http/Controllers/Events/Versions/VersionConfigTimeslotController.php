<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\VersionConfigTimeslot;
use Illuminate\Http\Request;

class VersionConfigTimeslotController extends Controller
{
    public function index()
    {
        return VersionConfigTimeslot::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date'],
            'duration' => ['required', 'integer'],
        ]);

        return VersionConfigTimeslot::create($data);
    }

    public function show(VersionConfigTimeslot $versionConfigTimeslot)
    {
        return $versionConfigTimeslot;
    }

    public function update(Request $request, VersionConfigTimeslot $versionConfigTimeslot)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date'],
            'duration' => ['required', 'integer'],
        ]);

        $versionConfigTimeslot->update($data);

        return $versionConfigTimeslot;
    }

    public function destroy(VersionConfigTimeslot $versionConfigTimeslot)
    {
        $versionConfigTimeslot->delete();

        return response()->json();
    }
}
