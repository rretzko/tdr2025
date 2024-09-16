<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use Illuminate\Http\Request;

class RoomVoicepartController extends Controller
{
    public function index()
    {
        return RoomVoicePart::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
        ]);

        return RoomVoicePart::create($data);
    }

    public function show(RoomVoicePart $roomVoicepart)
    {
        return $roomVoicepart;
    }

    public function update(Request $request, RoomVoicePart $roomVoicepart)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms'],
            'voice_part_id' => ['required', 'exists:voice_parts'],
        ]);

        $roomVoicepart->update($data);

        return $roomVoicepart;
    }

    public function destroy(RoomVoicePart $roomVoicepart)
    {
        $roomVoicepart->delete();

        return response()->json();
    }
}
