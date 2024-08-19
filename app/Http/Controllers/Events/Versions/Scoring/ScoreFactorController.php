<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use Illuminate\Http\Request;

class ScoreFactorController extends Controller
{
    public function index()
    {
        return ScoreFactor::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'version_id' => ['nullable', 'exists:versions'],
            'score_category_id' => ['required', 'exists:score_categories'],
            'factor' => ['required'],
            'abbr' => ['required'],
            'best' => ['required', 'integer'],
            'worst' => ['required', 'integer'],
            'interval_by' => ['required', 'integer'],
            'multiplier' => ['required', 'integer'],
            'tolerance' => ['required', 'integer'],
            'order_by' => ['required', 'integer'],
        ]);

        return ScoreFactor::create($data);
    }

    public function show(ScoreFactor $scoreFactor)
    {
        return $scoreFactor;
    }

    public function update(Request $request, ScoreFactor $scoreFactor)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'version_id' => ['nullable', 'exists:versions'],
            'score_category_id' => ['required', 'exists:score_categories'],
            'factor' => ['required'],
            'abbr' => ['required'],
            'best' => ['required', 'integer'],
            'worst' => ['required', 'integer'],
            'interval_by' => ['required', 'integer'],
            'multiplier' => ['required', 'integer'],
            'tolerance' => ['required', 'integer'],
            'order_by' => ['required', 'integer'],
        ]);

        $scoreFactor->update($data);

        return $scoreFactor;
    }

    public function destroy(ScoreFactor $scoreFactor)
    {
        $scoreFactor->delete();

        return response()->json();
    }
}
