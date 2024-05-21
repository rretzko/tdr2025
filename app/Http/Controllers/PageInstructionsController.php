<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageInstructionsRequest;
use App\Models\PageInstruction;

class PageInstructionsController extends Controller
{
    public function index()
    {
        return PageInstruction::all();
    }

    public function store(PageInstructionsRequest $request)
    {
        return PageInstruction::create($request->validated());
    }

    public function show(PageInstruction $pageInstructions)
    {
        return $pageInstructions;
    }

    public function update(PageInstructionsRequest $request, PageInstruction $pageInstructions)
    {
        $pageInstructions->update($request->validated());

        return $pageInstructions;
    }

    public function destroy(PageInstruction $pageInstructions)
    {
        $pageInstructions->delete();

        return response()->json();
    }
}
