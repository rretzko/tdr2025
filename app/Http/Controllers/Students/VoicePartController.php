<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Students\VoicePart;
use Illuminate\Http\Request;

class VoicePartController extends Controller
{
    public function index()
    {
        return VoicePart::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descr' => ['required'],
            'abbr' => ['required'],
            'order_by' => ['required', 'integer'],
        ]);

        return VoicePart::create($data);
    }

    public function show(VoicePart $voicePart)
    {
        return $voicePart;
    }

    public function update(Request $request, VoicePart $voicePart)
    {
        $data = $request->validate([
            'descr' => ['required'],
            'abbr' => ['required'],
            'order_by' => ['required', 'integer'],
        ]);

        $voicePart->update($data);

        return $voicePart;
    }

    public function destroy(VoicePart $voicePart)
    {
        $voicePart->delete();

        return response()->json();
    }
}
