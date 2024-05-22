<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageViewRequest;
use App\Models\PageView;

class PageViewController extends Controller
{
    public function index()
    {
        return PageView::all();
    }

    public function store(PageViewRequest $request)
    {
        return PageView::create($request->validated());
    }

    public function show(PageView $pageView)
    {
        return $pageView;
    }

    public function update(PageViewRequest $request, PageView $pageView)
    {
        $pageView->update($request->validated());

        return $pageView;
    }

    public function destroy(PageView $pageView)
    {
        $pageView->delete();

        return response()->json();
    }
}
