<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use Illuminate\Http\Request;

class ScoreCategoryController extends Controller
{
    public function index()
    {
        return ScoreCategory::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'descr' => ['required'],
            'order_by' => ['required', 'integer'],
        ]);

        return ScoreCategory::create($data);
    }

    public function show(ScoreCategory $scoreCategory)
    {
        return $scoreCategory;
    }

    public function update(Request $request, ScoreCategory $scoreCategory)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'descr' => ['required'],
            'order_by' => ['required', 'integer'],
        ]);

        $scoreCategory->update($data);

        return $scoreCategory;
    }

    public function destroy(ScoreCategory $scoreCategory)
    {
        $scoreCategory->delete();

        return response()->json();
    }
}
