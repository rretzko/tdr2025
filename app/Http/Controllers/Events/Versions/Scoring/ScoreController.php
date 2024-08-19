<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index()
    {
        return Score::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'candidate_id' => ['required', 'exists:candidates'],
            'student_id' => ['required', 'exists:students'],
            'school_id' => ['required', 'exists:schools'],
            'score_category_id' => ['required', 'exists:score_categories'],
            'score_category_order_by' => ['required', 'integer'],
            'score_factor_id' => ['required', 'exists:score_factors'],
            'score_factor_order_by' => ['required', 'integer'],
            'judge_id' => ['required', 'integer'],
            'judge_order_by' => ['required', 'integer'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
            'voice_part_order_by' => ['required', 'integer'],
            'score' => ['required', 'integer'],
        ]);

        return Score::create($data);
    }

    public function show(Score $score)
    {
        return $score;
    }

    public function update(Request $request, Score $score)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'candidate_id' => ['required', 'exists:candidates'],
            'student_id' => ['required', 'exists:students'],
            'school_id' => ['required', 'exists:schools'],
            'score_category_id' => ['required', 'exists:score_categories'],
            'score_category_order_by' => ['required', 'integer'],
            'score_factor_id' => ['required', 'exists:score_factors'],
            'score_factor_order_by' => ['required', 'integer'],
            'judge_id' => ['required', 'integer'],
            'judge_order_by' => ['required', 'integer'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
            'voice_part_order_by' => ['required', 'integer'],
            'score' => ['required', 'integer'],
        ]);

        $score->update($data);

        return $score;
    }

    public function destroy(Score $score)
    {
        $score->delete();

        return response()->json();
    }
}
