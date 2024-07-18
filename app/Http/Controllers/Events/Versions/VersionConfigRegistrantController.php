<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\VersionConfigRegistrant;
use Illuminate\Http\Request;

class VersionConfigRegistrantController extends Controller
{
    public function index()
    {
        return VersionConfigRegistrant::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'eapplication' => ['boolean'],
            'audition_count' => ['required', 'integer'],
        ]);

        return VersionConfigRegistrant::create($data);
    }

    public function show(VersionConfigRegistrant $versionConfigRegistrant)
    {
        return $versionConfigRegistrant;
    }

    public function update(Request $request, VersionConfigRegistrant $versionConfigRegistrant)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'eapplication' => ['boolean'],
            'audition_count' => ['required', 'integer'],
        ]);

        $versionConfigRegistrant->update($data);

        return $versionConfigRegistrant;
    }

    public function destroy(VersionConfigRegistrant $versionConfigRegistrant)
    {
        $versionConfigRegistrant->delete();

        return response()->json();
    }
}
