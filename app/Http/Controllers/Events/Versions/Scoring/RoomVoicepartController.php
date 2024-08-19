<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\RoomVoicepart;
use Illuminate\Http\Request;

class RoomVoicepartController extends Controller
{
    public function index()
    {
        return RoomVoicepart::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
        ]);

        return RoomVoicepart::create($data);
    }

    public function show(RoomVoicepart $roomVoicepart)
    {
        return $roomVoicepart;
    }

    public function update(Request $request, RoomVoicepart $roomVoicepart)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
        ]);

        $roomVoicepart->update($data);

        return $roomVoicepart;
    }

    public function destroy(RoomVoicepart $roomVoicepart)
    {
        $roomVoicepart->delete();

        return response()->json();
    }
}
