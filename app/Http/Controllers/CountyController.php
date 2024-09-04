<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountyRequest;
use App\Models\County;
use Carbon\Carbon;

class CountyController extends Controller
{
    public function index()
    {
        return County::all();
    }

    public function store(CountyRequest $request)
    {
        return County::create($request->validated());
    }

    public function show(County $county)
    {
        return $county;
    }

    public function update(CountyRequest $request, County $county)
    {
        $county->update($request->validated());

        return $county;
    }

    public function destroy(County $county)
    {
        $county->delete();

        return response()->json();
    }
}
