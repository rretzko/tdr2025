<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Room;
use Illuminate\Http\Request;

class RoomsController extends Controller
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

    public function show(Room $rooms)
    {
        return $rooms;
    }

    public function update(Request $request, Room $rooms)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'room_name' => ['required'],
            'tolerance' => ['required', 'integer'],
            'order_by' => ['required', 'integer'],
        ]);

        $rooms->update($data);

        return $rooms;
    }

    public function destroy(Room $rooms)
    {
        $rooms->delete();

        return response()->json();
    }
}
