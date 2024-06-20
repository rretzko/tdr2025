<?php

namespace App\Http\Controllers;

use App\Models\UserConfig;
use Illuminate\Http\Request;

class UserConfigController extends Controller
{
    public function index()
    {
        return UserConfig::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'header' => ['required'],
            'label' => ['required'],
            'value' => ['required'],
        ]);

        return UserConfig::create($data);
    }

    public function show(UserConfig $userConfig)
    {
        return $userConfig;
    }

    public function update(Request $request, UserConfig $userConfig)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'header' => ['required'],
            'label' => ['required'],
            'value' => ['required'],
        ]);

        $userConfig->update($data);

        return $userConfig;
    }

    public function destroy(UserConfig $userConfig)
    {
        $userConfig->delete();

        return response()->json();
    }
}
