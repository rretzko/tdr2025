<?php

namespace App\Http\Controllers\Founders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogInAsController extends Controller
{
    public function __invoke(Request $request)
    {
        Auth::loginUsingId($request['user_id']);

        return redirect('home');
    }
}
