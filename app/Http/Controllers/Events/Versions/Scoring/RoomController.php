<?php

namespace App\Http\Controllers\Events\Versions\Scoring;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Scoring\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'room_name' => ['required'],
            'tolerance' => ['required', 'integer'],
            'order_by' => ['required', 'integer'],
        ]);

        return Room::create($data);
    }

    public function show(Room $room)
    {
        return $room;
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'room_name' => ['required'],
            'tolerance' => ['required', 'integer'],
            'order_by' => ['required', 'integer'],
        ]);

        $room->update($data);

        return $room;
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return response()->json();
    }
}
