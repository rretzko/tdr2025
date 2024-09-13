<?php

namespace App\Http\Controllers\Events\Versions;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Rooms;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function index()
    {
        return Rooms::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'room_name' => ['required'],
            'tolerance' => ['required', 'integer'],
            'order_by' => ['required', 'integer'],
        ]);

        return Rooms::create($data);
    }

    public function show(Rooms $rooms)
    {
        return $rooms;
    }

    public function update(Request $request, Rooms $rooms)
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

    public function destroy(Rooms $rooms)
    {
        $rooms->delete();

        return response()->json();
    }
}
