<?php

namespace App\Http\Controllers;

use App\Imports\EpaymentPayPalImport;
use App\Imports\EpaymentSquareImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EpaymentUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $upload = ($request['vendor'] === 'paypal')
            ? Excel::import(new EpaymentPayPalImport, request()->file('transaction'), \Maatwebsite\Excel\Excel::CSV)
            : Excel::import(new EpaymentSquareImport, request()->file('transaction'), \Maatwebsite\Excel\Excel::CSV);

        return redirect()->back();
    }
}
