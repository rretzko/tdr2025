<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\EventEnsemble;
use Illuminate\Http\Request;

class EventEnsembleController extends Controller
{
    public function index()
    {
        return EventEnsemble::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'ensemble_name' => ['required'],
            'ensemble_short_name' => ['required'],
            'grades' => ['required'],
            'voice_part_ids' => ['required'],
        ]);

        return EventEnsemble::create($data);
    }

    public function show(EventEnsemble $eventEnsemble)
    {
        return $eventEnsemble;
    }

    public function update(Request $request, EventEnsemble $eventEnsemble)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events'],
            'ensemble_name' => ['required'],
            'ensemble_short_name' => ['required'],
            'grades' => ['required'],
            'voice_part_ids' => ['required'],
        ]);

        $eventEnsemble->update($data);

        return $eventEnsemble;
    }

    public function destroy(EventEnsemble $eventEnsemble)
    {
        $eventEnsemble->delete();

        return response()->json();
    }
}
