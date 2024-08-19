<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\Judge;
use Illuminate\Http\Request;

class JudgeController extends Controller
{
    public function index()
    {
        return Judge::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'room_id' => ['required', 'exists:rooms'],
            'user_id' => ['required', 'exists:users'],
            'judge_role' => ['required'],
        ]);

        return Judge::create($data);
    }

    public function show(Judge $judge)
    {
        return $judge;
    }

    public function update(Request $request, Judge $judge)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'room_id' => ['required', 'exists:rooms'],
            'user_id' => ['required', 'exists:users'],
            'judge_role' => ['required'],
        ]);

        $judge->update($data);

        return $judge;
    }

    public function destroy(Judge $judge)
    {
        $judge->delete();

        return response()->json();
    }
}
