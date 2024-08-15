<?php

namespace App\Http\Controllers\Events\Versions\Participations;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\AuditionResult;
use Illuminate\Http\Request;

class AuditionResultController extends Controller
{
    public function index()
    {
        return AuditionResult::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'candidate_id' => ['required', 'exists:candidates'],
            'version_id' => ['required', 'exists:versions'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
            'school_id' => ['required', 'exists:schools'],
            'voice_part_order_by' => ['required', 'integer'],
            'score_count' => ['required', 'integer'],
            'total' => ['required', 'integer'],
            'accepted' => ['boolean'],
            'acceptance_abbr' => ['required'],
        ]);

        return AuditionResult::create($data);
    }

    public function show(AuditionResult $auditionResult)
    {
        return $auditionResult;
    }

    public function update(Request $request, AuditionResult $auditionResult)
    {
        $data = $request->validate([
            'candidate_id' => ['required', 'exists:candidates'],
            'version_id' => ['required', 'exists:versions'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
            'school_id' => ['required', 'exists:schools'],
            'voice_part_order_by' => ['required', 'integer'],
            'score_count' => ['required', 'integer'],
            'total' => ['required', 'integer'],
            'accepted' => ['boolean'],
            'acceptance_abbr' => ['required'],
        ]);

        $auditionResult->update($data);

        return $auditionResult;
    }

    public function destroy(AuditionResult $auditionResult)
    {
        $auditionResult->delete();

        return response()->json();
    }
}
