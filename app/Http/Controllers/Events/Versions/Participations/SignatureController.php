<?php

namespace App\Http\Controllers\Events\Versions\Participations;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Signature;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function index()
    {
        return Signature::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'candidate_id' => ['required', 'exists:candidates'],
            'user_id' => ['required', 'exists:users'],
            'role' => ['required'],
            'signed' => ['boolean'],
        ]);

        return Signature::create($data);
    }

    public function show(Signature $signature)
    {
        return $signature;
    }

    public function update(Request $request, Signature $signature)
    {
        $data = $request->validate([
            'version_id' => ['required', 'exists:versions'],
            'candidate_id' => ['required', 'exists:candidates'],
            'user_id' => ['required', 'exists:users'],
            'role' => ['required'],
            'signed' => ['boolean'],
        ]);

        $signature->update($data);

        return $signature;
    }

    public function destroy(Signature $signature)
    {
        $signature->delete();

        return response()->json();
    }
}
