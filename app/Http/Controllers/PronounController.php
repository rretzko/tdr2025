<?php

namespace App\Http\Controllers;

use App\Models\Pronoun;
use Illuminate\Http\Request;

class PronounController extends Controller
{
    public function index()
    {
        return Pronoun::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descr' => ['required'],
            'intensive' => ['required'],
            'personal' => ['required'],
            'possessive' => ['required'],
            'object' => ['required'],
            'order_by' => ['required', 'integer'],
        ]);

        return Pronoun::create($data);
    }

    public function show(Pronoun $pronoun)
    {
        return $pronoun;
    }

    public function update(Request $request, Pronoun $pronoun)
    {
        $data = $request->validate([
            'descr' => ['required'],
            'intensive' => ['required'],
            'personal' => ['required'],
            'possessive' => ['required'],
            'object' => ['required'],
            'order_by' => ['required', 'integer'],
        ]);

        $pronoun->update($data);

        return $pronoun;
    }

    public function destroy(Pronoun $pronoun)
    {
        $pronoun->delete();

        return response()->json();
    }
}
