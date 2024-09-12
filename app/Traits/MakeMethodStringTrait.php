<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait MakeMethodStringTrait
{
    public static function makeMethodString(Request $request)
    {
        $parts = explode('/', $request->getPathInfo());

        $proxyController = ucwords(end($parts)).'Controller';

        $header = "App\Http\Controllers\Tdr\\";

        $footer = "::__invoke";

        return $header.$proxyController.$footer;
    }
}
