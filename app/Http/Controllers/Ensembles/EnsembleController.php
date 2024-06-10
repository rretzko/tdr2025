<?php

namespace App\Http\Controllers\Ensembles;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Ensembles\Ensemble;
use Illuminate\Http\Request;

class EnsembleController extends Controller
{
    public function index()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => ['required', 'exists:schools'],
            'name' => ['required'],
            'short_name' => ['required'],
            'abbr' => ['required'],
            'active' => ['boolean'],
        ]);

        return Ensemble::create($data);
    }

    public function create()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function show(Ensemble $ensemble)
    {
        return $ensemble;
    }

    public function update(Request $request, Ensemble $ensemble)
    {
        $data = $request->validate([
            'school_id' => ['required', 'exists:schools'],
            'name' => ['required'],
            'short_name' => ['required'],
            'abbr' => ['required'],
            'active' => ['boolean'],
        ]);

        $ensemble->update($data);

        return $ensemble;
    }

    public function destroy(Ensemble $ensemble)
    {
        $ensemble->delete();

        return response()->json();
    }
}
