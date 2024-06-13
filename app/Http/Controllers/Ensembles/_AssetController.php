<?php

namespace App\Http\Controllers\Ensembles;

use App\Http\Controllers\Controller;
use App\Models\Ensembles\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        return Asset::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        return Asset::create($data);
    }

    public function show(Asset $asset)
    {
        return $asset;
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        $asset->update($data);

        return $asset;
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->json();
    }
}
