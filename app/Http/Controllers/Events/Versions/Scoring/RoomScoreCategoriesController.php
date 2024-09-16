<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use Illuminate\Http\Request;

class RoomScoreCategoriesController extends Controller
{
    public function index()
    {
        return RoomScoreCategory::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms'],
            'score_category_id' => ['required', 'exists:score_categories'],
        ]);

        return RoomScoreCategory::create($data);
    }

    public function show(RoomScoreCategory $roomScoreCategories)
    {
        return $roomScoreCategories;
    }

    public function update(Request $request, RoomScoreCategory $roomScoreCategories)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms'],
            'score_category_id' => ['required', 'exists:score_categories'],
        ]);

        $roomScoreCategories->update($data);

        return $roomScoreCategories;
    }

    public function destroy(RoomScoreCategory $roomScoreCategories)
    {
        $roomScoreCategories->delete();

        return response()->json();
    }
}
