<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeostateRequest;
use App\Models\Geostate;

class GeostateController extends Controller
{
    public function index()
    {
        return Geostate::all();
    }

    public function store(GeostateRequest $request)
    {
        return Geostate::create($request->validated());
    }

    public function show(Geostate $geostate)
    {
        return $geostate;
    }

    public function update(GeostateRequest $request, Geostate $geostate)
    {
        $geostate->update($request->validated());

        return $geostate;
    }

    public function destroy(Geostate $geostate)
    {
        $geostate->delete();

        return response()->json();
    }
}
