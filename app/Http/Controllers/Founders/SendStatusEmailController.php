<?php

declare(strict_types=1);

namespace App\Http\Controllers\Founders;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SendStatusEmailController extends Controller
{
    public function __invoke()
    {
        Artisan::call('email:registration-status', ['--test' => true]);

        return redirect()->route('founder')->with('success', 'Status email(s) sent.');
    }
}
