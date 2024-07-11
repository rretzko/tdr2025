<?php

namespace App\Http\Controllers;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FounderController extends Controller
{
    public function __invoke()
    {
        $dto = [];
        $dto['header'] = 'founder page';
        return view('pages.rickPage', compact('dto'));
    }
}
