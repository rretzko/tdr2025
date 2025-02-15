<?php

namespace App\Http\Controllers\Ensembles;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Ensembles\Ensemble;
use App\Services\MissingGradesService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EnsembleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        //abort if user should not have access to the module
        //i.e. grades or gradesITeach is missing from the UserConfig::getValue('schoolId') school profile
        if (MissingGradesService::missingGrades()) {
            abort(404);
        }

        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function edit(Request $request, Ensemble $ensemble)
    {
        //only Ensemble originator (teacher_id can edit the Ensemble model
        if ($request->user()->cannot('edit', $ensemble)) {
            abort(403);
        }

        $data = new ViewDataFactory(__METHOD__, $ensemble->id);

        $dto = $data->getDto();

        $id = $ensemble->id;

        return view($dto['pageName'], compact('dto', 'id'));
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
        $this->authorize('create', Ensemble::class);

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
