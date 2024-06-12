<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return Event::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'short_name' => ['required'],
            'organization_name' => ['required'],
            'user_id' => ['required', 'exists:users'],
        ]);

        return Event::create($data);
    }

    public function show(Event $event)
    {
        return $event;
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name' => ['required'],
            'short_name' => ['required'],
            'organization_name' => ['required'],
            'user_id' => ['required', 'exists:users'],
        ]);

        $event->update($data);

        return $event;
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json();
    }
}
