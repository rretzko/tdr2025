<?php

namespace App\Http\Controllers\Ensembles;

use App\Http\Controllers\Controller;
use App\Models\Ensembles\AssetEnsemble;
use Illuminate\Http\Request;

class AssetEnsembleController extends Controller
{
    public function index()
    {
        return AssetEnsemble::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ensemble_id' => ['required', 'exists:ensembles'],
            'asset_id' => ['required', 'exists:assets'],
        ]);

        return AssetEnsemble::create($data);
    }

    public function show(AssetEnsemble $assetEnsemble)
    {
        return $assetEnsemble;
    }

    public function update(Request $request, AssetEnsemble $assetEnsemble)
    {
        $data = $request->validate([
            'ensemble_id' => ['required', 'exists:ensembles'],
            'asset_id' => ['required', 'exists:assets'],
        ]);

        $assetEnsemble->update($data);

        return $assetEnsemble;
    }

    public function destroy(AssetEnsemble $assetEnsemble)
    {
        $assetEnsemble->delete();

        return response()->json();
    }
}
