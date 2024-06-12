<?php

namespace App\Http\Controllers;

use App\Models\UserFilter;
use Illuminate\Http\Request;

class UserFilterController extends Controller
{
    public function index()
    {
        return UserFilter::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'header' => ['required'],
            'schools' => ['required'],
        ]);

        return UserFilter::create($data);
    }

    public function show(UserFilter $userFilter)
    {
        return $userFilter;
    }

    public function update(Request $request, UserFilter $userFilter)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'header' => ['required'],
            'schools' => ['required'],
        ]);

        $userFilter->update($data);

        return $userFilter;
    }

    public function destroy(UserFilter $userFilter)
    {
        $userFilter->delete();

        return response()->json();
    }
}
