<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\EventManagement;
use Illuminate\Http\Request;

class EventManagementController extends Controller
{
    public function index()
    {
        return EventManagement::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'user_id' => ['required', 'exists:users'],
            'role' => ['required'],
        ]);

        return EventManagement::create($data);
    }

    public function show(EventManagement $eventManagement)
    {
        return $eventManagement;
    }

    public function update(Request $request, EventManagement $eventManagement)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'user_id' => ['required', 'exists:users'],
            'role' => ['required'],
        ]);

        $eventManagement->update($data);

        return $eventManagement;
    }

    public function destroy(EventManagement $eventManagement)
    {
        $eventManagement->delete();

        return response()->json();
    }
}
