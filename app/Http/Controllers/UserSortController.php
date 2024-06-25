<?php

namespace App\Http\Controllers;

use App\Models\UserSort;
use Illuminate\Http\Request;

class UserSortController extends Controller
{
    public function index()
    {
        return UserSort::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'header' => ['required'],
            'column' => ['required'],
            'asc' => ['boolean'],
        ]);

        return UserSort::create($data);
    }

    public function show(UserSort $userSort)
    {
        return $userSort;
    }

    public function update(Request $request, UserSort $userSort)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'header' => ['required'],
            'column' => ['required'],
            'asc' => ['boolean'],
        ]);

        $userSort->update($data);

        return $userSort;
    }

    public function destroy(UserSort $userSort)
    {
        $userSort->delete();

        return response()->json();
    }
}
