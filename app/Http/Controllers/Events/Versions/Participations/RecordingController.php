<?php

namespace App\Http\Controllers\Events\Versions\Participations;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Recording;
use Illuminate\Http\Request;

class RecordingController extends Controller
{
    public function index()
    {
        return Recording::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'candidate_id' => ['required', 'exists:candidates'],
            'file_type' => ['required'],
            'uploaded_by' => ['required', 'integer'],
            'approved' => ['required', 'date'],
            'url' => ['required'],
        ]);

        return Recording::create($data);
    }

    public function show(Recording $recording)
    {
        return $recording;
    }

    public function update(Request $request, Recording $recording)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'candidate_id' => ['required', 'exists:candidates'],
            'file_type' => ['required'],
            'uploaded_by' => ['required', 'integer'],
            'approved' => ['required', 'date'],
            'url' => ['required'],
        ]);

        $recording->update($data);

        return $recording;
    }

    public function destroy(Recording $recording)
    {
        $recording->delete();

        return response()->json();
    }
}
