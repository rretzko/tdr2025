<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Http\Request;

class VersionConfigAdjudicationController extends Controller
{
    public function index()
    {
        return VersionConfigAdjudication::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'upload_count' => ['required', 'integer'],
            'upload_types' => ['required'],
            'judge_per_room_count' => ['required', 'integer'],
            'room_monitor' => ['boolean'],
            'averaged_scores' => ['boolean'],
            'scores_ascending' => ['boolean'],
        ]);

        return VersionConfigAdjudication::create($data);
    }

    public function show(VersionConfigAdjudication $versionConfigAdjudication)
    {
        return $versionConfigAdjudication;
    }

    public function update(Request $request, VersionConfigAdjudication $versionConfigAdjudication)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'upload_count' => ['required', 'integer'],
            'upload_types' => ['required'],
            'judge_per_room_count' => ['required', 'integer'],
            'room_monitor' => ['boolean'],
            'averaged_scores' => ['boolean'],
            'scores_ascending' => ['boolean'],
        ]);

        $versionConfigAdjudication->update($data);

        return $versionConfigAdjudication;
    }

    public function destroy(VersionConfigAdjudication $versionConfigAdjudication)
    {
        $versionConfigAdjudication->delete();

        return response()->json();
    }
}
