<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\VersionConfigMembership;
use Illuminate\Http\Request;

class VersionConfigMembershipController extends Controller
{
    public function index()
    {
        return VersionConfigMembership::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'membership_card' => ['boolean'],
            'valid_thru' => ['required', 'date'],
        ]);

        return VersionConfigMembership::create($data);
    }

    public function show(VersionConfigMembership $versionConfigMembership)
    {
        return $versionConfigMembership;
    }

    public function update(Request $request, VersionConfigMembership $versionConfigMembership)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'membership_card' => ['boolean'],
            'valid_thru' => ['required', 'date'],
        ]);

        $versionConfigMembership->update($data);

        return $versionConfigMembership;
    }

    public function destroy(VersionConfigMembership $versionConfigMembership)
    {
        $versionConfigMembership->delete();

        return response()->json();
    }
}
