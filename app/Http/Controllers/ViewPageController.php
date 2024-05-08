<?php

namespace App\Http\Controllers;

use App\Http\Requests\ViewPageRequest;
use App\Models\ViewPage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ViewPageController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', ViewPage::class);

        return ViewPage::all();
    }

    public function store(ViewPageRequest $request)
    {
        $this->authorize('create', ViewPage::class);

        return ViewPage::create($request->validated());
    }

    public function show(ViewPage $viewPage)
    {
        $this->authorize('view', $viewPage);

        return $viewPage;
    }

    public function update(ViewPageRequest $request, ViewPage $viewPage)
    {
        $this->authorize('update', $viewPage);

        $viewPage->update($request->validated());

        return $viewPage;
    }

    public function destroy(ViewPage $viewPage)
    {
        $this->authorize('delete', $viewPage);

        $viewPage->delete();

        return response()->json();
    }
}
