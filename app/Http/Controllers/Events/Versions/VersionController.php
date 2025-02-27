<?php

namespace App\Http\Controllers\Events\Versions;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index()
    {
        return Version::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'name' => ['required'],
            'short_name' => ['required'],
            'senior_class_of' => ['required', 'integer'],
            'status' => ['required'],
        ]);

        return Version::create($data);
    }

    public function show(Version $version)
    {
        UserConfig::setProperty('versionId', $version->id);

        $data = new ViewDataFactory(__METHOD__, $version->id);

        $dto = $data->getDto();

        $id = $version->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }

    public function update(Request $request, Version $version)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'name' => ['required'],
            'short_name' => ['required'],
            'senior_class_of' => ['required', 'integer'],
            'status' => ['required'],
        ]);

        $version->update($data);

        return $version;
    }

    public function destroy(Version $version)
    {
        $version->delete();

        return response()->json();
    }
}
