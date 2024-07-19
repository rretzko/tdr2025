<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\VersionConfigDate;
use Illuminate\Http\Request;

class VersionConfigDateController extends Controller
{
    public function index()
    {
        return VersionConfigDate::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'date_type' => ['required'],
            'version_date' => ['required', 'date'],
        ]);

        return VersionConfigDate::create($data);
    }

    public function show(VersionConfigDate $versionConfigDate)
    {
        return $versionConfigDate;
    }

    public function update(Request $request, VersionConfigDate $versionConfigDate)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'date_type' => ['required'],
            'version_date' => ['required', 'date'],
        ]);

        $versionConfigDate->update($data);

        return $versionConfigDate;
    }

    public function destroy(VersionConfigDate $versionConfigDate)
    {
        $versionConfigDate->delete();

        return response()->json();
    }
}
